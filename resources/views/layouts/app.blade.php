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
    
    <!-- C'est cette ligne qui fait apparaître les icônes -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[#F8FAFC] dark:bg-gray-900">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 ">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{-- @yield('content') --}}
                {{ $slot }}
            </main>
        </div>
    </body>
</html>


<script>
   
   
 function CopieData(button,idElement){

     const copyIcon = button.querySelector('.copyIcon');
    const copiedIcon = button.querySelector('.copiedIcon');

    // 2. Récupère le texte de l'élément cible (telephone ou email)
    const contenu = document.getElementById(idElement).textContent.trim();


    navigator.clipboard.writeText(contenu).then(()=>{
        copyIcon.classList.add('hidden');
        copiedIcon.classList.remove('hidden');

        setTimeout(() => {
            copyIcon.classList.remove('hidden');
            copiedIcon.classList.add('hidden');
        }, 2000);
    });


    
        
         /* alert('Vous avez copier le numero de telephone du client') ; */
    }
    
    
   /*  function CopierEmail(){
        let contenu=document.getElementById('mail').textContent.trim();
        navigator.clipboard.writeText(contenu);
        alert('Vous avez copier l\'adresse email du client')
    } */

</script>
