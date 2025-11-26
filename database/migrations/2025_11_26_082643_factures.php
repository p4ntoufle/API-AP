<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pension_id')->constrained()->cascadeOnDelete();
            $table->string('numero');
            $table->date('issued_at');
            $table->date('stay_start_at')->nullable();
            $table->date('stay_end_at');
            $table->unsignedSmallInteger('animals_count')->default(1);
            $table->decimal('total_ht', 10, 2)->nullable();
            $table->decimal('total_ttc', 10, 2)->nullable();
            $table->string('pdf_path');
            $table->timestamps();

            $table->unique(['pension_id', 'numero']);
            $table->index(['user_id', 'stay_end_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
