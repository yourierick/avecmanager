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
        Schema::create('soutien_caisse_solidarites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("avec_id");
            $table->foreign("avec_id")->references('id')->on('avecs')->cascadeOnDelete();
            $table->text("cas");
            $table->unsignedBigInteger("beneficiaire")->nullable();
            $table->foreign("beneficiaire")->references('id')->on('membres')->onDelete("set null");;
            $table->double("montant");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soutien_caisse_solidarites');
    }
};
