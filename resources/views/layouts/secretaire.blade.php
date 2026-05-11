<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Espace Secrétaire' }} – {{ config('app.name', 'Gestion Admissions') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"></div>

        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-gradient-to-b from-teal-900 to-slate-900 text-slate-200 transform transition-transform duration-200 ease-in-out flex flex-col">

            <div class="flex items-center justify-between h-16 px-6 border-b border-teal-800/50">
                <a href="{{ route('secretaire.dashboard') }}" class="flex items-center gap-2 text-white font-bold text-lg">
                    <svg class="w-7 h-7 text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Secrétariat</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if(auth()->user()->service)
                <div class="px-6 py-3 border-b border-teal-800/50 bg-teal-900/30">
                    <p class="text-xs text-teal-300 uppercase tracking-wider font-semibold">Service</p>
                    <p class="text-sm font-medium text-white mt-1">{{ auth()->user()->service->nom }}</p>
                    <p class="text-xs text-teal-200/70">
                        Étage {{ auth()->user()->service->etage?->numero }} ·
                        {{ auth()->user()->service->etage?->batiment?->nom }}
                    </p>
                </div>
            @else
                <div class="px-6 py-3 border-b border-teal-800/50 bg-amber-900/40">
                    <p class="text-xs text-amber-200">⚠ Aucun service affecté</p>
                    <p class="text-xs text-amber-100/70 mt-1">Contactez l'administrateur.</p>
                </div>
            @endif

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                @php
                    $linkBase = 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition';
                    $linkInactive = 'text-slate-300 hover:bg-teal-800/40 hover:text-white';
                    $linkActive = 'bg-teal-600 text-white shadow';
                @endphp

                <a href="{{ route('secretaire.dashboard') }}"
                   class="{{ $linkBase }} {{ request()->routeIs('secretaire.dashboard') ? $linkActive : $linkInactive }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Tableau de bord
                </a>

                <a href="{{ route('secretaire.patients') }}"
                   class="{{ $linkBase }} {{ request()->routeIs('secretaire.patients') ? $linkActive : $linkInactive }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Patients
                </a>

                <a href="{{ route('secretaire.admissions') }}"
                   class="{{ $linkBase }} {{ request()->routeIs('secretaire.admissions') ? $linkActive : $linkInactive }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Admissions
                </a>
            </nav>

            <div class="px-4 py-4 border-t border-teal-800/50">
                <div class="flex items-center gap-3 mb-3 px-2">
                    <div class="w-9 h-9 rounded-full bg-teal-500 flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-teal-200/70 truncate">Secrétaire</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-red-600/30 hover:text-red-200 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Se déconnecter
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-slate-200 h-16 flex items-center px-6 lg:px-8 sticky top-0 z-20">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-600 hover:text-slate-900 mr-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-semibold text-slate-900">{{ $title ?? 'Espace Secrétaire' }}</h1>
            </header>

            <main class="flex-1 p-6 lg:p-8">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                         class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(!auth()->user()->service)
                    <div class="mb-4 px-4 py-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg">
                        Votre compte n'est rattaché à aucun service. Veuillez contacter l'administrateur avant de pouvoir gérer des admissions.
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
