<?php

namespace App\Livewire\Admin;

use App\Models\Chambre;
use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Chambres extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterService = null;

    public bool $showModal = false;
    public bool $showDeleteModal = false;

    public ?int $editingId = null;
    public ?int $service_id = null;
    public string $numero = '';
    public string $type = 'simple';
    public string $description = '';
    public bool $is_active = true;

    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'numero' => 'required|string|max:50',
            'type' => 'required|in:simple,double,triple,suite',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterService(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->service_id = $this->filterService;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $c = Chambre::findOrFail($id);
        $this->editingId = $c->id;
        $this->service_id = $c->service_id;
        $this->numero = $c->numero;
        $this->type = $c->type;
        $this->description = $c->description ?? '';
        $this->is_active = (bool) $c->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Chambre::where('id', $this->editingId)->update($data);
            session()->flash('success', 'Chambre mise à jour.');
        } else {
            Chambre::create($data);
            session()->flash('success', 'Chambre créée.');
        }
        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void { $this->deletingId = $id; $this->showDeleteModal = true; }
    public function delete(): void
    {
        if ($this->deletingId) {
            Chambre::where('id', $this->deletingId)->delete();
            session()->flash('success', 'Chambre supprimée.');
        }
        $this->showDeleteModal = false;
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->service_id = null;
        $this->numero = '';
        $this->type = 'simple';
        $this->description = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    #[Layout('layouts.admin')]
    #[Title('Chambres')]
    public function render()
    {
        $chambres = Chambre::query()
            ->with('service.etage.batiment')
            ->withCount('lits')
            ->when($this->search, fn($q) => $q->where('numero', 'like', "%{$this->search}%"))
            ->when($this->filterService, fn($q) => $q->where('service_id', $this->filterService))
            ->orderBy('service_id')->orderBy('numero')
            ->paginate(10);

        return view('livewire.admin.chambres', [
            'chambres' => $chambres,
            'services' => Service::with('etage.batiment')->orderBy('nom')->get(),
            'types' => Chambre::TYPES,
        ]);
    }
}
