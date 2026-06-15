<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Espace Administrateur' }} – {{ config('app.name', 'Gestion Admissions') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-slate-50">
    <div x-data="{
            sidebarOpen: false,
            paramOpen: {{ request()->is('admin/parametrage*') ? 'true' : 'false' }}
        }" class="min-h-screen flex">

        {{-- Sidebar mobile overlay --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-200 transform transition-transform duration-200 ease-in-out flex flex-col">

            <div class="flex items-center justify-between h-16 px-6 border-b border-slate-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-white font-bold text-lg">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Gestion Admissions</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                @php
                    $linkBase = 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition';
                    $linkInactive = 'text-slate-300 hover:bg-slate-800 hover:text-white';
                    $linkActive = 'bg-indigo-600 text-white shadow';
                @endphp

                <a href="{{ route('admin.dashboard') }}"
                   class="{{ $linkBase }} {{ request()->routeIs('admin.dashboard') ? $linkActive : $linkInactive }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Tableau de bord
                </a>

                <a href="{{ route('admin.users') }}"
                   class="{{ $linkBase }} {{ request()->routeIs('admin.users') ? $linkActive : $linkInactive }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Gestion des utilisateurs
                </a>

                <a href="{{ route('admin.admissions') }}"
                   class="{{ $linkBase }} {{ request()->routeIs('admin.admissions') ? $linkActive : $linkInactive }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Toutes les admissions
                </a>

                {{-- Paramétrage avec sous-menu --}}
                <div>
                    <button type="button" @click="paramOpen = !paramOpen"
                            class="w-full {{ $linkBase }} {{ request()->is('admin/parametrage*') ? 'bg-slate-800 text-white' : $linkInactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="flex-1 text-left">Paramétrage</span>
                        <svg class="w-4 h-4 transition-transform" :class="paramOpen && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="paramOpen" x-cloak class="mt-1 ml-3 pl-3 border-l border-slate-700 space-y-1">
                        @php
                            $subLinks = [
                                ['route' => 'admin.etablissements', 'label' => 'Établissements', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                                ['route' => 'admin.batiments', 'label' => 'Bâtiments', 'icon' => 'M3 21h18M3 7v14m18-14v14M5 21V7l8-4v18M19 21V11l-6-4'],
                                ['route' => 'admin.etages', 'label' => 'Étages', 'icon' => 'M4 6h16M4 12h16M4 18h16'],
                                ['route' => 'admin.services', 'label' => 'Services', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                                ['route' => 'admin.chambres', 'label' => 'Chambres', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                ['route' => 'admin.lits', 'label' => 'Lits', 'icon' => 'M3 12h18M3 12v6a1 1 0 001 1h16a1 1 0 001-1v-6M3 12V8a2 2 0 012-2h3a2 2 0 012 2v4M14 12V9a1 1 0 011-1h4a1 1 0 011 1v3'],
                            ];
                        @endphp
                        @foreach($subLinks as $link)
                            <a href="{{ route($link['route']) }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition
                                      {{ request()->routeIs($link['route']) ? 'bg-indigo-500/20 text-indigo-300' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                                </svg>
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </nav>

            <div class="px-4 py-4 border-t border-slate-800">
                <div class="flex items-center gap-3 mb-3 px-2">
                    <div class="w-9 h-9 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ auth()->user()->role_libelle }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-red-600/20 hover:text-red-300 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Se déconnecter
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-slate-200 h-16 flex items-center px-6 lg:px-8 sticky top-0 z-20">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-600 hover:text-slate-900 mr-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-semibold text-slate-900">
                    {{ $title ?? 'Espace Administrateur' }}
                </h1>
            </header>

            <main class="flex-1 p-6 lg:p-8">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                         class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
