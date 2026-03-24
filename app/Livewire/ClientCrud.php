<?php

namespace App\Livewire;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;
use App\Models\license;

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
        'date_maintenance' => 'required|date',
        'licences_count' => 'required|integer|min:0|max:500',
    ];

    public $delete_id=null;
    public $showDelete=false;

   
    public $showUpdateForm=false;
    
    // Auth vars
    public $showPasswordModal = false;
    public $clientToGiveAccess = null;
    public $newClientPassword = '';

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
    public $FormulaireModification=false;

    public $payment_id = null;

    public $clientselectionner_id = null;


    public $showLicenses = false;
    public $FormAjLicense = false;
    public $nom_license = '';
    public $quantite_disponible = 0;
    public $date_assignation = null;
    public $license_id = null;
/* ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
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

   $valide=$this->validate([
    'nom' => 'required|string|min:3|max:200',
        'email' => 'required|email|max:200',
        'telephone' => 'required|string|min:8|max:20',
        'statut_paiement' => 'required|in:payé,en_attente,en_retard',
            'date_maintenance' => 'required|date', 

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

        
         public function ModifierPaiment($id){
                $this->showHistory = false;
                $payment=Payment::findOrFail($id);

                $this->payment_id = $id; 
                $this->clientselectionner_id = $payment->client_id; 
                $this->Id_clientHis = $payment->client_id;
                $this->montant=$payment->montant;
                $this->date_payment=$payment->date_payment;
                $this->status_payment=$payment->status_payment;



                $this->FormulaireModification=true;

            }
         public function ModifierPaimentsSubmit(){

            $validerPaiment=$this->validate([
            'montant' => 'required|numeric|min:0',
            'date_payment' => 'required|date',
            'status_payment' => 'required|in:payé,en_attente,en_retard',]
            );

             $payment = Payment::findOrFail($this->payment_id); 
             $payment->update($validerPaiment);


            $this->reset([
                'montant',
                'date_payment',
                'status_payment'
                
            ]);
            
            $this->FormulaireModification=false;
            $this->showHistory=true;
             $this->AffHistorique($this->clientselectionner_id);

             
             session()->flash('updatedPayment', 'Paiement modifié avec succès');


            }
            


            /* LICENCES */
      public function AffLicenses($clientId) {

            $this->clientselectionner = Client::find($clientId);
            $this->Id_clientHis = $clientId;

             $this->showHistory = false;
             $this->showLicenses = true;
            }


            public function FormLicense($clientId){

            $this->Id_clientHis = $clientId;
            $this->nom_license = '';
            $this->quantite_disponible = 0;

            $this->date_assignation = now()->toDateString();
            $this->showLicenses = false;
    $this->FormAjLicense = true; $this->showHistory = false;
}

/*  Ajouter licences */

        /* public function ListeLicences(){
             $this->showLicenses = true;
        } */
        public function AjouterLicense(){

        $this->validate([
        'nom_license' => 'required|string|min:2',
        'quantite_disponible' => 'required|integer|min:1',
        'date_assignation' => 'required|date',
    ]);


            License::create([
                'nom' => $this->nom_license,
                'quantite_disponible' => $this->quantite_disponible,
                'client_id' => $this->Id_clientHis,
                'date_assignation' => $this->date_assignation,
            ]);

            $this->FormAjLicense = false;
            $this->AffLicenses($this->Id_clientHis);
            session()->flash('licenseAjoutee', 'Licence ajoutée avec succès');
}




 /*  */
        public function FermerLicenses()
        {
            $this->showLicenses = false;
            $this->FormAjLicense = false;
            $this->showHistory=true;
        }       

/////////////////////////CSV///////////////////////////////
public function exportCsv()
{
    $clients = Client::all(); // ou ta query filtrée

    $filename = 'clients-' . now()->format('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($clients) {
        $file = fopen('php://output', 'w');

        // En-têtes CSV
        fputcsv($file, ['ID', 'Nom', 'Email', 'Site', 'Date']);

        foreach ($clients as $client) {
            fputcsv($file, [
                $client->id,
                $client->name,
                $client->email,
                $client->site_url,
                $client->created_at->format('d/m/Y'),
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}





public function exportPaymentsPdf()
{
    $client = $this->clientselectionner;
    $payments = $client->payments;
    $filename = 'paiements-' . $client->nom . '-' . now()->format('Y-m-d') . '.pdf';

    $pdf = Pdf::loadView('pdf.payments', [
        'client'   => $client,
        'payments' => $payments,
    ])->setPaper('a4', 'portrait');

    return response()->streamDownload(
        fn () => print($pdf->output()),
        $filename
    );
}



    
public function openAccessModal($id)
{
    $this->clientToGiveAccess = Client::findOrFail($id);
    // Pre-fill with a random password that the admin can change if they want
    $this->newClientPassword = \Illuminate\Support\Str::random(10);
    $this->showPasswordModal = true;
}

public function closeAccessModal()
{
    $this->showPasswordModal = false;
    $this->clientToGiveAccess = null;
    $this->newClientPassword = '';
}

public function confirmAccess()
{
    $valider = $this->validate([
        'newClientPassword' => 'required|string|min:6',
    ]);

    // Check if a user with this email already exists
    $existingUser = \App\Models\User::where('email', $this->clientToGiveAccess->email)->first();
    if ($existingUser) {
        $this->closeAccessModal();
        session()->flash('deleted', "Erreur : Ce client ou cet E-mail a déjà un compte utilisateur actif !");
        return;
    }

    $user = \App\Models\User::create([
        'name' => $this->clientToGiveAccess->nom,
        'email' => $this->clientToGiveAccess->email,
        'password' => \Illuminate\Support\Facades\Hash::make($this->newClientPassword),
    ]);

    $user->assignRole('client');
    $this->clientToGiveAccess->update(['user_id' => $user->id]);

    $this->closeAccessModal();

    // Use a persistent flash or just normal flash
    session()->flash('created', "Le compte client a été créé avec succès. N'oubliez pas de lui transmettre son mot de passe !");
}

public function supprimerAcces($id)
{
    $client = Client::findOrFail($id);
    if ($client->user_id) {
        $user = \App\Models\User::find($client->user_id);
        if ($user) {
            $user->delete();
        }
        $client->update(['user_id' => null]);
        session()->flash('deleted', "L'accès client a été révoqué.");
    }
}

 //////////////RENDERRR ////////////////
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
       
    


        
            

        return view('livewire.client-crud', [
    'clients' => $clients,  
]);

    }
}
