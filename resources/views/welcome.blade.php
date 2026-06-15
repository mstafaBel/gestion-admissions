<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestion Admissions') }} — Plateforme hospitalière</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">

    {{-- Header --}}
    <header class="absolute top-0 inset-x-0 z-20">
        <nav class="max-w-7xl mx-auto px-6 lg:px-8 py-5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 text-white">
                <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center ring-1 ring-white/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="leading-tight">
                    <p class="font-bold">Gestion Admissions</p>
                    <p class="text-xs text-white/70">Plateforme hospitalière</p>
                </div>
            </a>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white text-indigo-700 hover:bg-slate-50 text-sm font-semibold rounded-lg shadow-md transition">
                        Mon espace
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden sm:inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white/90 hover:text-white transition">
                        Se connecter
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white text-indigo-700 hover:bg-slate-50 text-sm font-semibold rounded-lg shadow-md transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Connexion
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-indigo-700 via-cyan-700 to-teal-700 text-white">
        {{-- Blobs décoratifs --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="absolute -top-24 -right-24 w-[600px] h-[600px]" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path fill="#fff" d="M44.1,-58.6C56.1,-49.2,63.6,-33.7,67.6,-17.5C71.6,-1.3,72.1,15.6,65.3,28.7C58.5,41.8,44.4,51.2,29.3,58.3C14.1,65.4,-2.2,70.3,-17.1,67.1C-32,63.9,-45.4,52.7,-55.5,39C-65.5,25.4,-72.2,9.3,-71.2,-6.3C-70.2,-21.9,-61.6,-37,-49.4,-46.7C-37.3,-56.4,-21.6,-60.6,-4.8,-54.9C12,-49.2,32.1,-68,44.1,-58.6Z" transform="translate(100 100)"/>
            </svg>
            <svg class="absolute -bottom-32 -left-32 w-[520px] h-[520px]" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path fill="#fff" d="M38.6,-66.2C49.3,-58.3,56.4,-45.4,63.6,-32C70.8,-18.6,78.1,-4.5,76.3,8.4C74.5,21.3,63.7,33,52.6,43.2C41.5,53.5,30.1,62.3,16.6,68.4C3.1,74.5,-12.5,77.9,-26.5,73.7C-40.5,69.6,-52.8,57.9,-60.6,44.2C-68.5,30.6,-71.8,15,-71.5,-0.6C-71.1,-16.1,-67,-32.1,-58.1,-43.1C-49.2,-54.1,-35.4,-60,-22.1,-67.3C-8.8,-74.6,4,-83.3,16.8,-81C29.6,-78.6,42.4,-65.3,38.6,-66.2Z" transform="translate(100 100)"/>
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 pt-32 pb-24 lg:pt-40 lg:pb-32">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-white/15 backdrop-blur text-white/90 text-xs font-medium rounded-full ring-1 ring-white/20 mb-6">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        Solution en ligne · multi-établissements
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight">
                        Pilotez les admissions<br>
                        de vos hôpitaux<br>
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-200 to-teal-100">en temps réel.</span>
                    </h1>
                    <p class="mt-6 text-lg lg:text-xl text-white/85 max-w-xl leading-relaxed">
                        Suivi des patients, gestion des lits, transferts inter-services et supervision multi-CHU — depuis une seule interface, claire et sécurisée.
                    </p>

                    <div class="mt-10 flex flex-wrap gap-4">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-white text-indigo-700 hover:bg-slate-100 text-base font-semibold rounded-xl shadow-lg hover:shadow-xl transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            Accéder à mon espace
                        </a>
                        <a href="#fonctionnalites"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur text-white hover:bg-white/20 text-base font-medium rounded-xl ring-1 ring-white/30 transition">
                            Découvrir les fonctionnalités
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </a>
                    </div>

                    <div class="mt-12 grid grid-cols-3 gap-6 max-w-md">
                        <div>
                            <p class="text-3xl font-bold">3</p>
                            <p class="text-xs text-white/70 mt-1 uppercase tracking-wider">Rôles métier</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold">8+</p>
                            <p class="text-xs text-white/70 mt-1 uppercase tracking-wider">CHU pris en charge</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold">24/7</p>
                            <p class="text-xs text-white/70 mt-1 uppercase tracking-wider">Disponibilité</p>
                        </div>
                    </div>
                </div>

                {{-- Aperçu visuel : mockup dashboard --}}
                <div class="hidden lg:block relative">
                    <div class="absolute -inset-4 bg-white/10 backdrop-blur rounded-3xl ring-1 ring-white/20"></div>
                    <div class="relative bg-white text-slate-900 rounded-2xl shadow-2xl p-6 transform rotate-1 hover:rotate-0 transition duration-500">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Tableau de bord</p>
                                <p class="text-lg font-bold text-slate-900">CHU Ibn Sina — Rabat</p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                En ligne
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-5">
                            <div class="p-3 rounded-xl bg-red-50 border border-red-100">
                                <p class="text-xs text-red-600 font-medium">Hospitalisés</p>
                                <p class="text-2xl font-bold text-red-700 mt-1">47</p>
                            </div>
                            <div class="p-3 rounded-xl bg-cyan-50 border border-cyan-100">
                                <p class="text-xs text-cyan-600 font-medium">Admissions/jour</p>
                                <p class="text-2xl font-bold text-cyan-700 mt-1">12</p>
                            </div>
                            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                                <p class="text-xs text-emerald-600 font-medium">Sorties</p>
                                <p class="text-2xl font-bold text-emerald-700 mt-1">8</p>
                            </div>
                            <div class="p-3 rounded-xl bg-indigo-50 border border-indigo-100">
                                <p class="text-xs text-indigo-600 font-medium">Lits libres</p>
                                <p class="text-2xl font-bold text-indigo-700 mt-1">139</p>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-slate-600 font-medium">Taux d'occupation</span>
                                <span class="text-xs font-bold text-slate-900">25%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-indigo-500 to-cyan-500 h-2.5 rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Mini cartes flottantes --}}
                    <div class="absolute -top-6 -right-6 bg-white rounded-xl shadow-xl p-3 transform -rotate-6 hidden xl:block">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-900">Patient admis</p>
                                <p class="text-xs text-slate-500">Cardiologie · Lit 12</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Fonctionnalités --}}
    <section id="fonctionnalites" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full uppercase tracking-wider mb-4">Fonctionnalités</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900">Tout ce qu'il vous faut pour gérer un hôpital</h2>
                <p class="mt-4 text-lg text-slate-600">Une plateforme pensée pour les équipes hospitalières : du secrétariat à la direction médicale.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $features = [
                        ['title' => 'Gestion des patients', 'desc' => 'Dossiers patients complets : identité, contact, antécédents, groupe sanguin et personne à prévenir.', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'color' => 'indigo'],
                        ['title' => 'Suivi des admissions', 'desc' => 'Admettre, transférer ou sortir un patient en quelques clics. Historique complet par service.', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2', 'color' => 'teal'],
                        ['title' => 'Cartographie des lits', 'desc' => 'État en temps réel de chaque lit : libre, occupé ou en maintenance, par bâtiment et étage.', 'icon' => 'M3 12h18M3 12v6a1 1 0 001 1h16a1 1 0 001-1v-6M3 12V8a2 2 0 012-2h3a2 2 0 012 2v4', 'color' => 'emerald'],
                        ['title' => 'Multi-établissements', 'desc' => 'Hiérarchie complète : Établissement → Bâtiment → Étage → Service → Chambre → Lit.', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1', 'color' => 'purple'],
                        ['title' => 'Supervision globale', 'desc' => 'Tableaux de bord pour les surveillants généraux : vue consolidée par établissement.', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'color' => 'cyan'],
                        ['title' => 'Rôles et droits', 'desc' => 'Admin, surveillant général et secrétaire — chaque rôle voit uniquement ce qui le concerne.', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'color' => 'amber'],
                    ];
                    $colorMap = [
                        'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'ring' => 'ring-indigo-100'],
                        'teal' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-600', 'ring' => 'ring-teal-100'],
                        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'ring' => 'ring-emerald-100'],
                        'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'ring' => 'ring-purple-100'],
                        'cyan' => ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-600', 'ring' => 'ring-cyan-100'],
                        'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'ring' => 'ring-amber-100'],
                    ];
                @endphp
                @foreach($features as $f)
                    @php $c = $colorMap[$f['color']]; @endphp
                    <div class="group p-6 rounded-2xl border border-slate-200 hover:border-slate-300 hover:shadow-lg transition bg-white">
                        <div class="w-12 h-12 rounded-xl {{ $c['bg'] }} {{ $c['text'] }} ring-4 {{ $c['ring'] }} flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $f['title'] }}</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Rôles --}}
    <section class="py-20 lg:py-28 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <span class="inline-block px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-full uppercase tracking-wider mb-4">Rôles utilisateurs</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900">Une interface adaptée à chaque métier</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="p-8 rounded-2xl bg-white border border-slate-200 hover:shadow-xl transition">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center mb-5 shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Administrateur</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Configure les établissements, gère les utilisateurs et supervise toutes les admissions, tous CHU confondus.</p>
                </div>

                <div class="p-8 rounded-2xl bg-white border border-slate-200 hover:shadow-xl transition">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-700 flex items-center justify-center mb-5 shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Surveillant général</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Consulte toutes les admissions de son établissement, suit l'activité de chaque service en temps réel.</p>
                </div>

                <div class="p-8 rounded-2xl bg-white border border-slate-200 hover:shadow-xl transition">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center mb-5 shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Secrétaire</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Gère les patients, enregistre les admissions, les transferts et les sorties pour son service.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA final --}}
    <section class="py-20 lg:py-24 bg-white">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="relative overflow-hidden bg-gradient-to-br from-indigo-700 via-cyan-700 to-teal-700 rounded-3xl shadow-2xl p-10 lg:p-16 text-white text-center">
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <svg class="absolute -top-12 -left-12 w-72 h-72" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#fff" d="M44.1,-58.6C56.1,-49.2,63.6,-33.7,67.6,-17.5C71.6,-1.3,72.1,15.6,65.3,28.7C58.5,41.8,44.4,51.2,29.3,58.3C14.1,65.4,-2.2,70.3,-17.1,67.1C-32,63.9,-45.4,52.7,-55.5,39C-65.5,25.4,-72.2,9.3,-71.2,-6.3C-70.2,-21.9,-61.6,-37,-49.4,-46.7C-37.3,-56.4,-21.6,-60.6,-4.8,-54.9C12,-49.2,32.1,-68,44.1,-58.6Z" transform="translate(100 100)"/>
                    </svg>
                </div>
                <h2 class="relative text-3xl lg:text-4xl font-bold mb-4">Prêt à reprendre la main sur vos admissions ?</h2>
                <p class="relative text-white/85 text-lg mb-8 max-w-2xl mx-auto">Connectez-vous à votre espace pour commencer.</p>
                <a href="{{ route('login') }}"
                   class="relative inline-flex items-center gap-2 px-7 py-3.5 bg-white text-indigo-700 hover:bg-slate-100 text-base font-semibold rounded-xl shadow-lg transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Se connecter maintenant
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-slate-600 text-sm">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600 to-teal-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <span class="font-semibold text-slate-900">{{ config('app.name', 'Gestion Admissions') }}</span>
            </div>
            <p class="text-xs text-slate-500">© {{ date('Y') }} Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
