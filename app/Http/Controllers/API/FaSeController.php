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
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FaSeController extends Controller
{
    private string $invoiceDisk = 'local';

    /**
     * Lister les factures.
     *
     * @OA\Get(
     *     path="/api/factures",
     *     summary="Liste les factures (paginées)",
     *     tags={"Factures"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", default=15)),
     *     @OA\Parameter(name="user_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="pension_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Liste paginée des factures"),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
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

    /**
     * Créer une facture.
     *
     * @OA\Post(
     *     path="/api/factures",
     *     summary="Créer une nouvelle facture",
     *     tags={"Factures"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"user_id","pension_id","numero","issued_at","stay_end_at"},
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="pension_id", type="integer"),
     *                 @OA\Property(property="numero", type="string"),
     *                 @OA\Property(property="issued_at", type="string", format="date"),
     *                 @OA\Property(property="stay_start_at", type="string", format="date"),
     *                 @OA\Property(property="stay_end_at", type="string", format="date"),
     *                 @OA\Property(property="animals_count", type="integer"),
     *                 @OA\Property(property="total_ht", type="number"),
     *                 @OA\Property(property="total_ttc", type="number"),
     *                 @OA\Property(property="pdf", type="string", format="binary"),
     *                 @OA\Property(property="pdf_path", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Facture créée"),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        $this->ensureChronology($validated);

        if (! array_key_exists('animals_count', $validated)) {
            $validated['animals_count'] = 1;
        }

        if ($request->hasFile('pdf')) {
            $validated['pdf_path'] = $this->storePdf(
                (int) $validated['user_id'],
                $request->file('pdf'),
                $validated['numero']
            );
            unset($validated['pdf']);
        } else {
            $validated['pdf_path'] = $this->normalizeStoredPath($validated['pdf_path'] ?? '');
            $this->assertPdfExists($validated['pdf_path']);
        }

        $facture = Facture::create($validated);

        return response()->json($facture->load(['pension', 'user']), 201);
    }

    /**
     * Afficher une facture.
     *
     * @OA\Get(
     *     path="/api/factures/{facture}",
     *     summary="Afficher une facture",
     *     tags={"Factures"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="facture", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Détails de la facture"),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=404, description="Facture non trouvée")
     * )
     */
    public function show(Facture $facture)
    {
        return response()->json($facture->load(['pension', 'user']));
    }

    /**
     * Mettre à jour une facture.
     *
     * @OA\Put(
     *     path="/api/factures/{facture}",
     *     summary="Mettre à jour une facture",
     *     tags={"Factures"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="facture", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="pension_id", type="integer"),
     *             @OA\Property(property="numero", type="string"),
     *             @OA\Property(property="issued_at", type="string", format="date"),
     *             @OA\Property(property="stay_start_at", type="string", format="date"),
     *             @OA\Property(property="stay_end_at", type="string", format="date"),
     *             @OA\Property(property="animals_count", type="integer"),
     *             @OA\Property(property="total_ht", type="number"),
     *             @OA\Property(property="total_ttc", type="number"),
     *             @OA\Property(property="pdf_path", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Facture mise à jour"),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=404, description="Facture non trouvée"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
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
        } elseif (array_key_exists('pdf_path', $validated) && $validated['pdf_path']) {
            $validated['pdf_path'] = $this->normalizeStoredPath($validated['pdf_path']);
            $this->assertPdfExists($validated['pdf_path']);
        } else {
            unset($validated['pdf_path']);
        }

        $facture->update($validated);

        return response()->json($facture->fresh()->load(['pension', 'user']));
    }

    /**
     * Supprimer une facture.
     *
     * @OA\Delete(
     *     path="/api/factures/{facture}",
     *     summary="Supprimer une facture",
     *     tags={"Factures"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="facture", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Facture supprimée"),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=404, description="Facture non trouvée")
     * )
     */
    public function destroy(Facture $facture)
    {
        $this->deletePdf($facture->pdf_path);
        $facture->delete();

        return response()->noContent();
    }

    /**
     * Télécharger le PDF d'une facture.
     *
     * @OA\Get(
     *     path="/api/factures/{facture}/download",
     *     summary="Télécharger le PDF d'une facture",
     *     tags={"Factures"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="facture", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Fichier PDF"),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=403, description="Accès interdit"),
     *     @OA\Response(response=404, description="Fichier introuvable")
     * )
     */
    public function download(Request $request, Facture $facture): StreamedResponse
    {
        $this->authorizeFactureAccess($request, $facture);

        $target = $this->resolvePdfTarget($facture->pdf_path);

        if (! $target) {
            abort(404, 'Le fichier de cette facture est introuvable.');
        }

        [$disk, $path] = $target;

        $filename = sprintf(
            '%s-%s.pdf',
            Str::slug(optional($facture->pension)->nom ?? 'facture'),
            $facture->numero
        );

        if ($disk === null) {
            return response()->download($path, $filename);
        }

        return Storage::disk($disk)->download($path, $filename);
    }

    private function validatePayload(Request $request, ?Facture $facture = null): array
    {
        $pdfRules = ['file', 'mimes:pdf', 'max:20480'];

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
            'pdf' => array_merge(
                [$facture ? 'sometimes' : 'required_without:pdf_path'],
                $pdfRules
            ),
            'pdf_path' => $facture
                ? ['sometimes', 'nullable', 'string', 'max:500']
                : ['required_without:pdf', 'nullable', 'string', 'max:500'],
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
        if (! $path) {
            return;
        }

        $target = $this->resolvePdfTarget($path);

        if (! $target) {
            return;
        }

        [$disk, $resolved] = $target;

        if ($disk === null) {
            @unlink($resolved);

            return;
        }

        Storage::disk($disk)->delete($resolved);
    }

    private function authorizeFactureAccess(Request $request, Facture $facture): void
    {
        $user = $request->user() ?? Auth::user();

        if (! $user || $user->id !== $facture->user_id) {
            abort(403, 'Vous ne pouvez pas accéder à cette facture.');
        }
    }

    private function normalizeStoredPath(string $path): string
    {
        $normalized = str_replace('\\', '/', $path);
        $normalized = preg_replace('#\.\.+#', '', $normalized ?? '') ?: '';
        $normalized = trim($normalized);

        return ltrim($normalized, '/');
    }

    private function resolvePdfTarget(?string $path): ?array
    {
        if (! $path) {
            return null;
        }

        $candidates = $this->candidatePaths($path);

        foreach ($this->disksToProbe() as $disk) {
            foreach ($candidates as $candidate) {
                if (Storage::disk($disk)->exists($candidate)) {
                    return [$disk, $candidate];
                }
            }
        }

        foreach ($candidates as $candidate) {
            foreach ($this->absolutePathCandidates($candidate) as $absolute) {
                if ($absolute && is_file($absolute)) {
                    return [null, $absolute];
                }
            }
        }

        return null;
    }

    private function candidatePaths(string $path): array
    {
        $base = $this->normalizeStoredPath($path);

        if ($base === '') {
            return [];
        }

        $candidates = [$base];

        $prefixesToStrip = [
            'storage/app/public/',
            'storage/app/private/',
            'storage/app/',
            'storage/',
            'app/',
        ];

        foreach ($prefixesToStrip as $prefix) {
            if (Str::startsWith($base, $prefix)) {
                $candidates[] = substr($base, strlen($prefix));
            }
        }

        $prefixesToAdd = [
            '',
            'storage/',
            'storage/app/',
            'storage/app/public/',
            'storage/app/private/',
            'app/',
            'public/',
            'private/',
        ];

        $normalizedCandidates = [];
        foreach (array_filter(array_unique($candidates)) as $candidate) {
            $normalizedCandidates[] = $candidate;
            $candidate = ltrim($candidate, '/');
            foreach ($prefixesToAdd as $prefix) {
                $normalizedCandidates[] = ltrim($prefix . $candidate, '/');
            }
        }

        return array_values(array_unique(array_filter($normalizedCandidates)));
    }

    private function disksToProbe(): array
    {
        return array_values(array_unique(array_filter([
            $this->invoiceDisk,
            config('filesystems.default'),
            'public',
        ])));
    }

    /**
     * @return list<string|null>
     */
    private function absolutePathCandidates(string $relative): array
    {
        $relative = ltrim($relative, '/');

        return [
            $relative,
            storage_path($relative),
            storage_path("app/{$relative}"),
            storage_path("app/public/{$relative}"),
            storage_path("app/private/{$relative}"),
            public_path($relative),
            base_path($relative),
        ];
    }

    private function assertPdfExists(string $path): void
    {
        if (! $this->resolvePdfTarget($path)) {
            throw ValidationException::withMessages([
                'pdf_path' => 'Le fichier PDF indiqué est introuvable sur le serveur.',
            ]);
        }
    }
}
