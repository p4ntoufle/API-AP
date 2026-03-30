<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pensions', function (Blueprint $table) {
            $table->string('ville')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('responsable')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pensions', function (Blueprint $table) {
            $table->dropColumn(['ville', 'adresse', 'telephone', 'responsable']);
        });
    }
};

