<div class="p-6">

    <h2 class="text-2xl font-bold  ">La Liste des Clients</h2>

    <div class="flex gap-12 justify-center ">
    <a href="#"
   class="bg-blue-600 hover:bg-blue-700 hover:text-white rounded-lg px-4 py-2 inline-block">
   Ajouter client
    </a>
        
    <a 
    href=""
    type="button" class="bg-blue-600 hover:bg-blue-700 hover:text-white rounded-lg w-48 inline-block px-4 py-2 text-center">Modofier</a>
    <a 
    href=""
    type="button"class="bg-blue-600 hover:bg-blue-700 hover:text-white rounded-lg w-48 px-4 py-2 text-center">Supprimer</a>
    
    </div>  


<br>


    <div class="flex justify-between items-center">
        <table class="w-full  border-2 ">
            <thead class="bg-gray-100">
                <tr >
                    <th class=" border-2">nom et prenom</th>
                    <th class=" border-2">email</th>
                    <th class=" border-2">telephone</th>
                    <th class=" border-2">statut_paiement</th>
                    <th class=" border-2">date_maintenance</th>
                    <th class=" border-2">Nombres licences</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    
               
                <tr>
                    <th class="text-center border-2">{{$client->nom}}</th>

                     <th class="text-center border-2">{{$client->email}}</th>

                    <th class="text-center border-2">{{$client->telephone}}</th>

                    <th class="text-center border-2">

                        <!--'payé', 'en attente', 'en retard'-->
                       
                        
                            @if($client->statut_paiement=='payé')
                                <span class="text-green-600">{{$client->statut_paiement}}</span>
                                    @elseif ($client->statut_paiement=='en attente')
                                <span class="text-orange-500">{{$client->statut_paiement}}</span>
                                    @elseif($client->statut_paiement=='en retard')
                                <span class="text-red-600">{{$client->statut_paiement}}</span>
                            @endif
                 
                    </th>

                    <th class="text-center border-2">{{$client->date_maintenance}}</th>

                    <th class="text-center border-2">{{$client->licences_count}}</th>

                </tr>
            

           @endforeach

            </tbody>
        </table>
        



    </div>


</div>
