<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etage_id')->constrained('etages')->cascadeOnDelete();
            $table->string('nom');
            $table->string('code')->nullable();
            $table->string('responsable')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['etage_id', 'nom']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
