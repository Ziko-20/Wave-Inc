<div class="p-6 lg:p-12 mb-10 w-full min-h-screen bg-[#F8FAFC] dark:bg-gray-900">
    
    {{-- Header Section with Title & Year Filter --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 dark:text-gray-100 uppercase tracking-wide">
                {{ __('Analytique & Revenus') }}
            </h2>
            <p class="text-gray-500 text-sm mt-1">
                {{ __('Visualisez la santé financière et l\'état de vos clients.') }}
            </p>
        </div>
        
        <div class="relative min-w-[150px]">
            <select wire:model.live="selectedYear"
                class="block w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 
                       text-gray-700 dark:text-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 
                       font-medium py-3 px-4 cursor-pointer outline-none transition-all">
                @if(count($AnneeDisponibles) > 0)
                    @foreach($AnneeDisponibles as $year)
                        <option value="{{ $year }}">{{ __('Année') }} {{ $year }}</option>
                    @endforeach
                @else
                    <option value="{{ date('Y') }}">{{ __('Année') }} {{ date('Y') }}</option>
                @endif
            </select>
        </div>
    </div>

    {{-- ── KPIs Statut Clients ──────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        
        <!-- Carte: Total Clients -->
        <div class="bg-gradient-to-br from-[#439670] to-[#2d7a58]
                    rounded-3xl px-6 py-5 shadow-lg shadow-[#439670]/30
                    flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform">
            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-white/10 group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-md">
                    <span class="material-symbols-outlined text-white text-[24px]">group</span>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-white/80 text-sm font-medium tracking-wide mb-1">{{ __('Total Clients') }}</p>
                <h3 class="text-3xl font-extrabold text-white">{{ $totalClients }}</h3>
            </div>
        </div>

        <!-- Carte: Clients Actifs -->
        <div class="bg-gradient-to-br from-[#10b981] to-[#047857]
                    rounded-3xl px-6 py-5 shadow-lg shadow-[#10b981]/30
                    flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform">
            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-white/10 group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-md">
                    <span class="material-symbols-outlined text-white text-[24px]">verified_user</span>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-white/80 text-sm font-medium tracking-wide mb-1">{{ __('Actifs (Payés)') }}</p>
                <h3 class="text-3xl font-extrabold text-white">{{ $clientsPayes }}</h3>
            </div>
        </div>

        <!-- Carte: Clients en Retard -->
        <div class="bg-gradient-to-br from-[#f43f5e] to-[#be123c]
                    rounded-3xl px-6 py-5 shadow-lg shadow-[#f43f5e]/30
                    flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform">
            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-white/10 group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-md">
                    <span class="material-symbols-outlined text-white text-[24px]">warning</span>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-white/80 text-sm font-medium tracking-wide mb-1">{{ __('En Retard') }}</p>
                <h3 class="text-3xl font-extrabold text-white">{{ $clientsEnRetard }}</h3>
            </div>
        </div>

        <!-- Carte: MRR (Revenu Mensuel) -->
        <div class="bg-gradient-to-br from-[#3b82f6] to-[#1d4ed8]
                    rounded-3xl px-6 py-5 shadow-lg shadow-[#3b82f6]/30
                    flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform">
            <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-white/10 group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-md">
                    <span class="material-symbols-outlined text-white text-[24px]">payments</span>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-white/80 text-sm font-medium tracking-wide mb-1">{{ __('MRR (Mois en cours)') }}</p>
                <h3 class="text-3xl font-extrabold text-white">{{ number_format($mrr, 2, ',', ' ') }} MAD</h3>
            </div>
        </div>

    </div>

    {{-- ── Chart Revenus ─────────────────────────────────────────────── --}}
    <div class="w-full bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 lg:p-8 relative">
        
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Évolution des revenus') }}</h3>
                <p class="text-sm text-gray-400 mt-1">{{ __('Performance financière de vos abonnements en') }} {{ $selectedYear }}.</p>
            </div>
            
            <div class="hidden sm:flex items-center gap-2">
                <span class="h-3 w-3 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                <span class="text-sm text-gray-500 font-medium tracking-wide uppercase">{{ __('Revenus confirmés') }}</span>
            </div>
        </div>

        <div class="relative h-[400px] w-full" wire:key="chart-container-{{ $selectedYear }}">
            <div class="w-full h-full" x-data="{
                chart: null,
                init() {
                    let ctx = this.$refs.canvas.getContext('2d');
                    
                    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.5)'); // Emerald 500
                    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

                    this.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['{{ __('Janvier') }}', '{{ __('Février') }}', '{{ __('Mars') }}', '{{ __('Avril') }}', '{{ __('Mai') }}', '{{ __('Juin') }}', '{{ __('Juillet') }}', '{{ __('Août') }}', '{{ __('Septembre') }}', '{{ __('Octobre') }}', '{{ __('Novembre') }}', '{{ __('Décembre') }}'],
                            datasets: [{
                                label: '{{ __('Revenus (MAD)') }}',
                                data: {{ json_encode($dataPayments) }},
                                backgroundColor: gradient,
                                borderColor: '#10b981',
                                borderWidth: 3,
                                pointBackgroundColor: '#ffffff',
                                pointBorderColor: '#10b981',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                    titleFont: { size: 14, family: 'Figtree, sans-serif' },
                                    bodyFont: { size: 14, family: 'Figtree, sans-serif' },
                                    padding: 12,
                                    cornerRadius: 8,
                                    displayColors: false,
                                    callbacks: {
                                        label: function(context) {
                                            return context.parsed.y + ' MAD';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(156, 163, 175, 0.1)',
                                        drawBorder: false,
                                    },
                                    ticks: {
                                        color: '#9ca3af',
                                        font: { family: 'Figtree, sans-serif' },
                                        callback: (val) => val + ' MAD'
                                    }
                                },
                                x: {
                                    grid: { display: false, drawBorder: false },
                                    ticks: {
                                        color: '#9ca3af',
                                        font: { family: 'Figtree, sans-serif' }
                                    }
                                }
                            }
                        }
                    });
                }
            }">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>
        
    </div>

</div>