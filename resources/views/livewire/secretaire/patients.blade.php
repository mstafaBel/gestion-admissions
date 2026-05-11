<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Patients</h2>
            <p class="text-slate-500 mt-1">Dossiers patients de l'établissement.</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouveau patient
        </button>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-4 mb-4">
        <div class="relative">
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="N° dossier, nom, prénom, téléphone, CNI..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">N° dossier</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Patient</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Sexe / Âge</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Téléphone</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Hospitalisation</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($patients as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded">{{ $p->num_dossier }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $p->sexe === 'F' ? 'from-pink-400 to-rose-500' : 'from-sky-400 to-indigo-500' }} flex items-center justify-center text-white text-sm font-semibold">
                                        {{ strtoupper(substr($p->prenom ?: $p->nom, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">{{ $p->nom }} {{ $p->prenom }}</p>
                                        <p class="text-xs text-slate-500">{{ $p->cni ?: 'CNI : —' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                {{ $p->sexe ? ($p->sexe === 'M' ? 'M' : 'F') : '—' }}
                                @if($p->age !== null)
                                    <span class="text-xs text-slate-400 block">{{ $p->age }} ans</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $p->telephone ?: '—' }}</td>
                            <td class="px-4 py-3">
                                @if($p->admissionEnCours)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Hospitalisé · {{ $p->admissionEnCours->service?->nom }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Externe
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="openEdit({{ $p->id }})" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg" title="Modifier">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $p->id }})" class="p-1.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-slate-500 text-sm">Aucun patient enregistré.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($patients->hasPages())<div class="px-4 py-3 border-t border-slate-200">{{ $patients->links() }}</div>@endif
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-semibold text-slate-900">{{ $editingId ? 'Modifier le patient' : 'Nouveau patient' }}</h3>
                        <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <form wire:submit.prevent="save" class="space-y-5">
                        <div>
                            <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Identité</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">N° dossier <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="num_dossier" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-mono">
                                    @error('num_dossier') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Nom <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="nom" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="prenom" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Date de naissance</label>
                                    <input type="date" wire:model="date_naissance" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    @error('date_naissance') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Sexe</label>
                                    <select wire:model="sexe" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                        <option value="">—</option>
                                        @foreach($sexes as $k => $l)<option value="{{ $k }}">{{ $l }}</option>@endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">CNI</label>
                                    <input type="text" wire:model="cni" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Coordonnées</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Téléphone</label>
                                    <input type="text" wire:model="telephone" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Profession</label>
                                    <input type="text" wire:model="profession" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Groupe sanguin</label>
                                    <select wire:model="groupe_sanguin" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                        <option value="">—</option>
                                        @foreach($groupesSanguins as $g)<option value="{{ $g }}">{{ $g }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Adresse</label>
                                    <input type="text" wire:model="adresse" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Personne à contacter en urgence</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Nom</label>
                                    <input type="text" wire:model="contact_urgence_nom" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Téléphone</label>
                                    <input type="text" wire:model="contact_urgence_telephone" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Lien</label>
                                    <input type="text" wire:model="contact_urgence_relation" placeholder="Conjoint, parent..." class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Observations / Antécédents</label>
                            <textarea wire:model="observations" rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium">Annuler</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm">{{ $editingId ? 'Mettre à jour' : 'Enregistrer le patient' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 rounded-full bg-red-100 text-red-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
                        <div><h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3><p class="text-sm text-slate-500 mt-1">Le dossier sera archivé (soft-delete).</p></div>
                    </div>
                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium">Annuler</button>
                        <button wire:click="delete" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
