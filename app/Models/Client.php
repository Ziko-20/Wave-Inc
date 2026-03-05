<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
        use HasFactory;

   protected $fillable=[
        'nom',
        'email',
        'telephone',
        'statut_paiement',
        'date_maintenance',
        'licences_count'
    ];

    public function payments(){
        return $this->hasMany(Payment::class);
    }
    

}
