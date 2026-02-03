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
    Schema::create('reparations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('voiture_id')->constrained()->cascadeOnDelete();
        $table->enum('statut', ['en_attente','en_cours','terminee'])->default('en_attente');
        $table->integer('slot')->nullable(); // 1 ou 2
        $table->timestamp('started_at')->nullable();
        $table->timestamp('ended_at')->nullable();
        $table->decimal('total',10,2)->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reparations');
    }
};
