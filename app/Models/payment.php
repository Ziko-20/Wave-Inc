<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    protected $fillable=[
        'montant',
        'date',
        'status',
        'client_id'


    ];
}
