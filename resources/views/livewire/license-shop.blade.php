<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

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

        {{-- Boutique de Licences --}}
        <div>
            <div class="mb-4 flex items-center gap-3">
                <span class="material-symbols-outlined text-purple-500 border border-purple-200 bg-white rounded-lg p-1.5">storefront</span>
                <h2 class="text-2xl font-bold text-slate-800">{{ __('Acheter une licence WP') }}</h2>
            </div>
            <p class="text-slate-500 mb-8">{{ __('Choisissez la licence qui correspond le mieux à vos besoins.') }}</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($offers as $offer)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col hover:shadow-md transition-all hover:-translate-y-1 relative">
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-bold text-slate-800">{{ $offer->nom }}</h3>
                                <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-1 rounded-lg">WP</span>
                            </div>
                            <p class="text-sm text-slate-500 mb-5 min-h-[40px]">{{ $offer->description }}</p>
                            
                            <div class="bg-slate-50 rounded-xl p-4 mb-5 border border-slate-100">
                                <div class="text-2xl font-black text-slate-800">{{ number_format($offer->prix, 2) }} <span class="text-sm text-slate-500 font-semibold mb-1">MAD</span></div>
                            </div>
                            
                            <div class="flex items-center justify-between text-xs font-medium mb-6">
                                <span class="text-slate-400">{{ __('Stock disponible') }}</span>
                                <span class="bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-md font-bold">{{ $offer->quantite_disponible }}</span>
                            </div>
                        </div>
                        <button wire:click="buyLicense({{ $offer->id }})" class="w-full bg-[#0070ba] hover:bg-[#003087] text-white font-bold py-3 px-4 rounded-xl flex justify-center items-center gap-2 transition-colors shadow-sm shadow-[#0070ba]/30">
                            <span class="material-symbols-outlined text-[20px]">payments</span>
                            {{ __('Payer avec PayPal') }}
                        </button>
                    </div>
                @empty
                    <div class="col-span-full py-12 flex flex-col items-center justify-center text-slate-400 bg-white rounded-2xl border border-dashed border-slate-200">
                        <span class="material-symbols-outlined text-5xl mb-3 opacity-30">production_quantity_limits</span>
                        <p class="font-medium">{{ __('Aucune licence disponible à l\'achat pour le moment.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
