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
        Schema::create('caisse_interets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("projet_id");
            $table->foreign("projet_id")->references('id')->on('projet_avecs')->cascadeOnDelete();
            $table->unsignedBigInteger("avec_id");
            $table->foreign("avec_id")->references('id')->on('avecs')->cascadeOnDelete();
            $table->unsignedBigInteger('mois_id');
            $table->foreign('mois_id')->references('id')->on('cycle_de_gestions')->cascadeOnDelete();
            $table->string("semaine", 15);
            $table->double('montant')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caisse_interets');
    }
};
