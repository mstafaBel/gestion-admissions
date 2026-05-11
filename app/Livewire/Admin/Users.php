<?php

namespace App\Livewire\Admin;

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

    public bool $showModal = false;
    public bool $showDeleteModal = false;

    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = User::ROLE_SECRETAIRE;
    public ?int $service_id = null;
    public string $telephone = '';
    public bool $is_active = true;

    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . ($this->userId ?? 'NULL'),
            'password' => $this->userId ? 'nullable|min:6|confirmed' : 'required|min:6|confirmed',
            'role' => 'required|in:' . User::ROLE_ADMIN . ',' . User::ROLE_SURVEILLANT . ',' . User::ROLE_SECRETAIRE,
            'service_id' => 'nullable|exists:services,id',
            'telephone' => 'nullable|string|max:30',
            'is_active' => 'boolean',
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

        if ($this->role !== User::ROLE_SECRETAIRE) {
            $data['service_id'] = null;
        }

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $payload = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
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
            ->with('service')
            ->when($this->search, fn($q) => $q->where(function ($w) {
                $w->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->filterRole, fn($q) => $q->where('role', $this->filterRole))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.users', [
            'users' => $users,
            'services' => Service::with('etage.batiment')->orderBy('nom')->get(),
            'roles' => User::ROLES,
        ]);
    }
}
