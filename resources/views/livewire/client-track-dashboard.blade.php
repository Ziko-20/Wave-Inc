<div class="min-h-screen">





    
<div class="flex flex-col items-center justify-center text-center ">
    {{-- TITRRE --}}
    <div class="mx-auto font-bold py-6">
        <p class="text-4xl mt-4   font-extrabold text-gray-800">Bienvenu dans</p> 
       
        <p class="text-[#439670] text-2xl">Client <span class='text-[#22419A]'>Track</span></p>
        
        <p class="text-xs text-[#9CA3AF]">Simplifier notre gestion pour mieux servir nos clients</p>
    </div>
    </div>


{{-- LINKSSSSSSSS --}}
    <div class="flex  mt-14 justify-between ml-12 mr-8">

    {{-- Opérations Clients --}}
    <div class="flex-row bg-white w-[320px] h-[200px] rounded-[32px] ">
        <div class="bg-[#EDEEF5] w-[50px] h-[50px] rounded-[11px] mt-4 ml-6 flex items-center justify-center">
                <span class="material-symbols-outlined text-[40px] ">person</span> 
        </div>
       
        <a href="/clients" class="text-[#22419A] pl-6 pt-2 text-2xl text inline-flex items-center"><span class="underline ">Opérations Clients </span><span class="material-symbols-outlined no-underline">arrow_right_alt</span></a>
        <p class="text-[#9CA3AF] text-sm pt-4 pl-4 w">Consulter la liste | Ajouter | Modifier | Supprimer|Voir l'historique des paiements</p>


    </div>

    {{-- Abonnements & Paiements --}}
    <div class="flex-row bg-white w-[340px] h-[200px] rounded-[32px]">
        <div class="bg-[#EDEEF5] w-[50px] h-[50px] rounded-[11px] mt-4 ml-6 flex items-center justify-center">
                <span class="material-symbols-outlined text-[40px] ">attach_money</span> 

        </div>
        <a href="" class="text-[#439670] pl-6 pt-2 text-2xl text inline-flex items-center whitespace-nowrap"><span class="underline">Abonnements & Paiements </span><span class="material-symbols-outlined no-underline ml-1">arrow_right_alt</span></a>
        <p class="text-[#9CA3AF] text-sm pt-4 pl-4 w">Gérer les transactions, les paiements manuels</p>

    </div>

{{-- <span class="material-symbols-outlined">

</span> --}}
    {{-- Dashboard --}}
    <div class="flex-row bg-white w-[320px] h-[200px] rounded-[32px] ">
         <div class="bg-[#EDEEF5] w-[50px] h-[50px] rounded-[11px] mt-4 ml-6 flex items-center justify-center">
                <span class="material-symbols-outlined text-[40px] ">bar_chart_4_bars</span> 

        </div>
        <a href="" class="text-[#22419A] pl-6 pt-2 text-2xl text inline-flex items-center"><span class="underline ">Dashboard</span><span class="material-symbols-outlined no-underline">arrow_right_alt</span></a>
        <p class="text-[#9CA3AF] text-sm pt-4 pl-4 w">teeeeeeeeeeeext</p>


    </div>

</div>
{{-- nombres tt clients --}}
<Center>
<div class="w-[565px] h-[105px] mt-16 text-3xl bg-[#439670] rounded-[33px] text-white font-bold pt-4">
    Nombre de clients actuels
    {{-- Nombre clientssss --}}
    <div class="flex justify-center items-center">
<span class="material-symbols-outlined ">person</span>
 <p>{{ $totalClients }}</p>
    </div>
   
</div>
</Center>

</div>









</div>