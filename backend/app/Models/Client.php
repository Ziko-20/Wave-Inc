<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'email',
        'telephone',
        'statut_paiement',
        'date_maintenance',
        'licences_count',
        'relance_flag',
        'date_relance',
        'note_relance',
    ];

    protected $casts = [
        'relance_flag'     => 'boolean',
        'date_maintenance' => 'date',
        'date_relance'     => 'date',
    ];

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function license(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(License::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
