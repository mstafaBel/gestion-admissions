<?php

namespace App\Livewire\Admin;

use App\Models\Etablissement;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRole = '';
    public ?int $filterEtablissement = null;

    public bool $showModal = false;
    public bool $showDeleteModal = false;

    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = User::ROLE_SECRETAIRE;
    public ?int $etablissement_id = null;
    public ?int $service_id = null;
    public string $telephone = '';
    public bool $is_active = true;

    public ?int $deletingId = null;

    protected function rules(): array
    {
        $needsEtablissement = in_array($this->role, [User::ROLE_SURVEILLANT, User::ROLE_SECRETAIRE], true);
        $needsService = $this->role === User::ROLE_SECRETAIRE;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . ($this->userId ?? 'NULL'),
            'password' => $this->userId ? 'nullable|min:6|confirmed' : 'required|min:6|confirmed',
            'role' => 'required|in:' . User::ROLE_ADMIN . ',' . User::ROLE_SURVEILLANT . ',' . User::ROLE_SECRETAIRE,
            'etablissement_id' => ($needsEtablissement ? 'required' : 'nullable') . '|exists:etablissements,id',
            'service_id' => ($needsService ? 'required' : 'nullable') . '|exists:services,id',
            'telephone' => 'nullable|string|max:30',
            'is_active' => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'etablissement_id.required' => "L'établissement est obligatoire pour ce profil.",
            'service_id.required' => 'Le service est obligatoire pour un secrétaire.',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterRole(): void
    {
        $this->resetPage();
    }

    public function updatingFilterEtablissement(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        if ($this->role === User::ROLE_ADMIN) {
            $this->etablissement_id = null;
            $this->service_id = null;
        }
        if ($this->role !== User::ROLE_SECRETAIRE) {
            $this->service_id = null;
        }
    }

    public function updatedEtablissementId(): void
    {
        // Réinitialise le service si l'établissement change
        $this->service_id = null;
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->etablissement_id = $user->etablissement_id;
        $this->service_id = $user->service_id;
        $this->telephone = $user->telephone ?? '';
        $this->is_active = (bool) $user->is_active;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        // Verrouillage : l'admin n'a pas d'établissement, le surveillant n'a pas de service
        if ($this->role === User::ROLE_ADMIN) {
            $data['etablissement_id'] = null;
            $data['service_id'] = null;
        }
        if ($this->role !== User::ROLE_SECRETAIRE) {
            $data['service_id'] = null;
        }

        // Cohérence : le service choisi doit appartenir à l'établissement choisi
        if (!empty($data['service_id']) && !empty($data['etablissement_id'])) {
            $service = Service::with('etage.batiment')->find($data['service_id']);
            if (!$service || $service->etage?->batiment?->etablissement_id !== (int) $data['etablissement_id']) {
                $this->addError('service_id', 'Le service choisi n\'appartient pas à cet établissement.');
                return;
            }
        }

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $payload = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'etablissement_id' => $data['etablissement_id'] ?? null,
                'service_id' => $data['service_id'] ?? null,
                'telephone' => $data['telephone'] ?? null,
                'is_active' => $data['is_active'],
            ];
            if (!empty($data['password'])) {
                $payload['password'] = Hash::make($data['password']);
            }
            $user->update($payload);
            session()->flash('success', 'Utilisateur mis à jour avec succès.');
        } else {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'etablissement_id' => $data['etablissement_id'] ?? null,
                'service_id' => $data['service_id'] ?? null,
                'telephone' => $data['telephone'] ?? null,
                'is_active' => $data['is_active'],
            ]);
            session()->flash('success', 'Utilisateur créé avec succès.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId && $this->deletingId !== auth()->id()) {
            User::where('id', $this->deletingId)->delete();
            session()->flash('success', 'Utilisateur supprimé.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function toggleActive(int $id): void
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return;
        }
        $user->update(['is_active' => !$user->is_active]);
    }

    public function resetForm(): void
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = User::ROLE_SECRETAIRE;
        $this->etablissement_id = null;
        $this->service_id = null;
        $this->telephone = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    #[Layout('layouts.admin')]
    #[Title('Gestion des utilisateurs')]
    public function render()
    {
        $users = User::query()
            ->with(['service.etage.batiment', 'etablissement'])
            ->when($this->search, fn($q) => $q->where(function ($w) {
                $w->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->filterRole, fn($q) => $q->where('role', $this->filterRole))
            ->when($this->filterEtablissement, fn($q) => $q->where('etablissement_id', $this->filterEtablissement))
            ->latest()
            ->paginate(10);

        // Services filtrés selon l'établissement choisi dans le formulaire
        $servicesForForm = collect();
        if ($this->etablissement_id) {
            $servicesForForm = Service::with('etage.batiment')
                ->whereHas('etage.batiment', fn($q) => $q->where('etablissement_id', $this->etablissement_id))
                ->orderBy('nom')
                ->get();
        }

        return view('livewire.admin.users', [
            'users' => $users,
            'etablissements' => Etablissement::orderBy('nom')->get(),
            'services' => $servicesForForm,
            'roles' => User::ROLES,
        ]);
    }
}
