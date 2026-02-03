<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('reparation_intervention', function (Blueprint $table) {
            $table->id();
            

            $table->foreignId('reparation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('intervention_id')->constrained()->cascadeOnDelete();
            $table->enum('statut', ['en_attente', 'en_cours', 'terminee'])->default('en_attente');
            $table->integer('progression')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reparation_intervention');
    }
};
