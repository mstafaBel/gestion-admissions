<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etage extends Model
{
    use HasFactory;

    protected $table = 'etages';

    protected $fillable = [
        'batiment_id',
        'numero',
        'nom',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'numero' => 'integer',
    ];

    public function batiment(): BelongsTo
    {
        return $this->belongsTo(Batiment::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function getLibelleAttribute(): string
    {
        return $this->nom ? "Étage {$this->numero} – {$this->nom}" : "Étage {$this->numero}";
    }
}
