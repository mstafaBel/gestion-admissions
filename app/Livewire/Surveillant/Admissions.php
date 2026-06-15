<?php

namespace App\Livewire\Surveillant;

use App\Models\Admission;
use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Admissions extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatut = Admission::STATUT_EN_COURS;
    public ?int $filterServiceId = null;

    public bool $showDetail = false;
    public ?int $selectedAdmissionId = null;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterStatut(): void { $this->resetPage(); }
    public function updatingFilterServiceId(): void { $this->resetPage(); }

    public function voirDetail(int $admissionId): void
    {
        $admission = Admission::with('service.etage.batiment')->findOrFail($admissionId);
        $etablissementId = (int) auth()->user()->etablissement_id;
        if ((int) $admission->service?->etage?->batiment?->etablissement_id !== $etablissementId) {
            abort(403);
        }
        $this->selectedAdmissionId = $admissionId;
        $this->showDetail = true;
    }

    public function fermerDetail(): void
    {
        $this->showDetail = false;
        $this->selectedAdmissionId = null;
    }

    #[Layout('layouts.surveillant')]
    #[Title('Admissions de l\'établissement')]
    public function render()
    {
        $etablissementId = auth()->user()->etablissement_id;

        $admissions = Admission::query()
            ->with(['patient', 'service.etage.batiment', 'lit.chambre', 'createur', 'cloturePar'])
            ->whereHas('service.etage.batiment', fn($q) => $q->where('etablissement_id', $etablissementId))
            ->when($this->search, fn($q) => $q->whereHas('patient', function ($w) {
                $w->where('nom', 'like', "%{$this->search}%")
                  ->orWhere('prenom', 'like', "%{$this->search}%")
                  ->orWhere('num_dossier', 'like', "%{$this->search}%");
            }))
            ->when($this->filterStatut, fn($q) => $q->where('statut', $this->filterStatut))
            ->when($this->filterServiceId, fn($q) => $q->where('service_id', $this->filterServiceId))
            ->latest('date_entree')
            ->paginate(20);

        $services = Service::query()
            ->with('etage.batiment')
            ->whereHas('etage.batiment', fn($q) => $q->where('etablissement_id', $etablissementId))
            ->orderBy('nom')
            ->get();

        $admissionDetail = $this->selectedAdmissionId
            ? Admission::with(['patient', 'service.etage.batiment', 'lit.chambre', 'createur', 'cloturePar'])
                ->find($this->selectedAdmissionId)
            : null;

        return view('livewire.surveillant.admissions', [
            'admissions' => $admissions,
            'services' => $services,
            'statuts' => Admission::STATUTS,
            'admissionDetail' => $admissionDetail,
        ]);
    }
}
