<div>
    {{-- ── KPIs Statut Clients ──────────────────────────────────────── --}}
 <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 max-w-3xl mx-auto">

    {{-- Payés --}}
    <div class="bg-white border border-emerald-100 rounded-2xl px-5 py-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500 rounded-l-2xl"></div>
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
            </div>
            <span class="text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                {{ $totalClients > 0 ? round($clientsPayes / $totalClients * 100) : 0 }}%
            </span>
        </div>
        <p class="text-[28px] font-medium text-emerald-700 leading-none mb-0.5">{{ $clientsPayes }}</p>
        <p class="text-xs text-gray-400 mb-2.5">Clients payés</p>
        <div class="h-1 rounded-full bg-gray-100 overflow-hidden">
            <div class="h-full rounded-full bg-emerald-500 transition-all duration-500"
                 style="width: {{ $totalClients > 0 ? round($clientsPayes / $totalClients * 100) : 0 }}%">
            </div>
        </div>
    </div>

    {{-- En attente --}}
    <div class="bg-white border border-amber-100 rounded-2xl px-5 py-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-amber-400 rounded-l-2xl"></div>
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-amber-600 text-[18px]">schedule</span>
            </div>
            <span class="text-[11px] font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
                {{ $totalClients > 0 ? round($clientsEnAttente / $totalClients * 100) : 0 }}%
            </span>
        </div>
        <p class="text-[28px] font-medium text-amber-700 leading-none mb-0.5">{{ $clientsEnAttente }}</p>
        <p class="text-xs text-gray-400 mb-2.5">En attente</p>
        <div class="h-1 rounded-full bg-gray-100 overflow-hidden">
            <div class="h-full rounded-full bg-amber-400 transition-all duration-500"
                 style="width: {{ $totalClients > 0 ? round($clientsEnAttente / $totalClients * 100) : 0 }}%">
            </div>
        </div>
    </div>

    {{-- En retard --}}
    <div class="bg-white border border-red-100 rounded-2xl px-5 py-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-red-500 rounded-l-2xl"></div>
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-red-500 text-[18px]">cancel</span>
            </div>
            <span class="text-[11px] font-medium text-red-700 bg-red-50 px-2 py-0.5 rounded-full">
                {{ $totalClients > 0 ? round($clientsEnRetard / $totalClients * 100) : 0 }}%
            </span>
        </div>
        <p class="text-[28px] font-medium text-red-600 leading-none mb-0.5">{{ $clientsEnRetard }}</p>
        <p class="text-xs text-gray-400 mb-2.5">En retard</p>
        <div class="h-1 rounded-full bg-gray-100 overflow-hidden">
            <div class="h-full rounded-full bg-red-400 transition-all duration-500"
                 style="width: {{ $totalClients > 0 ? round($clientsEnRetard / $totalClients * 100) : 0 }}%">
            </div>
        </div>
    </div>

</div>
    {{-- ── Chart Revenus ─────────────────────────────────────────────── --}}
    <div class="mx-auto w-[80%] h-auto" wire:key="chart-{{ $selectedYear }}">
        <canvas id="TTPaiments"></canvas>
        @script
        <script>
            new Chart(TTPaiments, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
                    datasets: [{
                        label: 'Revenus (MAD)',
                        data: @json($dataPayments),
                        backgroundColor: 'rgba(4, 120, 87)',
                        borderColor: 'rgba(22, 101, 52)',
                        borderWidth: 2,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: (val) => val + ' MAD' }
                        }
                    }
                }
            });
        </script>
        @endscript
    </div>

</div>