<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Gestion des utilisateurs</h2>
            <p class="text-slate-500 mt-1">Administrateurs, surveillants généraux et secrétaires.</p>
        </div>
        <button wire:click="openCreate"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Ajouter un utilisateur
        </button>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-xl border border-slate-200 p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2 relative">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher par nom ou email..."
                       class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
            <select wire:model.live="filterRole"
                    class="px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                <option value="">Tous les profils</option>
                @foreach($roles as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterEtablissement"
                    class="px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                <option value="">Tous les établissements</option>
                @foreach($etablissements as $etab)
                    <option value="{{ $etab->id }}">{{ $etab->nom }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Profil</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Établissement</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Service</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Statut</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-sm font-semibold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $colors = [
                                        'admin' => 'bg-red-50 text-red-700 ring-red-200',
                                        'surveillant_general' => 'bg-purple-50 text-purple-700 ring-purple-200',
                                        'secretaire' => 'bg-blue-50 text-blue-700 ring-blue-200',
                                    ];
                                    $c = $colors[$user->role] ?? 'bg-slate-50 text-slate-700 ring-slate-200';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 {{ $c }}">
                                    {{ $user->role_libelle }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                {{ $user->etablissement?->nom ?? ($user->isAdmin() ? 'Tous' : '—') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                {{ $user->service?->nom ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="toggleActive({{ $user->id }})"
                                        @if($user->id === auth()->id()) disabled @endif
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium transition
                                               {{ $user->is_active ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}
                                               @if($user->id === auth()->id()) opacity-60 cursor-not-allowed @endif">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-slate-400' }}"></span>
                                    {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="openEdit({{ $user->id }})"
                                            class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Modifier">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <button wire:click="confirmDelete({{ $user->id }})"
                                                class="p-1.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-500" wire:key="empty-row">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857"/>
                                </svg>
                                <p class="text-sm">Aucun utilisateur trouvé.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-slate-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Création / Édition --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="user-modal">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>

                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-semibold text-slate-900">
                            {{ $userId ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur' }}
                        </h3>
                        <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Profil <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($roles as $key => $label)
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model.live="role" value="{{ $key }}" class="sr-only peer">
                                        <div class="px-3 py-2.5 border-2 border-slate-200 rounded-lg text-center text-sm peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 hover:border-slate-300 transition">
                                            {{ $label }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nom complet <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Mot de passe @if(!$userId)<span class="text-red-500">*</span>@endif
                            </label>
                            <input type="password" wire:model="password" placeholder="{{ $userId ? 'Laisser vide pour ne pas changer' : '' }}"
                                   class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Confirmer le mot de passe</label>
                            <input type="password" wire:model="password_confirmation"
                                   class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        </div>

                        @if($role !== 'admin')
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Établissement <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="etablissement_id"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <option value="">— Choisir un établissement —</option>
                                    @foreach($etablissements as $etab)
                                        <option value="{{ $etab->id }}">{{ $etab->nom }} @if($etab->code)({{ $etab->code }})@endif</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-slate-500 mt-1">L'utilisateur ne verra que les données de cet établissement.</p>
                                @error('etablissement_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        @if($role === 'secretaire')
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Service affecté <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="service_id"
                                        @disabled(!$etablissement_id)
                                        class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm disabled:bg-slate-100 disabled:cursor-not-allowed">
                                    <option value="">
                                        @if(!$etablissement_id) — Choisir d'abord un établissement — @else — Choisir un service — @endif
                                    </option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">
                                            {{ $service->nom }}
                                            @if($service->etage) (Étage {{ $service->etage->numero }}, {{ $service->etage->batiment->nom ?? '' }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Téléphone</label>
                            <input type="text" wire:model="telephone" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        </div>

                        <div class="flex items-end">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-slate-700">Compte actif</span>
                            </label>
                        </div>

                        <div class="md:col-span-2 flex justify-end gap-2 pt-4 border-t border-slate-100 mt-2">
                            <button type="button" wire:click="$set('showModal', false)"
                                    class="px-4 py-2 border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium transition">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                                {{ $userId ? 'Mettre à jour' : 'Créer l\'utilisateur' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Suppression --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="delete-modal">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)"></div>
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3>
                            <p class="text-sm text-slate-500 mt-1">Cette action est irréversible.</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                        <button wire:click="$set('showDeleteModal', false)"
                                class="px-4 py-2 border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium transition">
                            Annuler
                        </button>
                        <button wire:click="delete"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
