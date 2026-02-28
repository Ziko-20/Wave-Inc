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
    public $statut_paiement='en_attente';
    public $date_maintenance=null;
    public $licences_count='0';

    public $Form=false;

    protected $rules = [
        'nom' => 'required|string|min:3|max:200',
        'email' => 'required|email|max:200',
        'telephone' => 'required|string|min:8|max:20',
        'statut_paiement' => 'required|in:payé,en_attente,en_retard',
        'date_maintenance' => 'nullable|date',
        'licences_count' => 'required|integer|min:0|max:500',
    ];

    public $delete_id=null;
    public $showDelete=false;

   
    public $Id_client=null;
    public $showUpdateForm=false;
    

    public $statusVal='all';

    public $Nom_a_Chercher='';

    public $clientselectionner=null;
    public $payments;
    public $showHistory=false;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
        $this->statut_paiement='en_attente';
        
        

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


    
    //modif
    public function UpdateForm($id){
        //gestion de l erreur si l id est introuvable
        $client=Client::findOrFail($id);
        //les champs
        $this->Id_client=$client->id;
        $this->nom=$client->nom;
        $this->email=$client->email;
        $this->telephone=$client->telephone;
        $this->statut_paiement=$client->statut_paiement;
        $this->date_maintenance=$client->date_maintenance;
        $this->licences_count=$client->licences_count;

        




        //affivhage du formulaire
        $this->showUpdateForm=true;

    }
    public function Update($Id_client){

   $valide=$this->validate(['nom' => 'required|string|min:3|max:200',
        'email' => 'required|email|max:200',
        'telephone' => 'required|string|min:8|max:20',
        'statut_paiement' => 'required|in:payé,en_attente,en_retard',
        'date_maintenance' => 'nullable|date',
        'licences_count' => 'required|integer|min:0|max:500',]);

    $client=Client::findOrFail($Id_client);

    $client->update($valide);

    $this->loadClients();





        $this->showUpdateForm=false;
//renisialiation du formulaire de moodification
    $this->reset([
        'nom',
        'email',
        'telephone',
        'statut_paiement',
        'date_maintenance',
        'licences_count'
            ]);
            $this->statut_paiement='en_attente';
            $this->licences_count=0;
             
        //success('Success','Votre modification est effectue');
        session()->flash('Success','Votre modification est effectue');
    }

    public function CacherFormUpdate(){
        $this->showUpdateForm=false;
    }
   
 //filtration par status

    
    
    /* public function Chercher(){
       if( $this->Nom_a_Chercher){
        /* 'nom',$this->Nom_a_Chercher */
           /* 
        
       }

    } */ 

      /* HISTORIQUE DE PAYMEENTS */
      public function AffHistorique($clientId){


        $client=Client::find($clientId);
        $this->clientselectionner=$client;
        
        $this->payments=$client->payments;
        $this->showHistory=true;
        

        }
    public function CacherHistorique(){

        
    $this->showHistory=false;

    }
 
 //////////////////////////////
    public function render(){       
        
    
    $query = Client::query();


    if (!empty($this->Nom_a_Chercher)) {
        $query->where('nom', 'like', '%' . $this->Nom_a_Chercher . '%');
    }
      if ($this->statusVal !== 'all') {
        $query->where('statut_paiement', $this->statusVal);
    }
        $this->clients = $query->get();

        //logic bour barre de recherche
/*          $this->clients= Client ::where('nom','like','%' .$this->Nom_a_Chercher .'%')->get();
 */            

        return view('livewire.client-crud');
    }
}
