<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
        use HasFactory;

   protected $fillable=[
        'user_id',
        'nom',
        'email',
        'telephone',
        'statut_paiement',
        'date_maintenance',
        'licences_count',

         'relance_flag',    
        'date_relance',    
        'note_relance'
        
    ];

    public function payments(){
        return $this->hasMany(Payment::class);
    }
    public function license(){
        return $this->hasMany(license::class);
    }

    /**
     * Get the user account associated with this client.
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
