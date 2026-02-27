<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
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
