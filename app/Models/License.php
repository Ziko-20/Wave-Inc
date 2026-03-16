<?php
//            

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\returnSelf;

class license extends Model
{
        protected $fillable=[
                        
            'nom',
            'quantite_disponible',
            'client_id',
            'date_assignation'

        ];

    public function client(){
        return $this->belongsTo(Client::class);
    }
}
