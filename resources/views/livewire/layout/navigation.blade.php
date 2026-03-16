<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav class="p-2 bg-white w-full shadow-sm" x-data="{ open: false }">

  {{-- Desktop navbar --}}
  <div class="h-16 flex items-center justify-between w-full">

    {{-- Logo + liens --}}
    <div class="flex items-center gap-4">

      <img src="{{ asset('imgs/LogoLatestVersion.png') }}" alt="Logo" class="h-44" />

      {{-- Liens visibles uniquement sur desktop --}}
      <div class="hidden md:flex items-center gap-4">
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-4 bg-[#eff6ff] rounded-md h-[30px] text-[#1e3a8a] font-bold border hover:bg-[#355cc6] hover:text-white gap-1.5 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
           wire:navigate>
          <span class="material-symbols-outlined">home</span>
          {{ __('ACCCEUIL') }}
        </a>

        <a href="{{ route('profile') }}"
           class="flex items-center gap-1.5 font-bold border h-[30px] rounded-md px-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
           wire:navigate>
          <span class="material-symbols-outlined">account_circle</span>
          {{ __('Profile') }}
        </a>
      </div>
    </div>
          @hasrole('admin')
<a href="{{ route('managers.index') }}"
   class="flex items-center px-4 bg-[#fef3c7] rounded-md h-[30px] text-[#92400e] font-bold border hover:bg-[#d97706] hover:text-white gap-1.5 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
   wire:navigate>
  <span class="material-symbols-outlined">manage_accounts</span>
  {{ __('Managers') }}
</a>
@endhasrole
    

    {{-- Langue + logout (desktop) --}}
    <div class="hidden md:flex items-center gap-3">
      <div class="flex items-center">
        <span class="material-symbols-outlined">language</span>
        <select class="lang rounded-md h-[36px] font-bold transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 border-none"
                x-on:change="window.location.href = $event.target.value">
          <option value="{{ route('language.switch', 'fr') }}" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>FR</option>
          <option value="{{ route('language.switch', 'en') }}" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
        </select>
      </div>

      <button wire:click="logout"
         class="bg-[#FEF2F2] text-red-600 border flex items-center gap-1.5 font-bold h-[30px] rounded-md px-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
        <span class="material-symbols-outlined">logout</span>
        {{ __('Log Out') }}
      </button>
    </div>

    {{-- Bouton hamburger (mobile) --}}
    <button class="md:hidden flex items-center p-2 rounded-md hover:bg-gray-100 transition"
            x-on:click="open = !open">
      <span class="material-symbols-outlined" x-text="open ? 'close' : 'menu'">menu</span>
    </button>

  </div>

  {{-- Menu mobile déroulant --}}
  <div class="md:hidden"
       x-show="open"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 -translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100 translate-y-0"
       x-transition:leave-end="opacity-0 -translate-y-2">

    <div class="flex flex-col gap-2 pb-3 pt-1 border-t mt-1">

      <a href="{{ route('dashboard') }}"
         class="flex items-center px-4 bg-[#eff6ff] rounded-md h-[36px] text-[#1e3a8a] font-bold border hover:bg-[#355cc6] hover:text-white gap-1.5 transition-all duration-200"
         wire:navigate x-on:click="open = false">
        <span class="material-symbols-outlined">home</span>
        {{ __('ACCCEUIL') }}
      </a>

      <a href="{{ route('profile') }}"
         class="flex items-center gap-1.5 font-bold border h-[36px] rounded-md px-4 transition-all duration-200"
         wire:navigate x-on:click="open = false">
        <span class="material-symbols-outlined">account_circle</span>
        {{ __('Profile') }}
      </a>
          @hasrole('admin')
<a href="{{ route('managers.index') }}"
   class="flex items-center px-4 bg-[#fef3c7] rounded-md h-[36px] text-[#92400e] font-bold border hover:bg-[#d97706] hover:text-white gap-1.5 transition-all duration-200"
   wire:navigate x-on:click="open = false">
  <span class="material-symbols-outlined">manage_accounts</span>
  {{ __('Managers') }}
</a>
@endhasrole

      {{-- Langue --}}
      <div class="flex items-center border rounded-md px-2 h-[36px]">
        <span class="material-symbols-outlined">language</span>
        <select class="lang rounded-md h-full font-bold border-none flex-1"
                x-on:change="window.location.href = $event.target.value">
          <option value="{{ route('language.switch', 'fr') }}" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>FR</option>
          <option value="{{ route('language.switch', 'en') }}" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
        </select>
      </div>

      <button wire:click="logout"
         class="bg-[#FEF2F2] text-red-600 border flex items-center gap-1.5 font-bold h-[36px] rounded-md px-4 transition-all duration-200">
        <span class="material-symbols-outlined">logout</span>
        {{ __('Log Out') }}
      </button>

    </div>
  </div>

</nav>