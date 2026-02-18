<?php
//            

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class license extends Model
{
    protected $fillable=[
                    

        'nom',
        
        'quantiter_disponible',

        'client_id',


        'date_d\'assignation'

    ];
}
