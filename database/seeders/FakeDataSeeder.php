<?php

namespace Database\Seeders;

use App\Models\Admission;
use App\Models\Batiment;
use App\Models\Chambre;
use App\Models\Etablissement;
use App\Models\Etage;
use App\Models\Lit;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FakeDataSeeder extends Seeder
{
    /**
     * Génère 8 CHU marocains avec hiérarchie complète,
     * patients fictifs et admissions en cours.
     */
    public function run(): void
    {
        $etablissements = [
            ['nom' => 'CHU Ibn Sina', 'code' => 'CHUIS', 'ville' => 'Rabat', 'tel' => '0537750000', 'email' => 'contact@chu-ibnsina.ma'],
            ['nom' => 'CHU Ibn Rochd', 'code' => 'CHUIR', 'ville' => 'Casablanca', 'tel' => '0522482020', 'email' => 'contact@chu-ibnrochd.ma'],
            ['nom' => 'CHU Hassan II', 'code' => 'CHUH2', 'ville' => 'Fès', 'tel' => '0535619200', 'email' => 'contact@chu-fes.ma'],
            ['nom' => 'CHU Mohammed VI', 'code' => 'CHUM6M', 'ville' => 'Marrakech', 'tel' => '0524300700', 'email' => 'contact@chu-marrakech.ma'],
            ['nom' => 'CHU Mohammed VI', 'code' => 'CHUM6O', 'ville' => 'Oujda', 'tel' => '0536506000', 'email' => 'contact@chu-oujda.ma'],
            ['nom' => 'CHU Mohammed VI', 'code' => 'CHUM6T', 'ville' => 'Tanger', 'tel' => '0539394040', 'email' => 'contact@chu-tanger.ma'],
            ['nom' => 'Hôpital d\'Enfants de Rabat', 'code' => 'HER', 'ville' => 'Rabat', 'tel' => '0537671616', 'email' => 'contact@her.ma'],
            ['nom' => 'Maternité Souissi', 'code' => 'SOUISSI', 'ville' => 'Rabat', 'tel' => '0537656565', 'email' => 'contact@souissi.ma'],
        ];

        $batimentsParEtab = [
            ['Aile Principale', 'Aile Sud'],
            ['Bâtiment A', 'Bâtiment B', 'Bâtiment Annexe'],
            ['Aile Nord', 'Aile Est'],
            ['Pavillon Central', 'Pavillon Médico-Chirurgical'],
            ['Bâtiment Principal', 'Annexe Spécialités'],
            ['Aile Hospitalisation', 'Aile Spécialités'],
            ['Bâtiment Pédiatrie', 'Annexe Consultations'],
            ['Bâtiment Maternité', 'Annexe Néonatologie'],
        ];

        $servicesParType = [
            'general' => ['Cardiologie', 'Pneumologie', 'Néphrologie', 'Médecine interne', 'Neurologie', 'Chirurgie générale', 'Orthopédie', 'Traumatologie', 'Urgences', 'Réanimation', 'Oncologie', 'Gastro-entérologie', 'Endocrinologie', 'ORL'],
            'pediatrique' => ['Pédiatrie générale', 'Néonatologie', 'Chirurgie pédiatrique', 'Pédiatrie d\'urgence', 'Pédiatrie infectieuse', 'Hémato-oncologie pédiatrique'],
            'maternite' => ['Maternité', 'Obstétrique', 'Gynécologie', 'Salle d\'accouchement', 'Suites de couches', 'Grossesses à risque'],
        ];

        // Patients fictifs marocains
        $prenomsM = ['Mohammed', 'Ahmed', 'Youssef', 'Hamza', 'Karim', 'Omar', 'Ali', 'Hassan', 'Saïd', 'Khalid', 'Abdelaziz', 'Mehdi', 'Yassine', 'Adil', 'Rachid', 'Othmane', 'Anas', 'Soufiane', 'Walid', 'Ismail'];
        $prenomsF = ['Fatima', 'Aïcha', 'Khadija', 'Salma', 'Sara', 'Imane', 'Houda', 'Latifa', 'Naima', 'Najat', 'Souad', 'Zineb', 'Meriem', 'Amina', 'Hanane', 'Samira', 'Karima', 'Loubna', 'Asma', 'Hajar'];
        $noms = ['Alaoui', 'Benjelloun', 'Bennani', 'Chraibi', 'El Fassi', 'El Idrissi', 'Fettah', 'Filali', 'Kettani', 'Lahlou', 'Mansouri', 'Mernissi', 'Tazi', 'Berrada', 'Belmahi', 'Ouali', 'Saidi', 'Bouazza', 'Cherkaoui', 'El Amrani', 'Hassani', 'Naciri', 'Sefrioui', 'Tahiri', 'Ziani', 'Bouanani', 'Sebti', 'El Otmani', 'Ait Hammou', 'Ben Abdellah'];
        $villes = ['Rabat', 'Casablanca', 'Fès', 'Marrakech', 'Tanger', 'Agadir', 'Oujda', 'Salé', 'Meknès', 'Kénitra', 'Tétouan', 'Settat', 'Mohammedia', 'Témara'];
        $professions = ['Enseignant', 'Étudiant', 'Commerçant', 'Fonctionnaire', 'Médecin', 'Infirmier', 'Ingénieur', 'Sans emploi', 'Retraité', 'Agriculteur', 'Chauffeur', 'Femme au foyer', 'Avocat', 'Artisan', 'Ouvrier'];
        $relations = ['Père', 'Mère', 'Conjoint', 'Conjointe', 'Frère', 'Sœur', 'Fils', 'Fille', 'Oncle', 'Cousin'];
        $groupes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $motifsAdmission = [
            'Douleur thoracique', 'Hypertension artérielle non contrôlée', 'Crise d\'asthme', 'Pneumopathie aiguë',
            'Infection urinaire', 'Diabète déséquilibré', 'Accident vasculaire cérébral', 'Traumatisme suite à accident',
            'Préparation chirurgicale', 'Douleurs abdominales', 'Insuffisance rénale', 'Grossesse à risque',
            'Accouchement programmé', 'Fièvre persistante', 'Anémie sévère', 'Bilan oncologique',
        ];

        $totalEtab = 0; $totalServices = 0; $totalLits = 0; $totalPatients = 0; $totalAdmissions = 0;

        foreach ($etablissements as $i => $infoEtab) {
            // 1. Établissement
            $etab = Etablissement::updateOrCreate(
                ['code' => $infoEtab['code']],
                [
                    'nom' => $infoEtab['nom'],
                    'adresse' => "Avenue principale, {$infoEtab['ville']}, Maroc",
                    'telephone' => $infoEtab['tel'],
                    'email' => $infoEtab['email'],
                    'description' => "Centre hospitalier universitaire de {$infoEtab['ville']}",
                    'is_active' => true,
                ]
            );
            $totalEtab++;

            // 2. Type de services selon vocation
            $typeServices = match ($etab->code) {
                'HER' => 'pediatrique',
                'SOUISSI' => 'maternite',
                default => 'general',
            };
            $poolServices = $servicesParType[$typeServices];

            // 3. Bâtiments
            $batiments = $batimentsParEtab[$i] ?? ['Bâtiment Principal'];
            foreach ($batiments as $bIdx => $batNom) {
                $batiment = Batiment::updateOrCreate(
                    ['etablissement_id' => $etab->id, 'nom' => $batNom],
                    ['code' => $etab->code . '-B' . ($bIdx + 1), 'is_active' => true]
                );

                // 4. Étages (0 = RDC, 1, 2)
                $nbEtages = rand(2, 3);
                for ($n = 0; $n < $nbEtages; $n++) {
                    $etage = Etage::updateOrCreate(
                        ['batiment_id' => $batiment->id, 'numero' => $n],
                        ['nom' => $n === 0 ? 'Rez-de-chaussée' : "Étage {$n}", 'is_active' => true]
                    );

                    // 5. Services dans cet étage (2 par étage en moyenne)
                    $servicesEtage = collect($poolServices)->shuffle()->take(rand(2, 3));
                    foreach ($servicesEtage as $idxSvc => $svcNom) {
                        $codeBase = strtoupper(Str::substr(Str::ascii($svcNom), 0, 5));
                        $codeBase = preg_replace('/[^A-Z0-9]/', '', $codeBase);
                        $service = Service::updateOrCreate(
                            ['etage_id' => $etage->id, 'nom' => $svcNom],
                            [
                                'code' => $codeBase . $etab->id . $etage->id,
                                'responsable' => 'Dr. ' . $noms[array_rand($noms)],
                                'is_active' => true,
                            ]
                        );
                        $totalServices++;

                        // 6. Chambres (3-5) + Lits (1-3 par chambre)
                        $nbChambres = rand(3, 5);
                        for ($c = 1; $c <= $nbChambres; $c++) {
                            $numChambre = sprintf('%d%02d', $etage->numero, $c);
                            $type = ['simple', 'double', 'triple'][array_rand(['simple', 'double', 'triple'])];
                            $chambre = Chambre::updateOrCreate(
                                ['service_id' => $service->id, 'numero' => $numChambre],
                                ['type' => $type, 'is_active' => true]
                            );

                            $nbLits = match ($type) {
                                'simple' => 1,
                                'double' => 2,
                                'triple' => 3,
                                default => 1,
                            };
                            for ($l = 1; $l <= $nbLits; $l++) {
                                Lit::updateOrCreate(
                                    ['chambre_id' => $chambre->id, 'numero' => (string) $l],
                                    ['statut' => 'libre']
                                );
                                $totalLits++;
                            }
                        }
                    }
                }
            }

            // 7. Secrétaire de test pour cet établissement
            $premierService = Service::whereHas('etage.batiment', fn($q) => $q->where('etablissement_id', $etab->id))->first();
            User::updateOrCreate(
                ['email' => 'sec.' . strtolower($etab->code) . '@hopital.ma'],
                [
                    'name' => 'Secrétaire ' . $etab->code,
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_SECRETAIRE,
                    'etablissement_id' => $etab->id,
                    'service_id' => $premierService?->id,
                    'telephone' => '0600' . str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            // 8. Surveillant général de l'établissement
            User::updateOrCreate(
                ['email' => 'surveillant.' . strtolower($etab->code) . '@hopital.ma'],
                [
                    'name' => 'Surveillant ' . $etab->code,
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_SURVEILLANT,
                    'etablissement_id' => $etab->id,
                    'service_id' => null,
                    'telephone' => '0601' . str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            // 9. Patients pour cet établissement (8-12)
            $nbPatients = rand(8, 12);
            $patientsCrees = [];
            for ($p = 0; $p < $nbPatients; $p++) {
                $sexe = rand(0, 1) ? 'M' : 'F';
                $prenom = $sexe === 'M' ? $prenomsM[array_rand($prenomsM)] : $prenomsF[array_rand($prenomsF)];
                $nom = $noms[array_rand($noms)];

                $age = $typeServices === 'pediatrique' ? rand(0, 15) : ($typeServices === 'maternite' ? rand(18, 45) : rand(1, 90));
                $dateNaissance = now()->subYears($age)->subDays(rand(0, 364))->format('Y-m-d');

                $patient = Patient::updateOrCreate(
                    ['num_dossier' => 'P' . date('Y') . sprintf('%05d', ($totalPatients + 1))],
                    [
                        'etablissement_id' => $etab->id,
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'date_naissance' => $dateNaissance,
                        'sexe' => $sexe,
                        'telephone' => '0' . rand(6, 7) . str_pad((string) rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                        'adresse' => 'Quartier ' . chr(rand(65, 90)) . ', ' . $villes[array_rand($villes)],
                        'cni' => strtoupper(chr(rand(65, 90))) . rand(100000, 999999),
                        'profession' => $professions[array_rand($professions)],
                        'contact_urgence_nom' => $noms[array_rand($noms)] . ' ' . ($sexe === 'M' ? $prenomsF[array_rand($prenomsF)] : $prenomsM[array_rand($prenomsM)]),
                        'contact_urgence_telephone' => '0' . rand(6, 7) . str_pad((string) rand(0, 99999999), 8, '0', STR_PAD_LEFT),
                        'contact_urgence_relation' => $relations[array_rand($relations)],
                        'groupe_sanguin' => $groupes[array_rand($groupes)],
                        'observations' => null,
                    ]
                );
                $patientsCrees[] = $patient;
                $totalPatients++;
            }

            // 10. Admettre ~50% des patients dans des lits libres de cet établissement
            $litsLibres = Lit::with('chambre.service')
                ->where('statut', 'libre')
                ->whereHas('chambre.service.etage.batiment', fn($q) => $q->where('etablissement_id', $etab->id))
                ->inRandomOrder()
                ->take((int) (count($patientsCrees) * 0.6))
                ->get();

            foreach ($litsLibres as $idx => $lit) {
                if (!isset($patientsCrees[$idx])) break;
                $patient = $patientsCrees[$idx];

                // Vérifier que le patient n'a pas déjà une admission en cours
                if ($patient->admissionEnCours()->exists()) continue;

                $dateEntree = now()->subDays(rand(0, 15))->subHours(rand(0, 23));

                Admission::create([
                    'patient_id' => $patient->id,
                    'lit_id' => $lit->id,
                    'service_id' => $lit->chambre->service_id,
                    'created_by' => $premierService ? User::where('etablissement_id', $etab->id)->where('role', 'secretaire')->first()?->id : null,
                    'date_entree' => $dateEntree,
                    'motif' => $motifsAdmission[array_rand($motifsAdmission)],
                    'observations' => 'Patient pris en charge en ' . $lit->chambre->service->nom,
                    'statut' => 'en_cours',
                ]);

                $lit->update(['statut' => 'occupe']);
                $totalAdmissions++;
            }
        }

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  Données fictives générées avec succès');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->line("  → Établissements : {$totalEtab}");
        $this->command->line("  → Services       : {$totalServices}");
        $this->command->line("  → Lits           : {$totalLits}");
        $this->command->line("  → Patients       : {$totalPatients}");
        $this->command->line("  → Admissions en cours : {$totalAdmissions}");
        $this->command->info('───────────────────────────────────────────────────────');
        $this->command->info('  Comptes secrétaires par établissement :');
        foreach ($etablissements as $e) {
            $email = 'sec.' . strtolower($e['code']) . '@hopital.ma';
            $this->command->line("    {$email} / password  ({$e['nom']} - {$e['ville']})");
        }
        $this->command->info('───────────────────────────────────────────────────────');
        $this->command->info('  Comptes surveillants généraux :');
        foreach ($etablissements as $e) {
            $email = 'surveillant.' . strtolower($e['code']) . '@hopital.ma';
            $this->command->line("    {$email} / password");
        }
        $this->command->info('═══════════════════════════════════════════════════════');
    }
}
