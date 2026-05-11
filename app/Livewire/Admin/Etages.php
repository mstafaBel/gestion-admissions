<?php

namespace App\Livewire\Admin;

use App\Models\Batiment;
use App\Models\Etage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Etages extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterBatiment = null;

    public bool $showModal = false;
    public bool $showDeleteModal = false;

    public ?int $editingId = null;
    public ?int $batiment_id = null;
    public ?int $numero = 0;
    public string $nom = '';
    public string $description = '';
    public bool $is_active = true;

    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'batiment_id' => 'required|exists:batiments,id',
            'numero' => 'required|integer|min:-5|max:200',
            'nom' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterBatiment(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->batiment_id = $this->filterBatiment;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $e = Etage::findOrFail($id);
        $this->editingId = $e->id;
        $this->batiment_id = $e->batiment_id;
        $this->numero = $e->numero;
        $this->nom = $e->nom ?? '';
        $this->description = $e->description ?? '';
        $this->is_active = (bool) $e->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Etage::where('id', $this->editingId)->update($data);
            session()->flash('success', 'Étage mis à jour.');
        } else {
            Etage::create($data);
            session()->flash('success', 'Étage créé.');
        }
        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void { $this->deletingId = $id; $this->showDeleteModal = true; }
    public function delete(): void
    {
        if ($this->deletingId) {
            Etage::where('id', $this->deletingId)->delete();
            session()->flash('success', 'Étage supprimé.');
        }
        $this->showDeleteModal = false;
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->batiment_id = null;
        $this->numero = 0;
        $this->nom = '';
        $this->description = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    #[Layout('layouts.admin')]
    #[Title('Étages')]
    public function render()
    {
        $etages = Etage::query()
            ->with('batiment.etablissement')
            ->withCount('services')
            ->when($this->search, fn($q) => $q->where('nom', 'like', "%{$this->search}%"))
            ->when($this->filterBatiment, fn($q) => $q->where('batiment_id', $this->filterBatiment))
            ->orderBy('batiment_id')->orderBy('numero')
            ->paginate(10);

        return view('livewire.admin.etages', [
            'etages' => $etages,
            'batiments' => Batiment::with('etablissement')->orderBy('nom')->get(),
        ]);
    }
}
