<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lit extends Model
{
    use HasFactory;

    protected $table = 'lits';

    protected $fillable = [
        'chambre_id',
        'numero',
        'statut',
        'description',
    ];

    public const STATUT_LIBRE = 'libre';
    public const STATUT_OCCUPE = 'occupe';
    public const STATUT_MAINTENANCE = 'maintenance';
    public const STATUT_RESERVE = 'reserve';

    public const STATUTS = [
        self::STATUT_LIBRE,
        self::STATUT_OCCUPE,
        self::STATUT_MAINTENANCE,
        self::STATUT_RESERVE,
    ];

    public function chambre(): BelongsTo
    {
        return $this->belongsTo(Chambre::class);
    }

    public function admissionEnCours(): HasOne
    {
        return $this->hasOne(Admission::class)->where('statut', Admission::STATUT_EN_COURS);
    }

    public function estLibre(): bool
    {
        return $this->statut === self::STATUT_LIBRE;
    }
}
