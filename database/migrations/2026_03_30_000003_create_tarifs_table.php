<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pension_id');
            $table->unsignedBigInteger('type_gardiennage_id');
            $table->decimal('prix', 10, 2);
            $table->timestamps();

            $table->foreign('pension_id')->references('id')->on('pensions')->onDelete('cascade');
            $table->foreign('type_gardiennage_id')->references('id')->on('type_gardiennages')->onDelete('cascade');
            $table->unique(['pension_id', 'type_gardiennage_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifs');
    }
};

