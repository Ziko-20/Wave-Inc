<?php

namespace App\Livewire;
use App\Models\Client;
use App\Models\Payment;
use Livewire\Component;
use Livewire\Attributes\Layout; 
use Carbon\Carbon;

class ClientTrackDashboard extends Component
{
    #[Layout('layouts.app')]
    
    public function render()
    {
        return view('livewire.client-track-dashboard',[
            'totalClients' => Client::count()
        ]);
    }
}
