<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Admissions</h2>
            <p class="text-slate-500 mt-1">
                Service : <span class="font-semibold text-teal-700">{{ $monService?->nom ?? '—' }}</span>
            </p>
        </div>
        <button wire:click="openAdmettre" @if(!auth()->user()->service_id) disabled @endif
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-teal-600 hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouvelle admission
        </button>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-4 mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="md:col-span-2 relative">
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher par patient..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
        </div>
        <select wire:model.live="filterStatut" class="px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
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
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Lit</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Motif</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Entrée</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Sortie</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Statut</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
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
                                @if($a->lit)
                                    <span class="font-mono">Ch. {{ $a->lit->chambre?->numero }} · Lit {{ $a->lit->numero }}</span>
                                @else — @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                <div class="max-w-[180px] truncate" title="{{ $a->motif }}">{{ $a->motif ?: '—' }}</div>
                                @if($a->motif_sortie)
                                    <div class="text-xs text-green-700 mt-0.5"><span class="font-medium">Sortie:</span> {{ $a->motif_sortie }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $a->date_entree?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $a->date_sortie?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $statutBadge[$a->statut] ?? 'bg-slate-100 text-slate-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statutDot[$a->statut] ?? 'bg-slate-400' }}"></span>
                                    {{ $a->statut_libelle }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @if($a->estEnCours())
                                        <button wire:click="openTransfert({{ $a->id }})" class="px-2.5 py-1 text-xs text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-md font-medium" title="Transférer">
                                            Transférer
                                        </button>
                                        <button wire:click="openSortie({{ $a->id }})" class="px-2.5 py-1 text-xs text-green-700 bg-green-50 hover:bg-green-100 rounded-md font-medium" title="Enregistrer la sortie">
                                            Sortie
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-12 text-center text-slate-500 text-sm">Aucune admission.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($admissions->hasPages())<div class="px-4 py-3 border-t border-slate-200">{{ $admissions->links() }}</div>@endif
    </div>

    {{-- Modal Admission --}}
    @if($showAdmissionModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showAdmissionModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-semibold text-slate-900">Nouvelle admission</h3>
                        <button wire:click="$set('showAdmissionModal', false)" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <form wire:submit.prevent="admettre" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2 px-3 py-2 bg-teal-50 border border-teal-200 rounded-lg text-sm text-teal-800">
                            Service d'accueil : <strong>{{ $monService?->nom ?? '—' }}</strong>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Patient <span class="text-red-500">*</span></label>
                            <select wire:model="patient_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                                <option value="">— Choisir un patient —</option>
                                @foreach($patientsLibres as $p)
                                    <option value="{{ $p->id }}">{{ $p->nom }} {{ $p->prenom }} ({{ $p->num_dossier }})</option>
                                @endforeach
                            </select>
                            @error('patient_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            @if($patientsLibres->isEmpty())
                                <p class="text-amber-600 text-xs mt-1">Aucun patient disponible. Créez-en un dans Patients.</p>
                            @endif
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Lit disponible <span class="text-red-500">*</span></label>
                            <select wire:model="lit_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                                <option value="">— Choisir un lit —</option>
                                @foreach($this->litsDisponibles as $l)
                                    <option value="{{ $l->id }}">Ch. {{ $l->chambre?->numero }} · Lit {{ $l->numero }}</option>
                                @endforeach
                            </select>
                            @error('lit_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            @if($this->litsDisponibles->isEmpty())
                                <p class="text-amber-600 text-xs mt-1">Aucun lit libre dans votre service.</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Date et heure d'entrée <span class="text-red-500">*</span></label>
                            <input type="datetime-local" wire:model="date_entree" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                            @error('date_entree') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Motif d'admission <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="motif" placeholder="Symptômes, diagnostic présumé..." class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                            @error('motif') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Observations</label>
                            <textarea wire:model="observations" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm"></textarea>
                        </div>
                        <div class="md:col-span-2 flex justify-end gap-2 pt-4 border-t border-slate-100 mt-2">
                            <button type="button" wire:click="$set('showAdmissionModal', false)" class="px-4 py-2 border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium">Annuler</button>
                            <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm">Admettre le patient</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Sortie --}}
    @if($showSortieModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showSortieModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-xl p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="p-2.5 rounded-full bg-green-100 text-green-700">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Enregistrer la sortie</h3>
                            <p class="text-sm text-slate-500">Le lit sera libéré automatiquement.</p>
                        </div>
                    </div>
                    <form wire:submit.prevent="enregistrerSortie" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Date et heure de sortie <span class="text-red-500">*</span></label>
                            <input type="datetime-local" wire:model="date_sortie" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                            @error('date_sortie') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Motif de la sortie <span class="text-red-500">*</span></label>
                            <select wire:model="motif_sortie" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                                <option value="">— Choisir un motif —</option>
                                <option value="Guérison">Guérison</option>
                                <option value="Amélioration">Amélioration</option>
                                <option value="Décharge sur demande">Décharge sur demande du patient</option>
                                <option value="Évasion">Évasion</option>
                                <option value="Décès">Décès</option>
                                <option value="Référé / Transfert externe">Référé / Transfert externe</option>
                                <option value="Autre">Autre</option>
                            </select>
                            @error('motif_sortie') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Observations de sortie</label>
                            <textarea wire:model="sortie_observations" rows="3" placeholder="Diagnostic final, recommandations, traitement de sortie..." class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"></textarea>
                        </div>
                        <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                            <button type="button" wire:click="$set('showSortieModal', false)" class="px-4 py-2 border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium">Annuler</button>
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm">Confirmer la sortie</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Transfert --}}
    @if($showTransfertModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showTransfertModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-xl p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="p-2.5 rounded-full bg-amber-100 text-amber-700">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Transférer le patient</h3>
                            <p class="text-sm text-slate-500">Une nouvelle admission sera créée sur le lit cible.</p>
                        </div>
                    </div>
                    <form wire:submit.prevent="transferer" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Service de destination <span class="text-red-500">*</span></label>
                            <select wire:model.live="transfert_service_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="">— Choisir —</option>
                                @foreach($services as $s)<option value="{{ $s->id }}">{{ $s->nom }}</option>@endforeach
                            </select>
                            @error('transfert_service_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Lit cible <span class="text-red-500">*</span></label>
                            <select wire:model="transfert_lit_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" @if(!$transfert_service_id) disabled @endif>
                                <option value="">— Choisir —</option>
                                @foreach($this->litsTransfert as $l)
                                    <option value="{{ $l->id }}">Ch. {{ $l->chambre?->numero }} · Lit {{ $l->numero }}</option>
                                @endforeach
                            </select>
                            @error('transfert_lit_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Motif du transfert <span class="text-red-500">*</span></label>
                            <textarea wire:model="transfert_motif" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm"></textarea>
                            @error('transfert_motif') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2 flex justify-end gap-2 pt-4 border-t border-slate-100 mt-2">
                            <button type="button" wire:click="$set('showTransfertModal', false)" class="px-4 py-2 border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium">Annuler</button>
                            <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg shadow-sm">Confirmer le transfert</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
