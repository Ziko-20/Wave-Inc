<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Payment;

use Livewire\Attributes\Layout; 


class ChartPaiments extends Component
{
 #[Layout('layouts.app')]
   
    public $selectedYear ;

   public function mount(){
    $this->selectedYear = date('Y');
   }

    public function render()
    {

         
        $AnnDisponibles = Payment::selectRaw('YEAR(date_payment) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

       
        $paymentTt = Payment::selectRaw('MONTH(date_payment) as month, SUM(montant) as total')
            ->whereYear('date_payment', $this->selectedYear)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = $paymentTt[$i] ?? 0;
        }
      

        return view('livewire.chart-paiments', [
        'dataPayments' => $data,
        'AnneeDisponibles'=>$AnnDisponibles
    ]);
    }
}

