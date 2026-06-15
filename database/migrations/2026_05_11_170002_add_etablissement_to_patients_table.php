<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('etablissement_id')
                ->nullable()
                ->after('id')
                ->constrained('etablissements')
                ->nullOnDelete();
            $table->index('etablissement_id');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['etablissement_id']);
            $table->dropIndex(['etablissement_id']);
            $table->dropColumn('etablissement_id');
        });
    }
};
