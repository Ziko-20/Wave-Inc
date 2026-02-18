<<div class="p-6">
<h2 class="text-2xl font-bold">La Liste des Clients</h2>
    <div class="flex justify-between items-center">
        

        <table class="w-full border">
            <thead class="bg-gray-100"  >
                <tr>
                    <th class="">nom</th>
                    <th class="">prenom</th>
                    <th class="">telephone</th>
                    <th class="">statut_paiement</th>
                    <th class="">date_maintenance</th>
                    <th class="">Nombres licences</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    
               
                <tr>
                    <th class="text-center">{{$client->nom}}</th>4

                     <th class="text-center">{{$client->prenom}}</th>

                    <th class="text-center">{{$client->telephone}}</th>

                    <th class="text-center">{{$client->statut_paiement}}</th>

                    <th class="text-center">{{$client->date_maintenance}}</th>

                    <th class="text-center">{{$client->licences_count}}</th>

                </tr>
            

           @endforeach

            </tbody>
        </table>


    </div>

</div>
