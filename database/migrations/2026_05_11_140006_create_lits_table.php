<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chambre_id')->constrained('chambres')->cascadeOnDelete();
            $table->string('numero');
            $table->enum('statut', ['libre', 'occupe', 'maintenance', 'reserve'])->default('libre');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['chambre_id', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lits');
    }
};
