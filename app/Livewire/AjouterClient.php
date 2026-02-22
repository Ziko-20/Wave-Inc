<?php

namespace App\Livewire;
use App\Models\Client;
use Livewire\Component;

class AjouterClient extends Component
{
            public $nom_prenom='';
            public $email='';
            public $telephone='';
            public $statut_paiement='en attente';
            public $date_maintenance='';
            public $licences_count=0;

            protected $rules=[
                'nom_prenom' => 'required|min:3',
                'email' => 'required|email|unique:clients,email',
                'telephone' => 'nullable',
                'statut_paiement' => 'required|in:payé,en attente,en retard',
                'date_maintenance' => 'nullable|date',
                'licences_count' => 'required|integer|min:0',

            ];




    public function save()
    {
        $validateData=$this->validate();
        Client::create($validateData);
        session()->flash('message','Le client eest ajouté avec succès');


        return  redirect()->to('/clients');
    }
    public function render(){
        return view('clients.ajouterclient');
    }
}


