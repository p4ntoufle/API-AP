<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pension_id');
            $table->decimal('superficie', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('pension_id')->references('id')->on('pensions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boxes');
    }
};

