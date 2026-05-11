<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chambre extends Model
{
    use HasFactory;

    protected $table = 'chambres';

    protected $fillable = [
        'service_id',
        'numero',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const TYPES = ['simple', 'double', 'triple', 'suite'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function lits(): HasMany
    {
        return $this->hasMany(Lit::class);
    }
}
