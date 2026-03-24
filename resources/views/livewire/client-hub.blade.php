<div class="min-h-[85vh] flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden bg-slate-50">
    
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl -z-10 mix-blend-multiply"></div>
    <div class="absolute bottom-10 right-1/4 w-96 h-96 bg-purple-400/20 rounded-full blur-3xl -z-10 mix-blend-multiply"></div>

    <div class="relative z-10 w-full max-w-4xl mx-auto text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800 tracking-tight mb-6">
            {{ __('Bienvenue') }}, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">{{ auth()->user()->name }}</span>
        </h1>
        <p class="text-xl text-slate-500 max-w-2xl mx-auto font-light">
            {{ __('Où souhaitez-vous vous rendre aujourd\'hui ?') }}
        </p>
    </div>

    <div class="relative z-10 w-full max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 px-4">
        
        {{-- Card: Mon Espace --}}
        <a href="{{ route('client.portal') }}" wire:navigate
           class="group relative bg-white/80 backdrop-blur-xl border border-white rounded-3xl p-10 hover:bg-white transition-all duration-500 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(59,130,246,0.15)] hover:-translate-y-2 flex flex-col items-center text-center overflow-hidden">
            
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -z-10"></div>
            
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-blue-200 mb-8 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-5xl">dashboard</span>
            </div>
            
            <h2 class="text-3xl font-bold text-slate-800 mb-4">{{ __('Mon Espace') }}</h2>
            <p class="text-slate-500 mb-8 flex-1 text-lg">
                {{ __('Consultez l\'état de vos abonnements, accédez à votre historique de paiements et téléchargez vos rapports.') }}
            </p>
            
            <div class="mt-auto flex items-center justify-center gap-2 text-indigo-600 font-bold group-hover:gap-4 transition-all bg-indigo-50 px-6 py-3 rounded-xl w-full">
                <span>{{ __('Accéder à mon espace') }}</span>
                <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </div>
        </a>

        {{-- Card: Boutique --}}
        <a href="{{ route('license.shop') }}" wire:navigate
           class="group relative bg-white/80 backdrop-blur-xl border border-white rounded-3xl p-10 hover:bg-white transition-all duration-500 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(168,85,247,0.15)] hover:-translate-y-2 flex flex-col items-center text-center overflow-hidden">
            
            <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-50 to-purple-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -z-10"></div>
            
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-purple-500 to-fuchsia-600 text-white flex items-center justify-center shadow-lg shadow-purple-200 mb-8 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-5xl">storefront</span>
            </div>
            
            <h2 class="text-3xl font-bold text-slate-800 mb-4">{{ __('Boutique WP') }}</h2>
            <p class="text-slate-500 mb-8 flex-1 text-lg">
                {{ __('Explorez notre catalogue exclusif pour acquérir de nouvelles licences et extensions WordPress.') }}
            </p>
            
            <div class="mt-auto flex items-center justify-center gap-2 text-purple-600 font-bold group-hover:gap-4 transition-all bg-purple-50 px-6 py-3 rounded-xl w-full">
                <span>{{ __('Visiter la boutique') }}</span>
                <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </div>
        </a>

    </div>
</div>
