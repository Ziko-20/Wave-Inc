<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- Header Section --}}
        
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-200 flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-red-500">error</span>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-bold text-3xl sm:text-4xl flex items-center justify-center shadow-lg shadow-indigo-200">
                    {{ strtoupper(substr($client->nom, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">{{ __('Bonjour') }}, {{ $client->nom }}</h1>
                    <p class="text-slate-500 mt-1 text-sm sm:text-base">{{ __('Bienvenue sur votre espace client sécurisé.') }}</p>
                </div>
            </div>
            
            <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 sm:px-6 py-4 flex flex-col sm:items-end w-full sm:w-auto">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Statut Abonnement') }}</span>
                @if($client->statut_paiement == 'payé')
                    <span class="mt-1 flex items-center gap-1.5 text-emerald-600 font-bold text-lg">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> {{ __('statusPaye') }}
                    </span>
                @elseif($client->statut_paiement == 'en_attente')
                    <span class="mt-1 flex items-center gap-1.5 text-amber-500 font-bold text-lg">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span> {{ __('statusEnattente') }}
                    </span>
                @else
                    <span class="mt-1 flex items-center gap-1.5 text-red-500 font-bold text-lg">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> {{ __('statusEnretard') }}
                    </span>
                @endif
                <div class="mt-2 text-xs text-slate-400 font-medium">
                    {{ __('Date de maintenance') }} : <span class="text-slate-600">{{ \Carbon\Carbon::parse($client->date_maintenance)->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            
            {{-- Licences Section --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                    <span class="material-symbols-outlined text-indigo-500 border border-indigo-200 bg-white rounded-lg p-1.5">vpn_key</span>
                    <h2 class="text-lg font-bold text-slate-800">{{ __('Mes Licences Actives') }}</h2>
                    <span class="ml-auto bg-indigo-100 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ $licenses->count() }}</span>
                </div>
                <div class="overflow-x-auto flex-1 p-2">
                    @if($licenses->isEmpty())
                        <div class="flex flex-col items-center justify-center p-10 text-slate-400">
                            <span class="material-symbols-outlined text-5xl mb-2 opacity-50">block</span>
                            <p>{{ __('Aucune licence assignée.') }}</p>
                        </div>
                    @else
                        <table class="w-full text-left border-separate border-spacing-y-2 px-4">
                            <thead>
                                <tr class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    <th class="px-4 py-2">{{ __('Licence') }}</th>
                                    <th class="px-4 py-2">{{ __('Quantité') }}</th>
                                    <th class="px-4 py-2">{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($licenses as $lic)
                                    <tr class="bg-indigo-50/30 hover:bg-indigo-50/80 transition-colors rounded-xl">
                                        <td class="px-4 py-3 font-medium text-slate-700 rounded-l-xl">{{ $lic->nom }}</td>
                                        <td class="px-4 py-3 text-slate-600">
                                            <span class="inline-flex items-center justify-center bg-white border border-slate-200 text-slate-700 text-xs font-bold w-6 h-6 rounded-md shadow-sm">
                                                {{ $lic->quantite_disponible }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-slate-500 text-sm rounded-r-xl">{{ \Carbon\Carbon::parse($lic->date_assignation)->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            {{-- Paiements Section --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-emerald-500 border border-emerald-200 bg-white rounded-lg p-1.5">receipt_long</span>
                        <h2 class="text-lg font-bold text-slate-800">{{ __('Historique des Paiements') }}</h2>
                    </div>
                    @if($payments->isNotEmpty())
                    <button wire:click="exportMyPaymentsPdf" class="flex items-center justify-center gap-2 text-sm font-semibold text-indigo-600 bg-indigo-50 border border-indigo-100 hover:bg-indigo-600 hover:text-white px-4 py-2 rounded-lg transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">download</span> PDF
                    </button>
                    @endif
                </div>
                <div class="overflow-x-auto flex-1 p-2">
                    @if($payments->isEmpty())
                        <div class="flex flex-col items-center justify-center p-10 text-slate-400">
                            <span class="material-symbols-outlined text-5xl mb-2 opacity-50">receipt_long</span>
                            <p>{{ __('Aucun paiement enregistré.') }}</p>
                        </div>
                    @else
                        <table class="w-full text-left border-separate border-spacing-y-2 px-4">
                            <thead>
                                <tr class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    <th class="px-4 py-2">{{ __('Montant') }}</th>
                                    <th class="px-4 py-2">{{ __('Date') }}</th>
                                    <th class="px-4 py-2">{{ __('Statut') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr class="bg-slate-50 hover:bg-slate-100 transition-colors rounded-xl">
                                        <td class="px-4 py-3 font-semibold text-slate-800 rounded-l-xl">{{ number_format($payment->montant, 2, ',', ' ') }} MAD</td>
                                        <td class="px-4 py-3 text-slate-500 text-sm">{{ \Carbon\Carbon::parse($payment->date_payment)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 rounded-r-xl">
                                            @if($payment->status_payment == 'payé')
                                                <span class="inline-flex items-center bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded-md">
                                                    {{ __('statusPaye') }}
                                                </span>
                                            @elseif($payment->status_payment == 'en_attente')
                                                <span class="inline-flex items-center bg-amber-100 text-amber-700 text-xs font-bold px-2 py-1 rounded-md">
                                                    {{ __('statusEnattente') }}
                                                </span>
                                            @elseif($payment->status_payment == 'en_retard')
                                                <span class="inline-flex items-center bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded-md">
                                                    {{ __('statusEnretard') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
