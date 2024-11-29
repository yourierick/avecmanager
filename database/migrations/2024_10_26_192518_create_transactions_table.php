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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('projet_id');
            $table->foreign('projet_id')->references('id')->on('projet_avecs')->cascadeOnDelete();
            $table->unsignedBigInteger('avec_id');
            $table->foreign('avec_id')->references('id')->on('avecs')->cascadeOnDelete();
            $table->unsignedBigInteger('membre_id')->nullable();
            $table->foreign('membre_id')->references("id")->on("membres")->onDelete("set null");;
            $table->string('statut_du_membre');
            $table->unsignedBigInteger('mois_id')->nullable();
            $table->foreign('mois_id')->references('id')->on('cycle_de_gestions');
            $table->string("semaine", 15);
            $table->date('semaine_debut');
            $table->date('semaine_fin');
            $table->date('date_de_la_reunion');
            $table->string('num_reunion');
            $table->string('frequentation');
            $table->double('parts_achetees')->default(0)->nullable();
            $table->double('cotisation')->default(0)->nullable();
            $table->double('amande')->default(0)->nullable();
            $table->double('credit')->default(0)->nullable();
            $table->double('taux_interet')->default(0)->nullable();
            $table->date('date_de_remboursement')->nullable();
            $table->double('credit_rembourse')->default(0)->nullable();
            $table->double('interet_genere')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
