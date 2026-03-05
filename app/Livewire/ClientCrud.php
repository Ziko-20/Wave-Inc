<?php

namespace App\Livewire;
use App\Models\Client;

use App\Models\Payment;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
class ClientCrud extends Component
{
    use WithPagination;
    
    #[Layout('layouts.app')]
  /*  public $clients=[];  */
    public $nom='';
    public $email='';
    public $telephone='';
    public $statut_paiement='en_attente';
    public $date_maintenance=null;
    public $licences_count=0;

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
/* PAIMENTS */
    public $clientselectionner=null;

    public $payments;
    public $showHistory=false;
//ajouter paiment
    public $PaymentForm=false;
    public $IdPaimentClient;
    

    public $utilisateurIntrouvable=null;
    public $FormAjPaiment=false;

    public $montant = null;
    public $date_payment = null;
    public $status_payment = 'en_attente';
    public $Id_clientHis = null;
    public $deletedPayment=false;
///////////////////////////////////////// ////////////////////////////////////////////////////////////////////////
    public function loadClients(){
        $this->clients=Client::all();
  
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
        
        /* $this->loadClients(); */ 
       

    session()->flash('created','Client ajouté avec succès');
     $this->UnshowForm();
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

        /*$this->loadClients();*/
       session()->flash('deleted','Le client a été supprimé avec succès');


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

    /* $this->loadClients(); */





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
        
        session()->flash('updated', 'Client modifié avec succès');
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
    public function updatingNomAChercher()
{
    $this->resetPage();
}
/*     public function FormPaiment($IdPaimentClient ){
        $this->IdPaimentClient->$id;
        $this->clientSelPaiment=Client::findOrFail($IdPaimentClient );
        $this->id_paiment=null;
        $this->montant='';
        $this->date_payment=now();
        $this->status_payment='en attente';
        $this->PaymentForm=true;
    }
    public function AjouterPaiment(){
       




         $this->validate([
              'montant' => 'required|numeric|min:0',
              'date_paiement' => 'required|date',
              'statut_payment' => 'required|in:payé,en_attente,en_retard',
              'notes' => 'nullable|string|max:500',
         ]);
    }  */
///////////////////////////////
    /* Ajouter paiment */


    public function FormPaiment($client_id){

        $this->Id_clientHis=$client_id;
        

        ///champss
        $this->montant=null;
        $this->date_payment=now();
        $this->status_payment='en_attente';

         $this->showHistory = false;
        $this->FormAjPaiment=true;
    }
        public function FermerFormPaiment(){
            
            

            $this->reset([
                'montant',
                'date_payment',
                'status_payment',

            ]);
            $this->AffHistorique($this->Id_clientHis);
        }

        public function AjouterPaiment(){

        $validerPaiment=$this->validate([
            'montant' => 'required|numeric|min:0',
            'date_payment' => 'required|date',
            'status_payment' => 'required|in:payé,en_attente,en_retard',]
            );
        $validerPaiment['client_id'] = $this->Id_clientHis;

        Payment::create($validerPaiment);
            $this->FermerFormPaiment();
            

        session()->flash('paimentajouter','Le paiment a été ajouter avec succès');

        }
        public function SupprimerPaiment($id){
            Payment::findOrFail($id)->delete();
            $this->deletedPayment=true;
            $this->Id_client=null;
            
            session()->flash('deletedPayment',__('paymentdeleted')  );
        }





    
 ////////////// ////////////////
    public function render(){       
        
    
    $query = Client::query();

    $this->utilisateurIntrouvable =null;


    if (!empty($this->Nom_a_Chercher)) {
        $query->where('nom', 'like', '%' . $this->Nom_a_Chercher . '%');
       
    }
      if ($this->statusVal !== 'all') {
        $query->where('statut_paiement', $this->statusVal);
    }

     $clients = $query->paginate(15);

        if(!empty($this->Nom_a_Chercher)&& $clients->isEmpty()){
            $this->utilisateurIntrouvable='Aucun client trouvé avec ce nom';
        }
       


        
        //logic bour barre de recherche
/*          $this->clients= Client ::where('nom','like','%' .$this->Nom_a_Chercher .'%')->get();
 */            

        return view('livewire.client-crud',['clients'=>$clients]);
    }
}
