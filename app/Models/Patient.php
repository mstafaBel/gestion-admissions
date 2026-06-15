<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'etablissement_id',
        'num_dossier',
        'nom',
        'prenom',
        'date_naissance',
        'sexe',
        'telephone',
        'adresse',
        'cni',
        'profession',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'contact_urgence_relation',
        'groupe_sanguin',
        'observations',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public const SEXES = ['M' => 'Masculin', 'F' => 'Féminin'];
    public const GROUPES_SANGUINS = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }

    public function admissionEnCours()
    {
        return $this->hasOne(Admission::class)->where('statut', 'en_cours');
    }

    public function getNomCompletAttribute(): string
    {
        return trim("{$this->nom} {$this->prenom}");
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_naissance?->age;
    }

    public static function genererNumDossier(): string
    {
        $year = date('Y');
        $last = static::where('num_dossier', 'like', "P{$year}%")
            ->orderByDesc('id')
            ->first();
        $next = $last
            ? ((int) substr($last->num_dossier, 5)) + 1
            : 1;
        return sprintf('P%s%05d', $year, $next);
    }
}
