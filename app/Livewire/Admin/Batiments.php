<?php

namespace App\Livewire\Admin;

use App\Models\Batiment;
use App\Models\Etablissement;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Batiments extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterEtablissement = null;

    public bool $showModal = false;
    public bool $showDeleteModal = false;

    public ?int $editingId = null;
    public ?int $etablissement_id = null;
    public string $nom = '';
    public string $code = '';
    public string $description = '';
    public bool $is_active = true;

    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'etablissement_id' => 'required|exists:etablissements,id',
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterEtablissement(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->etablissement_id = $this->filterEtablissement;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $b = Batiment::findOrFail($id);
        $this->editingId = $b->id;
        $this->etablissement_id = $b->etablissement_id;
        $this->nom = $b->nom;
        $this->code = $b->code ?? '';
        $this->description = $b->description ?? '';
        $this->is_active = (bool) $b->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Batiment::where('id', $this->editingId)->update($data);
            session()->flash('success', 'Bâtiment mis à jour.');
        } else {
            Batiment::create($data);
            session()->flash('success', 'Bâtiment créé.');
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
            Batiment::where('id', $this->deletingId)->delete();
            session()->flash('success', 'Bâtiment supprimé.');
        }
        $this->showDeleteModal = false;
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->etablissement_id = null;
        $this->nom = '';
        $this->code = '';
        $this->description = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    #[Layout('layouts.admin')]
    #[Title('Bâtiments')]
    public function render()
    {
        $batiments = Batiment::query()
            ->with('etablissement')
            ->withCount('etages')
            ->when($this->search, fn($q) => $q->where('nom', 'like', "%{$this->search}%"))
            ->when($this->filterEtablissement, fn($q) => $q->where('etablissement_id', $this->filterEtablissement))
            ->orderBy('nom')
            ->paginate(10);

        return view('livewire.admin.batiments', [
            'batiments' => $batiments,
            'etablissements' => Etablissement::orderBy('nom')->get(),
        ]);
    }
}
