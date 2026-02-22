<div class="min-h-screen bg-gray-50 p-8">

    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">La Liste des Clients</h2>
    </div>

    
    <div class="flex flex-wrap justify-between mb-8 sm:flex-row sm:justify-between mb-8">
        <a href="#"
           class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-md shadow-indigo-200 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Ajouter client
        </a>
        
        <select href=""
        class="flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl shadow-md shadow-indigo-200 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
        >
        <option value="">payé</option>
        <option value="">en attente</option>
        <option value="">en retard</option>
            
    </select>
        
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="max-h-96 overflow-y-auto">
        <table class="w-full text-sm">
            <thead class="sticky top-0 bg-gray-50 z-10">
                <tr class="bg-gray-50 border-b border-gray-10 z-10">
                    
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-6 py-4">Nom et Prénom</th>
                    <th class="text-left text-xs  font-semibold text-gray-400 uppercase tracking-wider px-6 py-4">Email</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-6 py-4">Téléphone</th>
                    <th class="text-left text-xs   font-semibold text-gray-400  uppercase tracking-wider px-6 py-4 ">Statut</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-6 py-4">Date maintenance</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider  px-6    py-4 ">Licences</th>

                    <th colspan="2" class="text-center text-xs  font-semibold text-gray-400 uppercase tracking-wider px-6 py-4" >Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
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
                        @elseif($client->statut_paiement == 'en attente')
                            <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-semibold px-3 py-1 rounded-full border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-400 inline-block"></span>
                                En attente
                            </span>
                        @elseif($client->statut_paiement == 'en retard')
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
                    <td><a href=""
           class="flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Modifier
        </a></td>
            <td>
                <a href=""
           class="flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 font-semibold text-sm px-5 py-2.5 rounded-xl border border-red-200 shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            
        </a>
            </td>

                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

</div>