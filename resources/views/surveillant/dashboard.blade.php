<x-surveillant-layout title="Tableau de bord">
    @php
        $user = auth()->user();
        $etablissement = $user->etablissement;
        $etablissementId = $user->etablissement_id;

        $stats = [
            'admissions_en_cours' => 0,
            'admissions_aujourdhui' => 0,
            'sorties_aujourdhui' => 0,
            'transferts' => 0,
            'lits_libres' => 0,
            'lits_occupes' => 0,
            'lits_total' => 0,
            'services' => 0,
            'patients' => 0,
        ];

        $admissionsRecentes = collect();
        $repartitionServices = collect();

        if ($etablissementId) {
            $admissionsBase = \App\Models\Admission::whereHas('service.etage.batiment',
                fn($q) => $q->where('etablissement_id', $etablissementId));

            $stats['admissions_en_cours'] = (clone $admissionsBase)->where('statut', 'en_cours')->count();
            $stats['admissions_aujourdhui'] = (clone $admissionsBase)->whereDate('date_entree', today())->count();
            $stats['sorties_aujourdhui'] = (clone $admissionsBase)->where('statut', 'terminee')->whereDate('date_sortie', today())->count();
            $stats['transferts'] = (clone $admissionsBase)->where('statut', 'transferee')->whereDate('date_sortie', today())->count();

            $litsEtab = \App\Models\Lit::whereHas('chambre.service.etage.batiment',
                fn($q) => $q->where('etablissement_id', $etablissementId))->get();
            $stats['lits_total'] = $litsEtab->count();
            $stats['lits_libres'] = $litsEtab->where('statut', 'libre')->count();
            $stats['lits_occupes'] = $litsEtab->where('statut', 'occupe')->count();

            $stats['services'] = \App\Models\Service::whereHas('etage.batiment',
                fn($q) => $q->where('etablissement_id', $etablissementId))->count();
            $stats['patients'] = \App\Models\Patient::where('etablissement_id', $etablissementId)->count();

            $admissionsRecentes = \App\Models\Admission::with(['patient', 'service', 'lit.chambre'])
                ->whereHas('service.etage.batiment', fn($q) => $q->where('etablissement_id', $etablissementId))
                ->latest('date_entree')
                ->limit(6)
                ->get();

            $repartitionServices = \App\Models\Service::with(['etage.batiment'])
                ->whereHas('etage.batiment', fn($q) => $q->where('etablissement_id', $etablissementId))
                ->withCount(['admissions as en_cours' => fn($q) => $q->where('statut', 'en_cours')])
                ->orderByDesc('en_cours')
                ->limit(6)
                ->get();
        }

        $taux = $stats['lits_total'] > 0 ? round(($stats['lits_occupes'] / $stats['lits_total']) * 100) : 0;

        $statutBadge = [
            'en_cours' => 'bg-red-50 text-red-700 ring-red-200',
            'terminee' => 'bg-green-50 text-green-700 ring-green-200',
            'transferee' => 'bg-amber-50 text-amber-700 ring-amber-200',
        ];
    @endphp

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Bonjour, {{ $user->name }}</h2>
        <p class="text-slate-500 mt-1">
            @if($etablissement)
                Supervision de
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-cyan-50 text-cyan-700 ring-1 ring-cyan-200">
                    {{ $etablissement->nom }}
                </span>
            @else
                Aucun établissement affecté à votre compte.
            @endif
        </p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Patients hospitalisés</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $stats['admissions_en_cours'] }}</p>
                </div>
                <div class="p-2.5 rounded-lg bg-red-50 text-red-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Admissions aujourd'hui</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $stats['admissions_aujourdhui'] }}</p>
                </div>
                <div class="p-2.5 rounded-lg bg-cyan-50 text-cyan-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Sorties aujourd'hui</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $stats['sorties_aujourdhui'] }}</p>
                </div>
                <div class="p-2.5 rounded-lg bg-green-50 text-green-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Lits libres / total</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">
                        {{ $stats['lits_libres'] }} <span class="text-base text-slate-400">/ {{ $stats['lits_total'] }}</span>
                    </p>
                </div>
                <div class="p-2.5 rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 12v6a1 1 0 001 1h16a1 1 0 001-1v-6M3 12V8a2 2 0 012-2h3a2 2 0 012 2v4"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Dernières admissions</h3>
                <a href="{{ route('surveillant.admissions') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-medium">Voir tout →</a>
            </div>

            @if($admissionsRecentes->isEmpty())
                <div class="text-center py-8 text-slate-500 text-sm">Aucune admission récente.</div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($admissionsRecentes as $a)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $a->patient?->nom }} {{ $a->patient?->prenom }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $a->service?->nom }} ·
                                    @if($a->lit) Ch. {{ $a->lit->chambre?->numero }} · Lit {{ $a->lit->numero }} @else lit non assigné @endif
                                    · {{ $a->date_entree?->format('d/m H:i') }}
                                </p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium ring-1 {{ $statutBadge[$a->statut] ?? 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                                {{ $a->statut_libelle }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Taux d'occupation</h3>
            <div class="mb-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-slate-600">Établissement</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $taux }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 h-3 rounded-full" style="width: {{ $taux }}%"></div>
                </div>
            </div>

            <h4 class="text-sm font-semibold text-slate-700 mb-3">Top services (en cours)</h4>
            @if($repartitionServices->isEmpty())
                <p class="text-sm text-slate-500">Aucun service configuré.</p>
            @else
                <div class="space-y-2">
                    @foreach($repartitionServices as $svc)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-700 truncate">{{ $svc->nom }}</span>
                            <span class="font-semibold text-cyan-700">{{ $svc->en_cours }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-surveillant-layout>
