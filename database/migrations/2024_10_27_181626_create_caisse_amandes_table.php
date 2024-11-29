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
        Schema::create('caisse_amandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("projet_id");
            $table->foreign("projet_id")->references('id')->on('projet_avecs')->cascadeOnDelete();
            $table->unsignedBigInteger("avec_id");
            $table->foreign("avec_id")->references('id')->on('avecs')->cascadeOnDelete();
            $table->double('montant')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caisse_amandes');
    }
};
