<?php

namespace App\Livewire\Admin;

use App\Models\Admission;
use App\Models\Etablissement;
use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Admissions extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatut = '';
    public ?int $filterEtablissementId = null;
    public ?int $filterServiceId = null;

    public bool $showDetail = false;
    public ?int $selectedAdmissionId = null;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterStatut(): void { $this->resetPage(); }
    public function updatingFilterServiceId(): void { $this->resetPage(); }

    public function updatedFilterEtablissementId(): void
    {
        $this->filterServiceId = null;
        $this->resetPage();
    }

    public function voirDetail(int $admissionId): void
    {
        $this->selectedAdmissionId = $admissionId;
        $this->showDetail = true;
    }

    public function fermerDetail(): void
    {
        $this->showDetail = false;
        $this->selectedAdmissionId = null;
    }

    #[Layout('layouts.admin')]
    #[Title('Toutes les admissions')]
    public function render()
    {
        $admissions = Admission::query()
            ->with(['patient', 'service.etage.batiment.etablissement', 'lit.chambre', 'createur', 'cloturePar'])
            ->when($this->search, fn($q) => $q->whereHas('patient', function ($w) {
                $w->where('nom', 'like', "%{$this->search}%")
                  ->orWhere('prenom', 'like', "%{$this->search}%")
                  ->orWhere('num_dossier', 'like', "%{$this->search}%");
            }))
            ->when($this->filterStatut, fn($q) => $q->where('statut', $this->filterStatut))
            ->when($this->filterEtablissementId, fn($q) => $q->whereHas('service.etage.batiment',
                fn($w) => $w->where('etablissement_id', $this->filterEtablissementId)))
            ->when($this->filterServiceId, fn($q) => $q->where('service_id', $this->filterServiceId))
            ->latest('date_entree')
            ->paginate(20);

        $etablissements = Etablissement::orderBy('nom')->get();

        $services = Service::query()
            ->with('etage.batiment')
            ->when($this->filterEtablissementId, fn($q) => $q->whereHas('etage.batiment',
                fn($w) => $w->where('etablissement_id', $this->filterEtablissementId)))
            ->orderBy('nom')
            ->get();

        $admissionDetail = $this->selectedAdmissionId
            ? Admission::with(['patient', 'service.etage.batiment.etablissement', 'lit.chambre', 'createur', 'cloturePar'])
                ->find($this->selectedAdmissionId)
            : null;

        $stats = [
            'total' => Admission::count(),
            'en_cours' => Admission::where('statut', 'en_cours')->count(),
            'terminees' => Admission::where('statut', 'terminee')->count(),
            'transferees' => Admission::where('statut', 'transferee')->count(),
        ];

        return view('livewire.admin.admissions', [
            'admissions' => $admissions,
            'etablissements' => $etablissements,
            'services' => $services,
            'statuts' => Admission::STATUTS,
            'admissionDetail' => $admissionDetail,
            'stats' => $stats,
        ]);
    }
}
