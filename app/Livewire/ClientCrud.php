<?php

namespace App\Livewire;
use App\Models\Client;

use Livewire\Component;

class ClientCrud extends Component
{
    public $clients=[];

    public $nom='';
    public $email='';
    public $telephone='';
    public $statut_paiement='en attente';
    public $date_maintenance=null;
    public $licences_count='0';

    public $Form=false;

    protected $rules = [
        'nom' => 'required|string|min:3|max:200',
        'email' => 'required|email|unique:clients,email',
        'telephone' => 'required|string|min:8|max:20',
        'statut_paiement' => 'required|in:payé,en attente,en retard',
        'date_maintenance' => 'nullable|date',
        'licences_count' => 'required|integer|min:1|max:999',
    ];

    public $delete_id=null;
    public $showDelete=false;
    



    public function loadClients(){
        $this->clients=Client::all();

        

         }
    public function mount(){
        $this->loadClients();
    }     
    
    public function ShowForm(){
        $this->RenisialisationForm();
        $this->Form=true;
    }
    public function UnshowForm(){
         
        $this->Form=false;
    }
    public function RenisialisationForm(){
        $this->reset(
            [
        'nom',
        'email',
        'telephone',
        'statut_paiement',
        'date_maintenance',
        'licences_count'
            ]
        );
        $this->statut_paiement='en attente';
        
        

    }
    public function Ajouter(){
        $valide=$this->validate();


        Client::create($valide);
        $this->UnshowForm();
        $this->loadClients();

        session()->flash('success','votre client a ete ajouter avec succes');
    }


    //supression
    public function ConfirmerLaSuppression($id){
        $this->delete_id=$id;
        $this->showDelete=true;

    }
    public function delete(){
        Client::findOrFail($this->delete_id)->delete();
        $this->showDelete=false;
        $this->delete_id=null;

        $this->loadClients();
        session()->flash('succes','le client est supprimer');


    }


     public function render()
    {
        return view('livewire.client-crud');
    }

}
