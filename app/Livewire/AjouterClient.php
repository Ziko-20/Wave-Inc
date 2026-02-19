<?php

namespace App\Livewire;

use Livewire\Component;

class AjouterClient extends Component
{
            public$nom_prenom='';
            public $email='';
            public $telephone='';
            public $statut_paiement='en attente';
            public $date_maintenance='';
            public $licences_count=0;
    public function save()
    {


        return view('livewire.ajouterclient');
    }
}
