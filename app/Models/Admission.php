<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'lit_id',
        'service_id',
        'created_by',
        'terminated_by',
        'date_entree',
        'date_sortie',
        'motif',
        'observations',
        'motif_sortie',
        'statut',
        'transferee_vers_admission_id',
    ];

    protected $casts = [
        'date_entree' => 'datetime',
        'date_sortie' => 'datetime',
    ];

    public const STATUT_EN_COURS = 'en_cours';
    public const STATUT_TERMINEE = 'terminee';
    public const STATUT_TRANSFEREE = 'transferee';

    public const STATUTS = [
        self::STATUT_EN_COURS => 'En cours',
        self::STATUT_TERMINEE => 'Terminée',
        self::STATUT_TRANSFEREE => 'Transférée',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function lit(): BelongsTo
    {
        return $this->belongsTo(Lit::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cloturePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'terminated_by');
    }

    public function admissionSuivante(): BelongsTo
    {
        return $this->belongsTo(Admission::class, 'transferee_vers_admission_id');
    }

    public function estEnCours(): bool
    {
        return $this->statut === self::STATUT_EN_COURS;
    }

    public function getStatutLibelleAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    public function getDureeSejourAttribute(): ?int
    {
        if (!$this->date_entree) {
            return null;
        }
        $fin = $this->date_sortie ?? now();
        return $this->date_entree->diffInDays($fin);
    }
}
