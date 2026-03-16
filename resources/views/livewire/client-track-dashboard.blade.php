<div class="min-h-screen bg-gradient-to-br from-[#eef0f8] via-white to-[#e8f4ef] px-6 py-10 lg:px-12">
    

   
    <div class="max-w-4xl mx-auto text-center mb-14">

        
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                     tracking-widest uppercase bg-[#22419A]/10 text-[#22419A] mb-4">
            <span class="w-1.5 h-1.5 rounded-full bg-[#439670] animate-pulse"></span>
            Back-office
        </span>

        <h1 class="text-5xl font-extrabold text-gray-900 leading-tight tracking-tight">
            {{ __('BigTitleDash') }}
        </h1>

        <p class="mt-2 text-2xl font-semibold">
            <span class="text-[#439670]">{{ __('Client') }}</span>
            <span class="text-[#22419A]"> {{ __('smallerTitle') }}</span>
        </p>

        <p class="mt-3 text-sm text-gray-400 max-w-md mx-auto leading-relaxed">
            {{ __('pTitleDash') }}
        </p>

        
        <div class="mx-auto mt-6 flex items-center justify-center gap-2 w-24">
            <span class="h-px flex-1 bg-[#22419A]/20"></span>
            <span class="w-2 h-2 rounded-full bg-[#439670]"></span>
            <span class="h-px flex-1 bg-[#439670]/20"></span>
        </div>
    </div>

    
    <div class="max-w-2xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">

        
        <div class="group bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-6
                    shadow-sm hover:-translate-y-2 hover:scale-[1.015]
                    hover:shadow-[0_20px_40px_-8px_rgba(34,65,154,0.15)]
                    hover:border-[#22419A]/15 transition-all duration-300 ease-out">

            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#EDEEF5] to-[#d8dcef]
                        flex items-center justify-center mb-5 shadow-inner">
                <span class="material-symbols-outlined text-[#22419A] text-[26px]">person</span>
            </div>

            <a href="/clients"
               class="text-[#22419A] inline-flex items-center gap-1 text-lg font-bold
                      hover:gap-2.5 transition-all duration-200">
                {{ __('card1title') }}
                <span class="material-symbols-outlined text-[20px] transition-transform
                             duration-200 group-hover:translate-x-1">arrow_right_alt</span>
            </a>

            <p class="mt-2 text-gray-400 text-sm leading-relaxed">
                {{ __('card1Description') }}
            </p>

            <div class="mt-5 h-[2px] w-8 rounded-full bg-[#22419A]/25
                        group-hover:w-16 transition-all duration-300"></div>
        </div>

        
        

         
        <div class="group bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-6
                    shadow-sm hover:-translate-y-2 hover:scale-[1.015]
                    hover:shadow-[0_20px_40px_-8px_rgba(34,65,154,0.15)]
                    hover:border-[#22419A]/15 transition-all duration-300 ease-out">

            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#EDEEF5] to-[#d8dcef]
                        flex items-center justify-center mb-5 shadow-inner">
                <span class="material-symbols-outlined text-[#22419A] text-[26px]">bar_chart_4_bars</span>
            </div>

            <a href="/chart"
               class="text-[#22419A] inline-flex items-center gap-1 text-lg font-bold
                      hover:gap-2.5 transition-all duration-200">
                {{ __('card3title') }}
                <span class="material-symbols-outlined text-[20px] transition-transform
                             duration-200 group-hover:translate-x-1">arrow_right_alt</span>
            </a>

            <p class="mt-2 text-gray-400 text-sm leading-relaxed">
                {{ __('card3Description') }}
            </p>

            <div class="mt-5 h-[2px] w-8 rounded-full bg-[#22419A]/25
                        group-hover:w-16 transition-all duration-300"></div>
        </div>

    </div>


    <div class="max-w-sm mx-auto mt-12">
        <div class="bg-gradient-to-br from-[#439670] via-[#2d7a58] to-[#22419A]
                    rounded-3xl px-8 py-5 shadow-xl shadow-[#439670]/30
                    flex items-center justify-between gap-4 overflow-hidden relative">

           
            <div class="absolute -top-8 -right-8 w-32 h-32 rounded-full bg-white/5"></div>
            <div class="absolute -bottom-6 -left-4 w-24 h-24 rounded-full bg-white/5"></div>

            <div class="relative z-10">
                <p class="text-white/70 text-xs font-semibold tracking-widest uppercase mb-1">
                    {{ __('ClientsNumber') }}
                </p>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-white/80 text-[22px]">group</span>
                    <span class="text-4xl font-extrabold text-white leading-none">
                        {{ $totalClients }}
                    </span>
                </div>
            </div>

            <div class="relative z-10 w-14 h-14 rounded-full border-2 border-white/20
                        flex items-center justify-center bg-white/10 backdrop-blur-sm">
                <span class="material-symbols-outlined text-white text-[26px]">person_check</span>
            </div>

        </div>
    </div>

</div>