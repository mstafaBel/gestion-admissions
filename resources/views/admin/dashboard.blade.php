<x-admin-layout title="Tableau de bord">
    @php
        $stats = [
            'utilisateurs' => \App\Models\User::count(),
            'secretaires' => \App\Models\User::where('role', 'secretaire')->count(),
            'etablissements' => \App\Models\Etablissement::count(),
            'services' => \App\Models\Service::count(),
            'lits_total' => \App\Models\Lit::count(),
            'lits_libres' => \App\Models\Lit::where('statut', 'libre')->count(),
            'lits_occupes' => \App\Models\Lit::where('statut', 'occupe')->count(),
            'lits_maintenance' => \App\Models\Lit::where('statut', 'maintenance')->count(),
        ];

        $cards = [
            ['label' => 'Utilisateurs', 'value' => $stats['utilisateurs'], 'classes' => 'bg-indigo-50 text-indigo-600', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => 'Secrétaires', 'value' => $stats['secretaires'], 'classes' => 'bg-teal-50 text-teal-600', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Établissements', 'value' => $stats['etablissements'], 'classes' => 'bg-purple-50 text-purple-600', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5'],
            ['label' => 'Services', 'value' => $stats['services'], 'classes' => 'bg-amber-50 text-amber-600', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ];

        $taux = $stats['lits_total'] > 0 ? round(($stats['lits_occupes'] / $stats['lits_total']) * 100) : 0;
    @endphp

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Bonjour, {{ auth()->user()->name }}</h2>
        <p class="text-slate-500 mt-1">Vue d'ensemble de votre établissement.</p>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach($cards as $card)
            <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">{{ $card['label'] }}</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $card['value'] }}</p>
                    </div>
                    <div class="p-2.5 rounded-lg {{ $card['classes'] }}">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Lits overview --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">État des lits</h3>

            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-slate-600">Taux d'occupation</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $taux }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-3 rounded-full transition-all" style="width: {{ $taux }}%"></div>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="text-center p-3 rounded-lg bg-slate-50">
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['lits_total'] }}</p>
                    <p class="text-xs text-slate-500 mt-1">Total</p>
                </div>
                <div class="text-center p-3 rounded-lg bg-green-50">
                    <p class="text-2xl font-bold text-green-700">{{ $stats['lits_libres'] }}</p>
                    <p class="text-xs text-green-600 mt-1">Libres</p>
                </div>
                <div class="text-center p-3 rounded-lg bg-red-50">
                    <p class="text-2xl font-bold text-red-700">{{ $stats['lits_occupes'] }}</p>
                    <p class="text-xs text-red-600 mt-1">Occupés</p>
                </div>
                <div class="text-center p-3 rounded-lg bg-amber-50">
                    <p class="text-2xl font-bold text-amber-700">{{ $stats['lits_maintenance'] }}</p>
                    <p class="text-xs text-amber-600 mt-1">Maintenance</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Actions rapides</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 transition border border-slate-100">
                    <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <div class="text-sm">
                        <p class="font-medium text-slate-900">Ajouter un utilisateur</p>
                        <p class="text-slate-500 text-xs">Surveillant ou secrétaire</p>
                    </div>
                </a>
                <a href="{{ route('admin.etablissements') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 transition border border-slate-100">
                    <div class="p-2 rounded-lg bg-purple-50 text-purple-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/></svg>
                    </div>
                    <div class="text-sm">
                        <p class="font-medium text-slate-900">Configurer l'établissement</p>
                        <p class="text-slate-500 text-xs">Bâtiments, étages, services</p>
                    </div>
                </a>
                <a href="{{ route('admin.lits') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 transition border border-slate-100">
                    <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 12v6a1 1 0 001 1h16a1 1 0 001-1v-6M3 12V8a2 2 0 012-2h3a2 2 0 012 2v4"/></svg>
                    </div>
                    <div class="text-sm">
                        <p class="font-medium text-slate-900">Gérer les lits</p>
                        <p class="text-slate-500 text-xs">Disponibilité par service</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
