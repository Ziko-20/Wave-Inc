<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientPortal extends Component
{
    #[Layout('layouts.app')]
    
    public $client;

    public function mount()
    {
        // On récupère le profil 'Client' correspondant à l'utilisateur connecté
        $this->client = Auth::user()->client;

        if (!$this->client) {
            abort(403, "Profil client introuvable. Veuillez contacter l'administrateur.");
        }
    }

    public function exportMyPaymentsPdf()
    {
        $payments = $this->client->payments()->orderBy('date_payment', 'desc')->get();
        $filename = 'mes-paiements-' . now()->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('pdf.payments', [
            'client'   => $this->client,
            'payments' => $payments,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    public function render()
    {
        return view('livewire.client-portal', [
            'payments' => $this->client->payments()->orderBy('date_payment', 'desc')->get(),
            'licenses' => $this->client->license()->orderBy('date_assignation', 'desc')->get(),
        ]);
    }
}
