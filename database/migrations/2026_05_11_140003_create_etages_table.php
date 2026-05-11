<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batiment_id')->constrained('batiments')->cascadeOnDelete();
            $table->integer('numero');
            $table->string('nom')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['batiment_id', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etages');
    }
};
