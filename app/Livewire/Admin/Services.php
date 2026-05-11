<?php

namespace App\Livewire\Admin;

use App\Models\Etage;
use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Services extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterEtage = null;

    public bool $showModal = false;
    public bool $showDeleteModal = false;

    public ?int $editingId = null;
    public ?int $etage_id = null;
    public string $nom = '';
    public string $code = '';
    public string $responsable = '';
    public string $description = '';
    public bool $is_active = true;

    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'etage_id' => 'required|exists:etages,id',
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'responsable' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterEtage(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->etage_id = $this->filterEtage;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $s = Service::findOrFail($id);
        $this->editingId = $s->id;
        $this->etage_id = $s->etage_id;
        $this->nom = $s->nom;
        $this->code = $s->code ?? '';
        $this->responsable = $s->responsable ?? '';
        $this->description = $s->description ?? '';
        $this->is_active = (bool) $s->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        if ($this->editingId) {
            Service::where('id', $this->editingId)->update($data);
            session()->flash('success', 'Service mis à jour.');
        } else {
            Service::create($data);
            session()->flash('success', 'Service créé.');
        }
        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void { $this->deletingId = $id; $this->showDeleteModal = true; }
    public function delete(): void
    {
        if ($this->deletingId) {
            Service::where('id', $this->deletingId)->delete();
            session()->flash('success', 'Service supprimé.');
        }
        $this->showDeleteModal = false;
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->etage_id = null;
        $this->nom = '';
        $this->code = '';
        $this->responsable = '';
        $this->description = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    #[Layout('layouts.admin')]
    #[Title('Services')]
    public function render()
    {
        $services = Service::query()
            ->with('etage.batiment.etablissement')
            ->withCount('chambres')
            ->when($this->search, fn($q) => $q->where(function ($w) {
                $w->where('nom', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%");
            }))
            ->when($this->filterEtage, fn($q) => $q->where('etage_id', $this->filterEtage))
            ->orderBy('nom')
            ->paginate(10);

        return view('livewire.admin.services', [
            'services' => $services,
            'etages' => Etage::with('batiment')->orderBy('batiment_id')->orderBy('numero')->get(),
        ]);
    }
}
