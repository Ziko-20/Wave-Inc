    <div>
        @if ($showUpdateForm)
        <div class="min-h-screen">
<div class="flex   ">
        <div class="flex flex-row py-8 pl-5">
            <button class=" border rounded-xl w-48 h-10 font-sm hover:bg-slate-400"
            wire:click="CacherFormUpdate">
                ← Retour à la liste
            </button>
        </div>
 </div>

        <div class=" 
    bg-slate-100
    flex
    items-center
    justify-center
    p-4
    font-sans"
    >
    {{-- retourner a la page de la liste clients --}}
  
   

    <div class="bg-white rounded-2xl w-full max-w-lg p-10 ">

        <div class="flex items-center gap3  pb-6 mb-8 border-b border-slate-200 ">
            <!--titre-->
            <div class=" w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">

            </div>
            <h1 class="text-xl font-bold text-blue-900">Modifier<span class="text-green-500"> Client</span>
            </h1>
        </div>

            
            
            
            <!-- formulaire Modification -->
        <form wire:submit.prevent="Update({{ $Id_client }})" class="flex flex-col gap-6">


            {{--  nom prenom --}}
            <div class="flex flex-col gap-1.5">
                <label for="" class="text-xs font-semibold">Nom & Prénom</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"> <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                <input type="text"
                wire:model.defer="nom"
                placeholder="Entrer le Nom & Prénom"
                class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
                text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
                
                </div>
    {{--  Email --}}
                <label for="" class="text-xs font-semibold">Adresse Email</label>

                <div class="relative">
    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <rect x="2" y="4" width="20" height="16" rx="2"/>
    <path d="m2 7 10 7 10-7"/>
    </svg>
                <input type="text"
                wire:model.defer="email"
                placeholder="exemple@gmail.com"
                class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
                text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
                    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
    {{-- Tell--}}
                <label for="" class="text-xs font-semibold">Téléphone</label>
                <div class="relative">
    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.1 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
    </svg>              <input type="text"
                wire:model.defer="telephone"
                placeholder="06 __ __ __ __"
                class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
                text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
                    @error('telephone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
    {{-- status   --}}
                <label for="" class="text-xs font-semibold">Statut Paiement</label>
                <div class="relative">

                <select type="text"
                
                wire:model.defer="statut_paiement"
                class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
                text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
                    <option value="payé">✓ Payé</option>
                    <option value="en_attente">⏳ En attente</option>
                    <option value="en_retard">✗ En retard </option>
                </select>
                @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                
                </div>
    {{--  date--}}
                <label for="" class="text-xs font-semibold">Date</label>
                <div class="relative">
    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                <input type="date"
                wire:model.defer="date_maintenance"
                placeholder=""
                class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
                text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
                    @error('date_maintenance') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <button 
                class="bg-indigo-600 text-white rounded-xl py-3 font-semibold shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                type="submit" 
                >Modifier</button>
            
            </div>
            


        </div>
        

        
    </div>
    </div>
</div>
                             {{-- PARTIE HISTORIQUE PAIMENTSS --}}

    @elseif($showHistory)
    <div class="min-h-screen">
    <div class="flex   ">
        <div class="flex flex-row py-8 pl-5">
            <button class=" border rounded-xl w-48 h-10 font-sm hover:bg-slate-400"
            wire:click="CacherHistorique">
                ← Retour à la liste
            </button>
        </div>
 </div>

 <div class="flex flex-col">
{{-- Profil --}}
    <div class="justify-center">

        @if ($clientselectionner)
            
        
            <center>
        
        <div class="w-48 h-48 rounded-full bg-indigo-100 text-indigo-600 font-bold text-7xl flex items-center justify-center flex-shrink-0">
              {{ strtoupper(substr($clientselectionner->nom, 0, 1 )) }}
                                </div>
                                
                                <p class="pt-4">{{ $clientselectionner->nom }}</p>
            </center>
    </div>
{{-- Tableau d historiques   --}}  
    <div class="flex-col">
       
            
        <div class="flex-row  rounded-xl">
                    <table class=" border w-4/5 mx-auto mt-4 ">
            <thead class=" ">
                
                
                <tr class="border ">
                        <th class="text-xl text-blue-800 text-center" colspan="3"> Historique des paiements</th>
                       </tr>
                       <tr>
                    <td class="text-xs font-semibold     text-gray-400 uppercase  px-6 py-4 ">Montant</td>
                    <td class="text-xs font-semibold   text-gray-400 uppercase px-6 py-4 ">Date</td>
                    <td class="text-xs font-semibold   text-gray-400 uppercase  px-6 py-4 ">Statut</td>
                </tr>
            </thead>
            <tbody>
                 @foreach ($clientselectionner -> payments as $payment )
                <tr class="border"> 
                   
                        
                    
                    <td class="px-6 py-4 text-gray-500 bg-slate-50">{{$payment->montant}} MAD</td>
                    <td class="px-6 py-4 text-gray-500 bg-slate-50">{{$payment ->date_payment}} </td>

                    <td class="bg-slate-50 ">
                        @if($payment->status_payment == 'payé')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                    Payé
                                </span>
                            @elseif($payment->status_payment == 'en_attente')
                                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-semibold px-3 py-1 rounded-full border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-400 inline-block"></span>
                                    En attente
                                </span>
                            @elseif($payment->status_payment == 'en_retard')
                                <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 text-xs font-semibold px-3 py-1 rounded-full border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                    En retard
                                </span>
                            @endif
                        </td>

                   
                </tr>     @endforeach

            </tbody>
            
        </table>
       
    </div>
{{-- Contacter client  --}}   
    <div class="flex flex-col items-center justify-between mt-12 gap-5">
        <div class="text-2xl text-green-600">Contacter le Client <span class="text-blue-800 ">{{ $clientselectionner->nom }}</span></div>

        <div class="relative bg-white w-48 rounded-xl">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.1 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
    </svg> 
          <p class="text-gray-600 pl-8 " id="telephone">  {{ $clientselectionner->telephone }}</p>

          <button 
          class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-black"
           onclick="CopieData(this, 'telephone')"
          >
<span class="copyIcon material-symbols-outlined pt-1.5">copy_all</span>
{{-- Icon qui apparait apres copier le contenu --}}
<span class="copiedIcon material-symbols-outlined hidden">check</span>
  
</button>

        </div>

        <div class="relative bg-white w-72 rounded-xl">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <rect x="2" y="4" width="20" height="16" rx="2"/>
    <path d="m2 7 10 7 10-7"/>
    </svg>
            
            <p class="text-gray-600 pl-8" id="email">{{ $clientselectionner-> email }}</p>
            <button 
            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-black"
             onclick="CopieData(this, 'email')"       >
  <span class="copyIcon material-symbols-outlined pt-1.5">
copy_all
</span>

{{-- Icon qui apparait apres copier le contenu --}}
<span class="copiedIcon material-symbols-outlined hidden">
check
</span>

</button>

        
        </div>
        

    </div>
    
 </div>
@endif
        </div>
        
   </div>




            
        {{-- ///////////////////////////////////// --}}




{{-- AJOUTER CLIENT --}}
        @elseif ($Form)
<div class="min-h-screen">
        <div class="flex gap-0">
        <div class="flex flex-row py-8 pl-5">
            <button class=" border rounded-xl w-48 h-10 font-sm hover:bg-slate-400 hover:text-opacity-50"
            wire:click="UnshowForm">
                ← Retour à la liste
            </button>
        </div>
 </div>
    <div class="  bg-slate-100  flex items-center  justify-center   font-sans">
   

    <div class="bg-white rounded-2xl w-full max-w-lg p-10 mt">

        <div class="flex items-center gap3  pb-6 mb-8 border-b border-slate-200 ">
            <!--titre-->
            <div class=" w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">

            </div>
            <h1 class="text-xl font-bold text-blue-900">Ajouter<span class="text-green-500"> Client</span>
            </h1>
        </div>

            
            
            
            <!-- formulaire -->
        <form wire:submit.prevent="Ajouter" class="flex flex-col gap-6">


            {{--  nom prenom --}}
            <div class="flex flex-col gap-1.5">
                <label for="" class="text-xs font-semibold">Nom & Prénom</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"> <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                <input type="text"
                wire:model.defer="nom"
                placeholder="Entrer le Nom & Prénom"
                class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
                text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
                </div>
    {{--  Email --}}
                <label for="" class="text-xs font-semibold">Adresse Email</label>

                <div class="relative">
    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <rect x="2" y="4" width="20" height="16" rx="2"/>
    <path d="m2 7 10 7 10-7"/>
    </svg>
                <input type="text"
                wire:model.defer="email"
                placeholder="exemple@gmail.com"
                class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
                text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
                 @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                </div>
    {{-- Tell--}}
                <label for="" class="text-xs font-semibold">Téléphone </label>
                <div class="relative">
    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.1 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
    </svg>              <input type="text"
                wire:model.defer="telephone"
                placeholder="06 __ __ __ __"
                class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
                text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
                @error('telephone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                </div>
    {{-- status   --}}
                <label for="" class="text-xs font-semibold">Statut Paiement</label>
                <div class="relative">

                <select type="text"
                placeholder=""
                wire:model.defer="statut_paiement"
                class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
                text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
                    <option value="payé">✓ Payé</option>
                    <option value="en_attente">⏳ En attente</option>
                    <option value="en_retard">✗ En retard </option>
                </select>
                 @error('statut_paiement') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                
                </div>
    {{--  date--}}
                <label for="" class="text-xs font-semibold">Date</label>
                <div class="relative">
    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                <input type="date"
                wire:model.defer="date_maintenance"
                placeholder=""
                class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
                text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
                 @error('date_maintenance') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                </div>
                <button 
                class="bg-indigo-600 text-white rounded-xl py-3 font-semibold shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                type="submit"
                >Ajouter</button>
            
            </div>
            


        </div>
        

        
    </div>
    </div>







   </div>     
    @elseif (!$Form)

    {{-- afffichage de clients --}}

    <div class="min-h-screen bg-gray-50 p-8">

        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">La Liste des Clients</h2>
        </div>

        
        <div class="flex flex-wrap justify-between mb-8 sm:flex-row sm:justify-between">
            <button wire:click="ShowForm"
            class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-md shadow-indigo-200 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter client
            </button>
            
            {{-- BARRE DE RECHERCHE --}}
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <circle cx="11" cy="11" r="8"/>
    <path d="m21 21-4.35-4.35"/>
    </svg> 



    
            <input 
            wire:model.live="Nom_a_Chercher"
            {{-- wire:model="Nom_a_Chercher" --}}
            type="text" class="rounded-xl font-semibold text-sm  hover:placeholder:text-gray-150"
            placeholder="Tapez le nom du client"
            >
            {{-- <button
            wire:click="Chercher">
                Chercher
            </button> --}}
        </div>
            {{-- filtration par status --}}
            <select 
            {{-- wire:change="statusChange({{ $statusVal }})" --}}
           {{--  wire:change="Filter" --}}
            wire:model.live="statusVal"
            class="flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl shadow-md shadow-indigo-200 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
            >
            <option value="all" >Tous</option>
            <option value="payé" >payé</option>
            <option value="en_attente" >en attente</option>
            <option value="en_retard"  >en retard</option>
            
        </select>
            
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="max-h-96 overflow-y-auto">
            <table class="w-full text-sm">
                <thead class="sticky top-0 bg-gray-50 z-10">
                    <tr class="bg-gray-50 border-b border-gray-10 z-10">
                        
                        <th class="text-left text-xs font-semibold   text-gray-400 uppercase tracking-wider px-6 py-4">Nom </th>
                        <th class="text-left text-xs  font-semibold  text-gray-400 uppercase tracking-wider px-6 py-4">Email</th>
                        <th class="text-left text-xs font-semibold   text-gray-400 uppercase tracking-wider px-6 py-4">Téléphone</th>
                        <th class="text-left text-xs   font-semibold text-gray-400  uppercase tracking-wider px-6 py-4 ">Statut</th>
                        <th class="text-left text-xs font-semibold   text-gray-400 uppercase tracking-wider px-6 py-4">Date maintenance</th>
                        <th class="text-left text-xs font-semibold   text-gray-400 uppercase tracking-wider  px-6    py-4 ">Licences</th>
                        <th colspan="3"  class="text-center text-xs   font-semibold text-gray-400 uppercase tracking-wider px-6 py-4" >Actions</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($clients as $client)
                    <tr class="hover:bg-indigo-50/40 transition-colors duration-150 group">

                    
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($client->nom, 0, 1 )) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $client->nom }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-500">{{ $client->email }}</td>

                    <td class="px-6 py-4 text-gray-600 font-mono tracking-wide">{{ $client->telephone }}</td>

                        
                        <td class="px-6 py-4">
                            @if($client->statut_paiement == 'payé')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                    Payé
                                </span>
                            @elseif($client->statut_paiement == 'en_attente')
                                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-semibold px-3 py-1 rounded-full border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-400 inline-block"></span>
                                    En attente
                                </span>
                            @elseif($client->statut_paiement == 'en_retard')
                                <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 text-xs font-semibold px-3 py-1 rounded-full border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                    En retard
                                </span>
                            @endif
                        </td>

                        
                        <td class="px-6 py-4 text-gray-500">{{ $client->date_maintenance }}</td>

                        
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center bg-indigo-100 text-indigo-700 text-xs font-bold w-8 h-8 rounded-lg">
                                {{ $client->licences_count }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="AffHistorique({{ $client->id }})"
            class="flex items-center gap-2 bg-teal-100  text-teal-600 hover:bg-teal-200 font-semibold text-sm px-5 py-2.5 rounded-xl border border-black-200 shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"  stroke-linejoin="round"class="w-5 h-5">
  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
  <circle cx="12" cy="12" r="3"/>
</svg>
                
            </button>
                        </td>
                        <td>
                            {{-- MODIFICATION --}}
                            <button  
                    wire:click="UpdateForm({{ $client->id }})"
            class="flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Modifier
            </button></td>
                <td>

                    {{-- SUPPRIMER --}}
                    <button wire:click="ConfirmerLaSuppression({{ $client->id }})"
            class="flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 font-semibold text-sm px-5 py-2.5 rounded-xl border border-red-200 shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                
            </button>
                </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>

    </div>
        @if ($showDelete)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center">
            <div class="bg-white p-6 rounded-xl w-full max-w-md">
                <p>Tu es sûr de supprimer ?</p>

                <button wire:click="delete" class=" bg-red-50 hover:bg-red-100 text-red-600 font-semibold text-sm px-5 py-2.5 rounded-xl border border-red-200 shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    Oui supprimer
                </button>
                <button wire:click="$set('showDelete', false)"
                class="bg-white hover:bg-gray-100 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                >
                    Annuler
                </button>
            </div>
        </div>
            
        @endif



    @endif















    </div>