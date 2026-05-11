<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Chambres</h2>
            <p class="text-slate-500 mt-1">Chambres des services.</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouvelle chambre
        </button>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-4 mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="md:col-span-2 relative">
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher par numéro..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
        </div>
        <select wire:model.live="filterService" class="px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            <option value="">Tous les services</option>
            @foreach($services as $s)
                <option value="{{ $s->id }}">{{ $s->nom }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">N°</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Service</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Localisation</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Lits</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Statut</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($chambres as $c)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono font-semibold text-slate-900">{{ $c->numero }}</td>
                        <td class="px-4 py-3"><span class="inline-flex items-center px-2 py-0.5 bg-amber-50 text-amber-700 rounded text-xs font-medium capitalize">{{ $c->type }}</span></td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $c->service?->nom }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">
                            Ét. {{ $c->service?->etage?->numero }} • {{ $c->service?->etage?->batiment?->nom }}
                        </td>
                        <td class="px-4 py-3"><span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-xs font-medium">{{ $c->lits_count }}</span></td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $c->is_active ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $c->is_active ? 'bg-green-500' : 'bg-slate-400' }}"></span>
                                {{ $c->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="openEdit({{ $c->id }})" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                <button wire:click="confirmDelete({{ $c->id }})" class="p-1.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-slate-500 text-sm">Aucune chambre.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($chambres->hasPages())<div class="px-4 py-3 border-t border-slate-200">{{ $chambres->links() }}</div>@endif
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-xl p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-semibold text-slate-900">{{ $editingId ? 'Modifier' : 'Nouvelle chambre' }}</h3>
                        <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Service <span class="text-red-500">*</span></label>
                            <select wire:model="service_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="">— Choisir —</option>
                                @foreach($services as $s)
                                    <option value="{{ $s->id }}">{{ $s->nom }} (Ét. {{ $s->etage?->numero }} – {{ $s->etage?->batiment?->nom }})</option>
                                @endforeach
                            </select>
                            @error('service_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Numéro <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="numero" placeholder="101, A-12..." class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            @error('numero') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Type <span class="text-red-500">*</span></label>
                            <select wire:model="type" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm capitalize">
                                @foreach($types as $t)
                                    <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                            <textarea wire:model="description" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-slate-700">Chambre active</span>
                            </label>
                        </div>
                        <div class="md:col-span-2 flex justify-end gap-2 pt-4 border-t border-slate-100 mt-2">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium">Annuler</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm">{{ $editingId ? 'Mettre à jour' : 'Créer' }}</button>
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
                        <div><h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3><p class="text-sm text-slate-500 mt-1">Les lits liés seront supprimés.</p></div>
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
