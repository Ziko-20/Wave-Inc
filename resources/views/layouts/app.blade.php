<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-[#F8FAFC] dark:bg-gray-900" x-data="{ sidebarOpen: false, mobileMenuOpen: false }">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <livewire:layout.navigation />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden transition-all duration-300 relative z-0">
            
            <!-- Mobile Header (Visible only on small screens) -->
            <header class="md:hidden bg-white shadow-sm flex items-center justify-between p-4 z-20 relative border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <button @click="mobileMenuOpen = true" class="p-2 -ml-2 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors">
                        <span class="material-symbols-outlined text-[26px]">menu_open</span>
                    </button>
                    <img src="{{ asset('imgs/LogoLatestVersion.png') }}" alt="Logo" class="h-8 object-contain" />
                </div>
            </header>

            <!-- Desktop Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow-sm hidden md:block z-10 relative">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 relative z-0 flex flex-col">
                <div class="flex-1">
                    {{ $slot }}
                </div>
                <!-- Footer now sits at the bottom of the scrollable content -->
                <livewire:layout.footer />
            </main>
        </div>

    </div>

    <!-- Mobile Overlay Layer -->
    <div class="md:hidden">
        <div 
            x-show="mobileMenuOpen" 
            @click="mobileMenuOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm transition-opacity"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;">
        </div>
    </div>

    <!-- Script for copy functionalities -->
    <script>
        function CopieData(button,idElement){
            const copyIcon = button.querySelector('.copyIcon');
            const copiedIcon = button.querySelector('.copiedIcon');
            const contenu = document.getElementById(idElement).textContent.trim();

            navigator.clipboard.writeText(contenu).then(()=>{
                copyIcon.classList.add('hidden');
                copiedIcon.classList.remove('hidden');

                setTimeout(() => {
                    copyIcon.classList.remove('hidden');
                    copiedIcon.classList.add('hidden');
                }, 2000);
            });
        }
    </script>
</body>
</html>
