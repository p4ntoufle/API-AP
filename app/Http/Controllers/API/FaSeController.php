<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FaSeController extends Controller
{
    private string $invoiceDisk = 'local';

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $query = Facture::with(['pension', 'user'])
            ->orderByDesc('issued_at')
            ->orderByDesc('created_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->input('user_id'));
        } elseif ($request->user()) {
            $query->where('user_id', $request->user()->id);
        }

        if ($request->filled('pension_id')) {
            $query->where('pension_id', (int) $request->input('pension_id'));
        }

        return response()->json(
            $query->paginate($perPage)->withQueryString()
        );
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        $this->ensureChronology($validated);

        if (! array_key_exists('animals_count', $validated)) {
            $validated['animals_count'] = 1;
        }

        $validated['pdf_path'] = $this->storePdf(
            (int) $validated['user_id'],
            $request->file('pdf'),
            $validated['numero']
        );

        unset($validated['pdf']);

        $facture = Facture::create($validated);

        return response()->json($facture->load(['pension', 'user']), 201);
    }

    public function show(Facture $facture)
    {
        return response()->json($facture->load(['pension', 'user']));
    }

    public function update(Request $request, Facture $facture)
    {
        $validated = $this->validatePayload($request, $facture);

        $this->ensureChronology($validated, $facture);

        if ($request->hasFile('pdf')) {
            $this->deletePdf($facture->pdf_path);
            $validated['pdf_path'] = $this->storePdf(
                (int) ($validated['user_id'] ?? $facture->user_id),
                $request->file('pdf'),
                $validated['numero'] ?? $facture->numero
            );
            unset($validated['pdf']);
        }

        $facture->update($validated);

        return response()->json($facture->fresh()->load(['pension', 'user']));
    }

    public function destroy(Facture $facture)
    {
        $this->deletePdf($facture->pdf_path);
        $facture->delete();

        return response()->noContent();
    }

    public function download(Request $request, Facture $facture): StreamedResponse
    {
        $this->authorizeFactureAccess($request, $facture);

        if (! Storage::disk($this->invoiceDisk)->exists($facture->pdf_path)) {
            abort(404, 'Le fichier de cette facture est introuvable.');
        }

        $filename = sprintf(
            '%s-%s.pdf',
            Str::slug(optional($facture->pension)->nom ?? 'facture'),
            $facture->numero
        );

        return Storage::disk($this->invoiceDisk)->download($facture->pdf_path, $filename);
    }

    private function validatePayload(Request $request, ?Facture $facture = null): array
    {
        $rules = [
            'user_id' => [$facture ? 'sometimes' : 'required', 'exists:users,id'],
            'pension_id' => [$facture ? 'sometimes' : 'required', 'exists:pensions,id'],
            'numero' => [
                $facture ? 'sometimes' : 'required',
                'string',
                'max:100',
                $this->numeroRule($request, $facture),
            ],
            'issued_at' => [$facture ? 'sometimes' : 'required', 'date'],
            'stay_start_at' => ['nullable', 'date'],
            'stay_end_at' => [$facture ? 'sometimes' : 'required', 'date'],
            'animals_count' => ['nullable', 'integer', 'min:1', 'max:500'],
            'total_ht' => ['nullable', 'numeric', 'min:0'],
            'total_ttc' => ['nullable', 'numeric', 'min:0'],
            'pdf' => [$facture ? 'sometimes' : 'required', 'file', 'mimes:pdf', 'max:20480'],
        ];

        return $request->validate($rules);
    }

    private function numeroRule(Request $request, ?Facture $facture = null): Rule
    {
        $pensionId = $request->input('pension_id', $facture?->pension_id);

        return Rule::unique('factures', 'numero')
            ->ignore($facture?->id)
            ->where(function ($query) use ($pensionId) {
                if ($pensionId) {
                    $query->where('pension_id', $pensionId);
                }
            });
    }

    private function ensureChronology(array $validated, ?Facture $facture = null): void
    {
        $start = $validated['stay_start_at'] ?? $facture?->stay_start_at;
        $end = $validated['stay_end_at'] ?? $facture?->stay_end_at;

        if ($start && $end && Carbon::parse($start)->gt(Carbon::parse($end))) {
            throw ValidationException::withMessages([
                'stay_start_at' => 'La date de début doit être antérieure ou égale à la date de fin.',
            ]);
        }
    }

    private function storePdf(int $userId, UploadedFile $file, string $numero): string
    {
        $filename = sprintf(
            '%s-%s.pdf',
            now()->format('YmdHis'),
            Str::slug($numero)
        );

        return $file->storeAs("factures/{$userId}", $filename, $this->invoiceDisk);
    }

    private function deletePdf(?string $path): void
    {
        if ($path && Storage::disk($this->invoiceDisk)->exists($path)) {
            Storage::disk($this->invoiceDisk)->delete($path);
        }
    }

    private function authorizeFactureAccess(Request $request, Facture $facture): void
    {
        $user = $request->user() ?? Auth::user();

        if (! $user || $user->id !== $facture->user_id) {
            abort(403, 'Vous ne pouvez pas accéder à cette facture.');
        }
    }
}
