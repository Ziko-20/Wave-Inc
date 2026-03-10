<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
class Payment extends Model
{
    use HasFactory;
    protected $fillable=[
        'montant',
        'date_payment',
        'status_payment',
        'client_id'


    ];
    public function client(){
        return $this->belongsTo(Client::class);
    }
    
}
