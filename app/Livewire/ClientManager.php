<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;  


class ClientManager extends Component


{
   

    public function render()
    {
      return view('livewire.client-manager', [
        
        'clients' => Client::all()
    ]);
    }
}
