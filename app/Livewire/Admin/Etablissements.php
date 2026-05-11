<?php

namespace App\Livewire\Admin;

use App\Models\Etablissement;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Etablissements extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;

    public ?int $editingId = null;
    public string $nom = '';
    public string $code = '';
    public string $adresse = '';
    public string $telephone = '';
    public string $email = '';
    public string $description = '';
    public bool $is_active = true;

    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:etablissements,code,' . ($this->editingId ?? 'NULL'),
            'adresse' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch(): void
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
        $e = Etablissement::findOrFail($id);
        $this->editingId = $e->id;
        $this->nom = $e->nom;
        $this->code = $e->code;
        $this->adresse = $e->adresse ?? '';
        $this->telephone = $e->telephone ?? '';
        $this->email = $e->email ?? '';
        $this->description = $e->description ?? '';
        $this->is_active = (bool) $e->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            Etablissement::where('id', $this->editingId)->update($data);
            session()->flash('success', 'Établissement mis à jour.');
        } else {
            Etablissement::create($data);
            session()->flash('success', 'Établissement créé.');
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
        if ($this->deletingId) {
            Etablissement::where('id', $this->deletingId)->delete();
            session()->flash('success', 'Établissement supprimé.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->nom = '';
        $this->code = '';
        $this->adresse = '';
        $this->telephone = '';
        $this->email = '';
        $this->description = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    #[Layout('layouts.admin')]
    #[Title('Établissements')]
    public function render()
    {
        $etablissements = Etablissement::query()
            ->withCount('batiments')
            ->when($this->search, fn($q) => $q->where(function ($w) {
                $w->where('nom', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%");
            }))
            ->orderBy('nom')
            ->paginate(10);

        return view('livewire.admin.etablissements', compact('etablissements'));
    }
}
