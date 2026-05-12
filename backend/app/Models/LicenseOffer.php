<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseOffer extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'prix',
        'quantite_disponible'
    ];
}
