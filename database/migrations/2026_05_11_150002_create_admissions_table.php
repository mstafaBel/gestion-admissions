<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('lit_id')->constrained('lits')->restrictOnDelete();
            $table->foreignId('service_id')->constrained('services')->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('terminated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('date_entree');
            $table->dateTime('date_sortie')->nullable();
            $table->string('motif')->nullable();
            $table->text('observations')->nullable();
            $table->enum('statut', ['en_cours', 'terminee', 'transferee'])->default('en_cours');
            $table->foreignId('transferee_vers_admission_id')->nullable()->constrained('admissions')->nullOnDelete();
            $table->timestamps();

            $table->index(['statut']);
            $table->index(['service_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
