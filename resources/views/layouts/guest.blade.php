<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Connexion' }} – {{ config('app.name', 'Gestion Admissions') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen grid lg:grid-cols-2">

        {{-- Panneau gauche : branding / illustration --}}
        <div class="relative hidden lg:flex flex-col justify-between p-12 bg-gradient-to-br from-indigo-700 via-sky-700 to-teal-700 text-white overflow-hidden">

            <div class="absolute inset-0 opacity-10">
                <svg class="absolute -top-24 -right-24 w-[480px] h-[480px]" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#fff" d="M44.1,-58.6C56.1,-49.2,63.6,-33.7,67.6,-17.5C71.6,-1.3,72.1,15.6,65.3,28.7C58.5,41.8,44.4,51.2,29.3,58.3C14.1,65.4,-2.2,70.3,-17.1,67.1C-32,63.9,-45.4,52.7,-55.5,39C-65.5,25.4,-72.2,9.3,-71.2,-6.3C-70.2,-21.9,-61.6,-37,-49.4,-46.7C-37.3,-56.4,-21.6,-60.6,-4.8,-54.9C12,-49.2,32.1,-68,44.1,-58.6Z" transform="translate(100 100)"/>
                </svg>
                <svg class="absolute -bottom-24 -left-24 w-[420px] h-[420px]" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#fff" d="M38.6,-66.2C49.3,-58.3,56.4,-45.4,63.6,-32C70.8,-18.6,78.1,-4.5,76.3,8.4C74.5,21.3,63.7,33,52.6,43.2C41.5,53.5,30.1,62.3,16.6,68.4C3.1,74.5,-12.5,77.9,-26.5,73.7C-40.5,69.6,-52.8,57.9,-60.6,44.2C-68.5,30.6,-71.8,15,-71.5,-0.6C-71.1,-16.1,-67,-32.1,-58.1,-43.1C-49.2,-54.1,-35.4,-60,-22.1,-67.3C-8.8,-74.6,4,-83.3,16.8,-81C29.6,-78.6,42.4,-65.3,38.6,-66.2Z" transform="translate(100 100)"/>
                </svg>
            </div>

            <div class="relative z-10 flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center ring-1 ring-white/30">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-bold tracking-tight">Gestion Admissions</p>
                    <p class="text-xs text-white/70">Plateforme hospitalière</p>
                </div>
            </div>

            <div class="relative z-10 max-w-lg">
                <h2 class="text-4xl font-bold leading-tight">
                    Pilotez vos admissions<br>en toute simplicité.
                </h2>
                <p class="mt-4 text-white/80 text-lg leading-relaxed">
                    Suivi des patients, gestion des lits, transferts entre services et supervision multi-établissements — depuis une interface claire et sécurisée.
                </p>

                <div class="mt-10 grid grid-cols-3 gap-4">
                    <div class="p-4 bg-white/10 backdrop-blur rounded-xl ring-1 ring-white/20">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <p class="text-xs font-medium">Patients</p>
                    </div>
                    <div class="p-4 bg-white/10 backdrop-blur rounded-xl ring-1 ring-white/20">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 12v6a1 1 0 001 1h16a1 1 0 001-1v-6M3 12V8a2 2 0 012-2h3a2 2 0 012 2v4"/></svg>
                        </div>
                        <p class="text-xs font-medium">Lits</p>
                    </div>
                    <div class="p-4 bg-white/10 backdrop-blur rounded-xl ring-1 ring-white/20">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <p class="text-xs font-medium">Statistiques</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10 text-xs text-white/60">
                © {{ date('Y') }} {{ config('app.name', 'Gestion Admissions') }} — Tous droits réservés.
            </div>
        </div>

        {{-- Panneau droit : formulaire --}}
        <div class="flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-16">

            {{-- Logo mobile --}}
            <div class="flex items-center justify-center gap-3 mb-8 lg:hidden">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-600 to-teal-600 flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <p class="text-xl font-bold text-slate-900">Gestion Admissions</p>
            </div>

            <div class="w-full max-w-md mx-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>
