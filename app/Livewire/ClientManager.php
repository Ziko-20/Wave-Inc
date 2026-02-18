<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;  
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ClientManager extends Component
{
    public function render()
    {
       $clients=Client::all();

        return view('livewire.client-manager',compact('clients'));
    }
}
