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
        Schema::create('avecs', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100);
            $table->string('designation', 100);
            $table->unsignedBigInteger("axe_id")->nullable();
            $table->foreign("axe_id")->references("id")->on("axes_projets")->onDelete("set null");
            $table->double('valeur_part');
            $table->integer('maximum_part_achetable');
            $table->double('valeur_montant_solidarite');
            $table->unsignedBigInteger('animateur_id')->nullable();
            $table->foreign('animateur_id')->references('id')->on('users')->onDelete("set null");;
            $table->unsignedBigInteger('superviseur_id')->nullable();
            $table->foreign('superviseur_id')->references('id')->on('users')->onDelete("set null");;
            $table->unsignedBigInteger('projet_id');
            $table->foreign('projet_id')->references('id')->on('projet_avecs')->cascadeOnDelete();
            $table->double("interets")->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avecs');
    }
};
