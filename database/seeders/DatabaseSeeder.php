<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@hopital.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $service = Service::with('etage.batiment')->orderBy('id')->first();
        $etablissementId = $service?->etage?->batiment?->etablissement_id;

        User::updateOrCreate(
            ['email' => 'secretaire@hopital.com'],
            [
                'name' => 'Secrétaire Démo',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SECRETAIRE,
                'etablissement_id' => $etablissementId,
                'service_id' => $service?->id,
                'telephone' => '0600000001',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Comptes prêts:');
        $this->command->line('  → admin@hopital.com / password');
        $this->command->line('  → secretaire@hopital.com / password' . ($service ? " (service: {$service->nom})" : ' (à affecter à un service)'));
    }
}
