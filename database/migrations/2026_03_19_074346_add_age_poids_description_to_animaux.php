<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animaux', function (Blueprint $table) {
            $table->integer('age')->nullable();
            $table->decimal('poids', 5, 2)->nullable();
            $table->text('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('animaux', function (Blueprint $table) {
            $table->dropColumn(['age', 'poids', 'description']);
        });
    }
};
