<div>
    {{-- 
    <div>
        <select wire:model.live="selectedYear" class="rounded border p-2">
        @foreach($AnneeDisponibles as $year)
            <option value="{{ $year }}">{{ $year }}</option>
        @endforeach
    </select>
    </div> --}}

    <div class="mx-auto w-[80%] h-auto " wire:key="chart-{{ $selectedYear }}">

    
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
console.log(@json($dataPayments));

    </script>
    @endscript

</div>
   {{-- <div class="w-72 mx-auto">
     <canvas id="GraphStatusClients"></canvas>
    @script
    <script>
        new Chart(GraphStatusClients, {
    type: 'doughnut',  
    data: {
        labels: ['payé', 'en_attente', 'en_retard'],
        datasets: [{
            data: @json($dataa) ,
            backgroundColor: ['#439670', '#D57028', '#CC433A'],
            borderColor: '#1f2937',
            borderWidth: 3,
            hoverOffset: 10,
        }]
    },
    options: {
        cutout: '65%', 
        plugins: {
            legend: { position: 'right' },
            tooltip: {
                callbacks: {
                    label: (GraphStatusClients) => ` ${GraphStatusClients.label}: ${GraphStatusClients.parsed}%`
                }
            }
        }
    }
});
    </script>
    @endscript 
        </div>--}}
</div>