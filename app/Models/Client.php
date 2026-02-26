<?php

namespace App\Models;
//nom, email, téléphone, statut_paiement, date_maintenance, licences_count
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
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
