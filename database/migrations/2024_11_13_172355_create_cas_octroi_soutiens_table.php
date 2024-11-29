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
        Schema::create('cas_octroi_soutiens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("avec_id");
            $table->foreign("avec_id")->references("id")->on("avecs")->cascadeOnDelete();
            $table->text("cas");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cas_octroi_soutiens');
    }
};
