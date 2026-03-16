<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Client;
use Livewire\Attributes\Layout;

class ChartPaiments extends Component
{
    #[Layout('layouts.app')]

    public $selectedYear;

    public function mount()
    {
        $this->selectedYear = date('Y');
    }

    public function render()
    {
        $AnneeDisponibles = Payment::selectRaw('distinct YEAR(date_payment) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $dataPayments = array_fill(0, 12, 0);

        $results = Payment::selectRaw('MONTH(date_payment) as month, SUM(montant) as total')
            ->whereYear('date_payment', $this->selectedYear)
            ->groupBy('month')
            ->get();

        foreach ($results as $row) {
            $dataPayments[$row->month - 1] = $row->total;
        }

        $totalClients = Client::count();

        return view('livewire.chart-paiments', [
            'dataPayments'     => $dataPayments,
            'AnneeDisponibles' => $AnneeDisponibles,
            'totalClients'     => $totalClients,
            'clientsPayes'     => Client::where('statut_paiement', 'payé')->count(),
            'clientsEnAttente' => Client::where('statut_paiement', 'en_attente')->count(),
            'clientsEnRetard'  => Client::where('statut_paiement', 'en_retard')->count(),
        ]);
    }
}