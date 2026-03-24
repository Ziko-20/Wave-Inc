<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\LicenseOffer;

class LicenseShop extends Component
{
    #[Layout('layouts.app')]

    public function buyLicense($offerId)
    {
        return redirect()->route('paypal.create', ['offer' => $offerId]);
    }

    public function render()
    {
        return view('livewire.license-shop', [
            'offers' => LicenseOffer::where('quantite_disponible', '>', 0)->get()
        ]);
    }
}
