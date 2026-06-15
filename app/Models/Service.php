<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'etage_id',
        'nom',
        'code',
        'responsable',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function etage(): BelongsTo
    {
        return $this->belongsTo(Etage::class);
    }

    public function chambres(): HasMany
    {
        return $this->hasMany(Chambre::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }
}
