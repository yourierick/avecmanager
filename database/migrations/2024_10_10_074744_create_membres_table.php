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
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('avec_id');
            $table->foreign('avec_id')->references('id')->on('avecs')->cascadeOnDelete();
            $table->text('photo')->nullable();
            $table->string('nom', 100);
            $table->string('sexe', 100);
            $table->string('adresse', 255);
            $table->string('numeros_de_telephone', 255)->nullable();
            $table->string('statut', '20')->default("actif");
            $table->double('part_tot_achetees')->default(0);
            $table->double('gains_actuels')->default(0);
            $table->double('credit')->default(0)->nullable();
            $table->double('interets_sur_credit')->default(0)->nullable();
            $table->date('date_de_remboursement')->nullable();
            $table->string('gains')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membres');
    }
};
