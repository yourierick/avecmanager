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
        Schema::create('comite_avecs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('avec_id');
            $table->foreign('avec_id')->references('id')->on('avecs')->cascadeOnDelete();
            $table->unsignedBigInteger('membre_id');
            $table->foreign('membre_id')->references('id')->on('membres')->cascadeOnDelete();
            $table->string('fonction', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comite_avecs');
    }
};
