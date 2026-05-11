<?php

namespace App\Livewire\Admin;

use App\Models\Chambre;
use App\Models\Lit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Lits extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterChambre = null;
    public string $filterStatut = '';

    public bool $showModal = false;
    public bool $showDeleteModal = false;

    public ?int $editingId = null;
    public ?int $chambre_id = null;
    public string $numero = '';
    public string $statut = 'libre';
    public string $description = '';

    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'chambre_id' => 'required|exists:chambres,id',
            'numero' => 'required|string|max:50',
            'statut' => 'required|in:libre,occupe,maintenance,reserve',
            'description' => 'nullable|string',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterChambre(): void { $this->resetPage(); }
    public function updatingFilterStatut(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->chambre_id = $this->filterChambre;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $l = Lit::findOrFail($id);
        $this->editingId = $l->id;
        $this->chambre_id = $l->chambre_id;
        $this->numero = $l->numero;
        $this->statut = $l->statut;
        $this->description = $l->description ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Lit::where('id', $this->editingId)->update($data);
            session()->flash('success', 'Lit mis à jour.');
        } else {
            Lit::create($data);
            session()->flash('success', 'Lit créé.');
        }
        $this->showModal = false;
        $this->resetForm();
    }

    public function changeStatut(int $id, string $statut): void
    {
        if (!in_array($statut, Lit::STATUTS)) return;
        Lit::where('id', $id)->update(['statut' => $statut]);
        session()->flash('success', 'Statut du lit mis à jour.');
    }

    public function confirmDelete(int $id): void { $this->deletingId = $id; $this->showDeleteModal = true; }
    public function delete(): void
    {
        if ($this->deletingId) {
            Lit::where('id', $this->deletingId)->delete();
            session()->flash('success', 'Lit supprimé.');
        }
        $this->showDeleteModal = false;
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->chambre_id = null;
        $this->numero = '';
        $this->statut = 'libre';
        $this->description = '';
        $this->resetErrorBag();
    }

    #[Layout('layouts.admin')]
    #[Title('Lits')]
    public function render()
    {
        $lits = Lit::query()
            ->with('chambre.service.etage.batiment')
            ->when($this->search, fn($q) => $q->where('numero', 'like', "%{$this->search}%"))
            ->when($this->filterChambre, fn($q) => $q->where('chambre_id', $this->filterChambre))
            ->when($this->filterStatut, fn($q) => $q->where('statut', $this->filterStatut))
            ->orderBy('chambre_id')->orderBy('numero')
            ->paginate(15);

        return view('livewire.admin.lits', [
            'lits' => $lits,
            'chambres' => Chambre::with('service')->orderBy('numero')->get(),
            'statuts' => Lit::STATUTS,
        ]);
    }
}
