<div class=" min-h-screen
  bg-slate-100
  flex
  items-center
  justify-center
  p-4
  font-sans"
>
<div class="bg-white rounded-2xl w-full max-w-lg p-10 ">

    <div class="flex items-center gap3  pb-6 mb-8 border-b border-slate-200 ">
        <!--logoo client track-->
        <div class=" w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">

        </div>
        <h1 class="text-xl font-bold text-blue-900">Ajouter<span class="text-green-500"> Client</span>
        </h1></div>
        
        
        
        <!-- ════ FORMULAIRE ════ -->
     <div class="flex flex-col gap-6">


           {{--  nom prenom --}}
           <div class="flex flex-col gap-1.5">
            <label for="" class="text-xs font-semibold">Nom & Prénom</label>
            <div class="relative">
                 <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"> <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
              <input type="text"
              placeholder="Entrer le Nom & Prénom"
              class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
               text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
            </div>
{{--  Email --}}
            <label for="" class="text-xs font-semibold">Adresse Email</label>

            <div class="relative">
<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.1 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
                        <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/>
          </svg>
              <input type="text"
              placeholder="exemple@gmail.com"
              class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
               text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
            </div>
{{-- Tell--}}
            <label for="" class="text-xs font-semibold">Téléphone </label>
            <div class="relative">
                 <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"> <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
              <input type="text"
              placeholder="06 __ __ __ __"
              class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
               text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
            </div>
{{-- status   --}}
            <label for="" class="text-xs font-semibold">Statut Paiement</label>
            <div class="relative">

                          <select type="text"
              placeholder="Entrer le Nom & Prénom"
              class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
               text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
                <option value="">✓ Payé</option>
                <option value="">⏳ En attente</option>
                <option value="">✗ En retard </option>
            </select>
            
            </div>
{{--  date--}}
            <label for="" class="text-xs font-semibold">Date</label>
            <div class="relative">
<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>
                          <input type="date"
              placeholder="Entrer le Nom & Prénom"
              class="w-full h-12 rounded-xl pl-10 pr-4  bg-slate-50  border border-slate-200 text-sm
               text-slate-800 placeholder-slate-300 focus:border-blue-900 ">  
            </div>
           
        </div>
           


      </div>
    

    
</div>
</div>














<!--
<div class=" ">
    <h1 class="text-xl font-bold text-center pt-6">Veuillez Remplire le Formulaire pour pouvoir Ajouter Votre Client</h1>
    <form type="POST" class="flex flex-col items-center gap-2 mb-4 pt-4"
        wire:submit.prevent="save"
    >

        <div class="grid grid-cols-3 items-center gap-4">
        <label for="">Nom & Prénom</label>
        <input type="text"
        class="col-span-2 rounded-lg "
        placeholder="Entrer le Nom & Prenom">
      </div>

 
      <div class="grid grid-cols-3 items-center gap-4">
        <label for="">Email</label>
        <input type="text"
        class="rounded-lg col-span-2 "
        placeholder="exemplel@gmail.com"
        ></div>
<div class="grid grid-cols-3 items-center gap-4">
        <label for="">Telephone</label>
        <input type="text"
        class="col-span-2 rounded-lg "
        placeholder="06********"
        ></div>

<div class="grid grid-cols-3 items-center gap-4">
        <label for="">Statut Paiement</label>

        <select name="" id="" class="col-span-2 rounded-lg "
        >
            <option value="">payé</option>
            <option value="">en attente</option>
            <option value="">en retard</option>
        </select>
    </div>
    
<div class="grid grid-cols-3 items-center gap-4">
        <label for="">Date </label>
        <input type="date"
        class="col-span-2 rounded-lg "
        >
    </div>

<div class="grid grid-cols-3 items-center gap-4">
        <label for="">Nombre de Licences</label>
        <input type="number"
        class="col-span-2 rounded-lg"
        
        >
</div>
    </form>
</div>
-->