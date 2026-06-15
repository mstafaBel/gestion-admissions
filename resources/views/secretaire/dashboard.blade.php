<x-secretaire-layout title="Tableau de bord">
    @php
        $user = auth()->user();
        $service = $user->service;
        $stats = [
            'admissions_en_cours' => 0,
            'admissions_aujourdhui' => 0,
            'sorties_aujourdhui' => 0,
            'lits_libres' => 0,
            'lits_occupes' => 0,
            'lits_total' => 0,
        ];

        $litsLibres = collect();

        if ($service) {
            $stats['admissions_en_cours'] = \App\Models\Admission::where('service_id', $service->id)
                ->where('statut', 'en_cours')->count();
            $stats['admissions_aujourdhui'] = \App\Models\Admission::where('service_id', $service->id)
                ->whereDate('date_entree', today())->count();
            $stats['sorties_aujourdhui'] = \App\Models\Admission::where('service_id', $service->id)
                ->where('statut', 'terminee')
                ->whereDate('date_sortie', today())->count();

            $litsService = \App\Models\Lit::whereHas('chambre', fn($q) => $q->where('service_id', $service->id))->get();
            $stats['lits_total'] = $litsService->count();
            $stats['lits_libres'] = $litsService->where('statut', 'libre')->count();
            $stats['lits_occupes'] = $litsService->where('statut', 'occupe')->count();

            $litsLibres = \App\Models\Lit::with('chambre')
                ->where('statut', 'libre')
                ->whereHas('chambre', fn($q) => $q->where('service_id', $service->id))
                ->orderBy('chambre_id')->orderBy('numero')
                ->get();
        }

        $taux = $stats['lits_total'] > 0 ? round(($stats['lits_occupes'] / $stats['lits_total']) * 100) : 0;
    @endphp

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Bonjour, {{ $user->name }}</h2>
        <p class="text-slate-500 mt-1">
            @if($user->etablissement)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-teal-50 text-teal-700 ring-1 ring-teal-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                    {{ $user->etablissement->nom }}
                </span>
            @endif
            <span class="ml-1">Service <span class="font-semibold text-teal-700">{{ $service?->nom ?? 'non affecté' }}</span></span>
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Patients hospitalisés</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $stats['admissions_en_cours'] }}</p>
                </div>
                <div class="p-2.5 rounded-lg bg-red-50 text-red-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Admissions aujourd'hui</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $stats['admissions_aujourdhui'] }}</p>
                </div>
                <div class="p-2.5 rounded-lg bg-teal-50 text-teal-600">
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
                <h3 class="text-lg font-semibold text-slate-900">Lits libres de votre service</h3>
                <a href="{{ route('secretaire.admissions') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">Admissions →</a>
            </div>

            <div class="mb-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-slate-600">Taux d'occupation</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $taux }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-500 to-teal-600 h-3 rounded-full" style="width: {{ $taux }}%"></div>
                </div>
            </div>

            @if($litsLibres->isEmpty())
                <div class="text-center py-8 text-slate-500">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 12v6a1 1 0 001 1h16a1 1 0 001-1v-6M3 12V8a2 2 0 012-2h3a2 2 0 012 2v4"/></svg>
                    <p class="text-sm">Aucun lit libre actuellement.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                    @foreach($litsLibres as $lit)
                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-xs text-green-700 font-medium">Chambre {{ $lit->chambre?->numero }}</p>
                            <p class="text-sm font-bold text-green-900">Lit {{ $lit->numero }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Actions rapides</h3>
            <div class="space-y-2">
                <a href="{{ route('secretaire.admissions') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 transition border border-slate-100">
                    <div class="p-2 rounded-lg bg-red-50 text-red-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                    </div>
                    <div class="text-sm">
                        <p class="font-medium text-slate-900">Nouvelle admission</p>
                        <p class="text-slate-500 text-xs">Admettre un patient</p>
                    </div>
                </a>
                <a href="{{ route('secretaire.patients') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 transition border border-slate-100">
                    <div class="p-2 rounded-lg bg-teal-50 text-teal-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="text-sm">
                        <p class="font-medium text-slate-900">Nouveau patient</p>
                        <p class="text-slate-500 text-xs">Créer un dossier</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-secretaire-layout>
