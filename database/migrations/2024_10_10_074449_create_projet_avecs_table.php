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
        Schema::create('projet_avecs', function (Blueprint $table) {
            $table->id();
            $table->string('code_reference', 255);
            $table->text("context");
            $table->integer('cycle_de_gestion');
            $table->date('date_de_debut');
            $table->date('date_de_fin');
            $table->string('statut')->default('en attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projet_avecs');
    }
};
