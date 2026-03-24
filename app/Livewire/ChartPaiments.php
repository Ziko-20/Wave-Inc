<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Client;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

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

        // Fetching payments only for 'payé' status to make the chart accurate
        $results = Payment::selectRaw('MONTH(date_payment) as month, SUM(montant) as total')
            ->where('status_payment', '=', 'payé')
            ->whereYear('date_payment', $this->selectedYear)
            ->groupBy('month')
            ->get();

        foreach ($results as $row) {
            $dataPayments[$row->month - 1] = $row->total;
        }

        $totalClients = Client::count();
        
        $mrr = Payment::where('status_payment', '=', 'payé')
            ->whereMonth('date_payment', Carbon::now()->month)
            ->whereYear('date_payment', Carbon::now()->year)
            ->sum('montant');

        return view('livewire.chart-paiments', [
            'dataPayments'     => $dataPayments,
            'AnneeDisponibles' => $AnneeDisponibles,
            'totalClients'     => $totalClients,
            'clientsPayes'     => Client::where('statut_paiement', '=', 'payé')->count(),
            'clientsEnAttente' => Client::where('statut_paiement', '=', 'en_attente')->count(),
            'clientsEnRetard'  => Client::where('statut_paiement', '=', 'en_retard')->count(),
            'mrr'              => $mrr,
        ]);
    }
}