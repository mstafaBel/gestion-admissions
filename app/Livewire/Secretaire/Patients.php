<?php

namespace App\Livewire\Secretaire;

use App\Models\Patient;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Patients extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;
    public bool $showDeleteModal = false;

    public ?int $editingId = null;
    public string $num_dossier = '';
    public string $nom = '';
    public string $prenom = '';
    public ?string $date_naissance = null;
    public string $sexe = '';
    public string $telephone = '';
    public string $adresse = '';
    public string $cni = '';
    public string $profession = '';
    public string $contact_urgence_nom = '';
    public string $contact_urgence_telephone = '';
    public string $contact_urgence_relation = '';
    public string $groupe_sanguin = '';
    public string $observations = '';

    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'num_dossier' => 'required|string|max:50|unique:patients,num_dossier,' . ($this->editingId ?? 'NULL'),
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date|before_or_equal:today',
            'sexe' => 'nullable|in:M,F',
            'telephone' => 'nullable|string|max:30',
            'adresse' => 'nullable|string|max:255',
            'cni' => 'nullable|string|max:50',
            'profession' => 'nullable|string|max:100',
            'contact_urgence_nom' => 'nullable|string|max:255',
            'contact_urgence_telephone' => 'nullable|string|max:30',
            'contact_urgence_relation' => 'nullable|string|max:100',
            'groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'observations' => 'nullable|string',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function getEtablissementId(): ?int
    {
        return auth()->user()->etablissement_id;
    }

    public function openCreate(): void
    {
        if (!$this->getEtablissementId()) {
            session()->flash('error', 'Aucun établissement affecté à votre compte.');
            return;
        }
        $this->resetForm();
        $this->num_dossier = Patient::genererNumDossier();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $p = Patient::findOrFail($id);
        if ((int) $p->etablissement_id !== (int) $this->getEtablissementId()) {
            abort(403, 'Ce patient n\'appartient pas à votre établissement.');
        }
        $this->editingId = $p->id;
        $this->num_dossier = $p->num_dossier;
        $this->nom = $p->nom;
        $this->prenom = $p->prenom;
        $this->date_naissance = $p->date_naissance?->format('Y-m-d');
        $this->sexe = $p->sexe ?? '';
        $this->telephone = $p->telephone ?? '';
        $this->adresse = $p->adresse ?? '';
        $this->cni = $p->cni ?? '';
        $this->profession = $p->profession ?? '';
        $this->contact_urgence_nom = $p->contact_urgence_nom ?? '';
        $this->contact_urgence_telephone = $p->contact_urgence_telephone ?? '';
        $this->contact_urgence_relation = $p->contact_urgence_relation ?? '';
        $this->groupe_sanguin = $p->groupe_sanguin ?? '';
        $this->observations = $p->observations ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $etablissementId = $this->getEtablissementId();
        if (!$etablissementId) {
            session()->flash('error', 'Aucun établissement affecté à votre compte.');
            return;
        }

        $data = $this->validate();
        foreach ($data as $k => $v) {
            if ($v === '') {
                $data[$k] = null;
            }
        }

        if ($this->editingId) {
            $existing = Patient::findOrFail($this->editingId);
            if ((int) $existing->etablissement_id !== (int) $etablissementId) {
                abort(403);
            }
            $existing->update($data);
            session()->flash('success', 'Patient mis à jour.');
        } else {
            $data['etablissement_id'] = $etablissementId;
            Patient::create($data);
            session()->flash('success', 'Patient enregistré.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $p = Patient::findOrFail($id);
        if ((int) $p->etablissement_id !== (int) $this->getEtablissementId()) {
            abort(403);
        }
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            $p = Patient::findOrFail($this->deletingId);
            if ((int) $p->etablissement_id !== (int) $this->getEtablissementId()) {
                abort(403);
            }
            $p->delete();
            session()->flash('success', 'Patient supprimé.');
        }
        $this->showDeleteModal = false;
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->num_dossier = '';
        $this->nom = '';
        $this->prenom = '';
        $this->date_naissance = null;
        $this->sexe = '';
        $this->telephone = '';
        $this->adresse = '';
        $this->cni = '';
        $this->profession = '';
        $this->contact_urgence_nom = '';
        $this->contact_urgence_telephone = '';
        $this->contact_urgence_relation = '';
        $this->groupe_sanguin = '';
        $this->observations = '';
        $this->resetErrorBag();
    }

    #[Layout('layouts.secretaire')]
    #[Title('Patients')]
    public function render()
    {
        $patients = Patient::query()
            ->where('etablissement_id', $this->getEtablissementId())
            ->with('admissionEnCours.service')
            ->when($this->search, fn($q) => $q->where(function ($w) {
                $w->where('num_dossier', 'like', "%{$this->search}%")
                  ->orWhere('nom', 'like', "%{$this->search}%")
                  ->orWhere('prenom', 'like', "%{$this->search}%")
                  ->orWhere('telephone', 'like', "%{$this->search}%")
                  ->orWhere('cni', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(15);

        return view('livewire.secretaire.patients', [
            'patients' => $patients,
            'sexes' => Patient::SEXES,
            'groupesSanguins' => Patient::GROUPES_SANGUINS,
        ]);
    }
}
