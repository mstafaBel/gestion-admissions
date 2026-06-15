<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Admissions de l'établissement</h2>
            <p class="text-slate-500 mt-1">
                Vue consolidée — lecture seule —
                <span class="font-semibold text-cyan-700">{{ auth()->user()->etablissement?->nom ?? '—' }}</span>
            </p>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-cyan-50 border border-cyan-200 text-cyan-700 text-xs font-medium rounded-full">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Consultation
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="md:col-span-2 relative">
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher par patient ou n° dossier..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
        </div>
        <select wire:model.live="filterServiceId" class="px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            <option value="">Tous les services</option>
            @foreach($services as $s)
                <option value="{{ $s->id }}">{{ $s->nom }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterStatut" class="px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm">
            <option value="">Tous statuts</option>
            @foreach($statuts as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
        </select>
    </div>

    @php
        $statutBadge = [
            'en_cours' => 'bg-red-50 text-red-700',
            'terminee' => 'bg-green-50 text-green-700',
            'transferee' => 'bg-amber-50 text-amber-700',
        ];
        $statutDot = [
            'en_cours' => 'bg-red-500',
            'terminee' => 'bg-green-500',
            'transferee' => 'bg-amber-500',
        ];
    @endphp

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Patient</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Service</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Lit</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Motif</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Entrée</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Sortie</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Statut</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Détail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($admissions as $a)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-slate-900">{{ $a->patient?->nom }} {{ $a->patient?->prenom }}</p>
                                <p class="text-xs text-slate-500 font-mono">{{ $a->patient?->num_dossier }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                <p class="font-medium text-slate-700">{{ $a->service?->nom }}</p>
                                <p class="text-xs text-slate-400">{{ $a->service?->etage?->batiment?->nom }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                @if($a->lit)
                                    <span class="font-mono">Ch. {{ $a->lit->chambre?->numero }} · Lit {{ $a->lit->numero }}</span>
                                @else — @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                <div class="max-w-[200px] truncate" title="{{ $a->motif }}">{{ $a->motif ?: '—' }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $a->date_entree?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $a->date_sortie?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $statutBadge[$a->statut] ?? 'bg-slate-100 text-slate-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statutDot[$a->statut] ?? 'bg-slate-400' }}"></span>
                                    {{ $a->statut_libelle }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="voirDetail({{ $a->id }})"
                                        class="px-2.5 py-1 text-xs text-cyan-700 bg-cyan-50 hover:bg-cyan-100 rounded-md font-medium">
                                    Détails
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-12 text-center text-slate-500 text-sm">Aucune admission trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($admissions->hasPages())<div class="px-4 py-3 border-t border-slate-200">{{ $admissions->links() }}</div>@endif
    </div>

    {{-- Modal Détail (lecture seule) --}}
    @if($showDetail && $admissionDetail)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="fermerDetail"></div>
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-semibold text-slate-900">Détail de l'admission</h3>
                        <button wire:click="fermerDetail" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="md:col-span-2 p-4 bg-cyan-50 border border-cyan-200 rounded-lg">
                            <p class="text-xs uppercase tracking-wider text-cyan-700 font-semibold mb-1">Patient</p>
                            <p class="text-lg font-bold text-slate-900">{{ $admissionDetail->patient?->nom }} {{ $admissionDetail->patient?->prenom }}</p>
                            <p class="text-xs text-slate-600 mt-1 font-mono">N° {{ $admissionDetail->patient?->num_dossier }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500 uppercase font-semibold">Service</p>
                            <p class="text-slate-900 mt-1">{{ $admissionDetail->service?->nom }}</p>
                            <p class="text-xs text-slate-500">{{ $admissionDetail->service?->etage?->batiment?->nom }} · Étage {{ $admissionDetail->service?->etage?->numero }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-semibold">Lit</p>
                            <p class="text-slate-900 mt-1">
                                @if($admissionDetail->lit)
                                    Chambre {{ $admissionDetail->lit->chambre?->numero }} · Lit {{ $admissionDetail->lit->numero }}
                                @else — @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-semibold">Date d'entrée</p>
                            <p class="text-slate-900 mt-1">{{ $admissionDetail->date_entree?->format('d/m/Y H:i') ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-semibold">Date de sortie</p>
                            <p class="text-slate-900 mt-1">{{ $admissionDetail->date_sortie?->format('d/m/Y H:i') ?? '—' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs text-slate-500 uppercase font-semibold">Motif d'admission</p>
                            <p class="text-slate-900 mt-1">{{ $admissionDetail->motif ?: '—' }}</p>
                        </div>
                        @if($admissionDetail->motif_sortie)
                            <div class="md:col-span-2">
                                <p class="text-xs text-slate-500 uppercase font-semibold">Motif de sortie</p>
                                <p class="text-slate-900 mt-1">{{ $admissionDetail->motif_sortie }}</p>
                            </div>
                        @endif
                        @if($admissionDetail->observations)
                            <div class="md:col-span-2">
                                <p class="text-xs text-slate-500 uppercase font-semibold">Observations</p>
                                <p class="text-slate-700 mt-1 whitespace-pre-line">{{ $admissionDetail->observations }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-semibold">Créée par</p>
                            <p class="text-slate-900 mt-1">{{ $admissionDetail->createur?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-semibold">Clôturée par</p>
                            <p class="text-slate-900 mt-1">{{ $admissionDetail->cloturePar?->name ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-5 mt-4 border-t border-slate-100">
                        <button wire:click="fermerDetail" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
