<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>
{{-- container --}}
<nav class="p-2 bg-white h-16 flex items-center justify-between w-full">

  {{-- logo --}}

  <div class="flex items-center gap-4">

    <img src="{{ asset('imgs/LogoLatestVersion.png') }}" alt="Logo" class="h-44" />



    <a href="{{ route('dashboard') }}"
       class="flex items-center px-4 bg-[#eff6ff] rounded-md h-[30px] text-[#1e3a8a] font-bold border hover:bg-[#355cc6] hover:text-white gap-1.5 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 "
       wire:navigate>
      <span class="material-symbols-outlined">
        home
    </span>
      {{ __('ACCCEUIL') }}
    </a>

    <a href="{{ route('profile') }}"

       class="flex items-center gap-1.5 font-bold border h-[30px] rounded-md px-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
      <span class="material-symbols-outlined">
        account_circle
    </span>
     
      {{ __('Profile') }}
    </a>

  </div>

  {{-- langue exitt --}}
  <div class="flex items-center gap-3">
    <div class="flex items-center">
      <span class="material-symbols-outlined">language</span>

      <select class="lang rounded-md h-[36px] font-bold transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 border-none"
              x-on:change="window.location.href = $event.target.value">

        <option value="{{ route('language.switch', 'fr') }}" 
        {{ app()->getLocale() == 'fr' ? 'selected' : ''}}>FR</option>
        <option value="{{ route('language.switch', 'en') }}" 
        {{ app()->getLocale() == 'en' ? 'selected' : ''}}>EN</option>
      </select>
    </div>

    <button wire:click="logout"

       class="bg-[#FEF2F2] text-red-600 border flex items-center gap-1.5 font-bold h-[30px] rounded-md px-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
      <span class="material-symbols-outlined">
        logout
    </span>
      {{ __('Log Out') }}

    </button>

  </div>







</nav>
