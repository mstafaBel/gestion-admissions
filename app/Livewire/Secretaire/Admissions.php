<?php

namespace App\Livewire\Secretaire;

use App\Models\Admission;
use App\Models\Lit;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Admissions extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatut = Admission::STATUT_EN_COURS;

    public bool $showAdmissionModal = false;
    public bool $showSortieModal = false;
    public bool $showTransfertModal = false;

    public ?int $admissionId = null;
    public ?int $patient_id = null;
    public ?int $lit_id = null;
    public string $date_entree = '';
    public string $motif = '';
    public string $observations = '';

    public ?int $sortieAdmissionId = null;
    public string $date_sortie = '';
    public string $motif_sortie = '';
    public string $sortie_observations = '';

    public ?int $transfertAdmissionId = null;
    public ?int $transfert_service_id = null;
    public ?int $transfert_lit_id = null;
    public string $transfert_motif = '';

    public function mount(): void
    {
        $this->date_entree = now()->format('Y-m-d\TH:i');
        $this->date_sortie = now()->format('Y-m-d\TH:i');
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterStatut(): void { $this->resetPage(); }

    public function updatedTransfertServiceId(): void
    {
        $this->transfert_lit_id = null;
    }

    protected function getServiceId(): ?int
    {
        return auth()->user()->service_id;
    }

    public function getLitsDisponiblesProperty()
    {
        return $this->getLitsLibres($this->getServiceId());
    }

    public function getLitsTransfertProperty()
    {
        return $this->getLitsLibres($this->transfert_service_id);
    }

    protected function getLitsLibres(?int $serviceId)
    {
        if (!$serviceId) {
            return collect();
        }
        return Lit::query()
            ->with('chambre')
            ->where('statut', Lit::STATUT_LIBRE)
            ->whereHas('chambre', fn($q) => $q->where('service_id', $serviceId))
            ->orderBy('chambre_id')->orderBy('numero')
            ->get();
    }

    public function openAdmettre(?int $patientId = null): void
    {
        if (!$this->getServiceId()) {
            session()->flash('error', 'Aucun service affecté à votre compte.');
            return;
        }
        $this->admissionId = null;
        $this->patient_id = $patientId;
        $this->lit_id = null;
        $this->date_entree = now()->format('Y-m-d\TH:i');
        $this->motif = '';
        $this->observations = '';
        $this->resetErrorBag();
        $this->showAdmissionModal = true;
    }

    public function admettre(): void
    {
        $serviceId = $this->getServiceId();
        if (!$serviceId) {
            $this->addError('lit_id', 'Aucun service affecté à votre compte.');
            return;
        }

        $data = $this->validate([
            'patient_id' => 'required|exists:patients,id',
            'lit_id' => 'required|exists:lits,id',
            'date_entree' => 'required|date',
            'motif' => 'required|string|max:255',
            'observations' => 'nullable|string',
        ]);

        $patient = Patient::with('admissionEnCours')->find($data['patient_id']);
        if ($patient->admissionEnCours) {
            $this->addError('patient_id', 'Ce patient est déjà hospitalisé.');
            return;
        }

        DB::transaction(function () use ($data, $patient, $serviceId) {
            $lit = Lit::with('chambre')->lockForUpdate()->findOrFail($data['lit_id']);
            if (!$lit->estLibre()) {
                throw new \RuntimeException('Ce lit n\'est plus libre.');
            }
            if ((int) $lit->chambre->service_id !== (int) $serviceId) {
                throw new \RuntimeException('Le lit ne correspond pas à votre service.');
            }

            Admission::create([
                'patient_id' => $patient->id,
                'lit_id' => $lit->id,
                'service_id' => $serviceId,
                'created_by' => auth()->id(),
                'date_entree' => $data['date_entree'],
                'motif' => $data['motif'],
                'observations' => $data['observations'] ?? null,
                'statut' => Admission::STATUT_EN_COURS,
            ]);

            $lit->update(['statut' => Lit::STATUT_OCCUPE]);
        });

        session()->flash('success', 'Admission enregistrée. Le lit est désormais occupé.');
        $this->showAdmissionModal = false;
    }

    public function openSortie(int $admissionId): void
    {
        $admission = Admission::findOrFail($admissionId);
        if ((int) $admission->service_id !== (int) $this->getServiceId()) {
            abort(403);
        }
        $this->sortieAdmissionId = $admissionId;
        $this->date_sortie = now()->format('Y-m-d\TH:i');
        $this->motif_sortie = '';
        $this->sortie_observations = '';
        $this->resetErrorBag();
        $this->showSortieModal = true;
    }

    public function enregistrerSortie(): void
    {
        $data = $this->validate([
            'date_sortie' => 'required|date',
            'motif_sortie' => 'required|string|max:255',
            'sortie_observations' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {
            $admission = Admission::with('lit')->lockForUpdate()->findOrFail($this->sortieAdmissionId);
            if (!$admission->estEnCours()) {
                throw new \RuntimeException('Cette admission n\'est plus en cours.');
            }
            if ((int) $admission->service_id !== (int) $this->getServiceId()) {
                throw new \RuntimeException('Vous ne pouvez pas clôturer une admission hors de votre service.');
            }

            $admission->update([
                'date_sortie' => $data['date_sortie'],
                'motif_sortie' => $data['motif_sortie'],
                'observations' => trim(($admission->observations ?? '') . ($data['sortie_observations'] ? "\n" . $data['sortie_observations'] : '')),
                'statut' => Admission::STATUT_TERMINEE,
                'terminated_by' => auth()->id(),
            ]);

            $admission->lit?->update(['statut' => Lit::STATUT_LIBRE]);
        });

        session()->flash('success', 'Sortie enregistrée. Le lit est désormais libre.');
        $this->showSortieModal = false;
    }

    public function openTransfert(int $admissionId): void
    {
        $admission = Admission::findOrFail($admissionId);
        if ((int) $admission->service_id !== (int) $this->getServiceId()) {
            abort(403);
        }
        $this->transfertAdmissionId = $admissionId;
        $this->transfert_service_id = null;
        $this->transfert_lit_id = null;
        $this->transfert_motif = '';
        $this->resetErrorBag();
        $this->showTransfertModal = true;
    }

    public function transferer(): void
    {
        $data = $this->validate([
            'transfert_service_id' => 'required|exists:services,id',
            'transfert_lit_id' => 'required|exists:lits,id',
            'transfert_motif' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($data) {
            $ancienne = Admission::with('lit', 'patient')->lockForUpdate()->findOrFail($this->transfertAdmissionId);
            if (!$ancienne->estEnCours()) {
                throw new \RuntimeException('Cette admission n\'est plus en cours.');
            }

            $nouveauLit = Lit::with('chambre')->lockForUpdate()->findOrFail($data['transfert_lit_id']);
            if (!$nouveauLit->estLibre()) {
                throw new \RuntimeException('Le lit cible n\'est plus disponible.');
            }
            if ((int) $nouveauLit->chambre->service_id !== (int) $data['transfert_service_id']) {
                throw new \RuntimeException('Le lit ne correspond pas au service cible.');
            }

            $maintenant = now();

            $nouvelle = Admission::create([
                'patient_id' => $ancienne->patient_id,
                'lit_id' => $nouveauLit->id,
                'service_id' => $data['transfert_service_id'],
                'created_by' => auth()->id(),
                'date_entree' => $maintenant,
                'motif' => 'Transfert depuis ' . ($ancienne->service?->nom ?? '') . ' — ' . ($data['transfert_motif'] ?? ''),
                'statut' => Admission::STATUT_EN_COURS,
            ]);

            $ancienne->update([
                'date_sortie' => $maintenant,
                'statut' => Admission::STATUT_TRANSFEREE,
                'transferee_vers_admission_id' => $nouvelle->id,
                'terminated_by' => auth()->id(),
            ]);

            $ancienne->lit?->update(['statut' => Lit::STATUT_LIBRE]);
            $nouveauLit->update(['statut' => Lit::STATUT_OCCUPE]);
        });

        session()->flash('success', 'Patient transféré avec succès.');
        $this->showTransfertModal = false;
    }

    #[Layout('layouts.secretaire')]
    #[Title('Admissions')]
    public function render()
    {
        $serviceId = $this->getServiceId();

        $admissions = Admission::query()
            ->with(['patient', 'service', 'lit.chambre', 'createur'])
            ->where('service_id', $serviceId)
            ->when($this->search, fn($q) => $q->whereHas('patient', function ($w) {
                $w->where('nom', 'like', "%{$this->search}%")
                  ->orWhere('prenom', 'like', "%{$this->search}%")
                  ->orWhere('num_dossier', 'like', "%{$this->search}%");
            }))
            ->when($this->filterStatut, fn($q) => $q->where('statut', $this->filterStatut))
            ->latest('date_entree')
            ->paginate(15);

        $patientsLibres = Patient::query()
            ->whereDoesntHave('admissionEnCours')
            ->orderBy('nom')->orderBy('prenom')
            ->limit(500)
            ->get();

        return view('livewire.secretaire.admissions', [
            'admissions' => $admissions,
            'services' => Service::orderBy('nom')->get(),
            'monService' => $serviceId ? Service::find($serviceId) : null,
            'patientsLibres' => $patientsLibres,
            'statuts' => Admission::STATUTS,
        ]);
    }
}
