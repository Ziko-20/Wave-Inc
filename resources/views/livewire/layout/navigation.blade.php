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

<aside 
    class="bg-white border-r border-gray-100 shadow-[4px_0_24px_rgba(0,0,0,0.02)] md:shadow-none h-screen flex flex-col z-50 transition-all duration-300 relative fixed md:relative"
    :class="{
        'w-64 translate-x-0': sidebarOpen,
        'w-20 -translate-x-full md:translate-x-0': !sidebarOpen,
        'translate-x-0 absolute': mobileMenuOpen,
        '-translate-x-full': !mobileMenuOpen && window.innerWidth < 768
    }">
    
    <!-- Header / Logo Area -->
    <div class="h-32 flex items-center justify-center border-b border-gray-50 relative shrink-0 pt-2">
        <!-- Expanded Logo -->
        <a href="/" class="transition-all duration-300 overflow-hidden flex justify-center items-center h-full w-full" x-show="sidebarOpen" x-transition.opacity>
            <img src="{{ asset('imgs/LogoLatestVersion.png') }}" alt="Logo" class="w-[95%] h-full object-contain scale-150 drop-shadow-sm" />
        </a>

        <!-- Desktop Toggle Collapse Button -->
        <button @click="sidebarOpen = !sidebarOpen" class="hidden md:flex absolute -right-3 top-6 bg-white border border-gray-200 rounded-full w-7 h-7 items-center justify-center text-gray-500 hover:text-indigo-600 hover:border-indigo-300 shadow-sm z-50 transition-colors cursor-pointer">
            <span class="material-symbols-outlined text-[18px]" x-text="sidebarOpen ? 'chevron_left' : 'chevron_right'">chevron_left</span>
        </button>
        
        <!-- Mobile Close Button -->
        <button @click="mobileMenuOpen = false" class="md:hidden absolute right-4 top-6 text-gray-400 hover:text-red-500 transition-colors p-2 bg-gray-50 rounded-full">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-x-hidden overflow-y-auto py-6 flex flex-col gap-3 px-3 custom-scrollbar">

        @unlessrole('client')
        <!-- Clients Link -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-4 bg-[#eff6ff] rounded-md h-[40px] text-[#1e3a8a] font-bold border hover:bg-[#355cc6] hover:text-white gap-2 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
           :class="!sidebarOpen ? 'justify-center px-0' : 'w-full'"
           title="{{ __('Gestion Clients') }}"
           wire:navigate>
          <span class="material-symbols-outlined text-[20px]">groups</span>
          <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">{{ __('Gestion Clients') }}</span>
        </a>
        @else
        <!-- Mon Espace Link -->
        <a href="{{ route('client.portal') }}"
           class="flex items-center px-4 bg-[#eff6ff] rounded-md h-[40px] text-[#1e3a8a] font-bold border hover:bg-[#355cc6] hover:text-white gap-2 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
           :class="!sidebarOpen ? 'justify-center px-0' : 'w-full'"
           title="{{ __('Mon Espace') }}"
           wire:navigate>
          <span class="material-symbols-outlined text-[20px]">dashboard</span>
          <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">{{ __('Mon Espace') }}</span>
        </a>

        <!-- Boutique Link -->
        <a href="{{ route('license.shop') }}"
           class="flex items-center px-4 bg-[#eff6ff] rounded-md h-[40px] text-[#1e3a8a] font-bold border hover:bg-[#355cc6] hover:text-white gap-2 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
           :class="!sidebarOpen ? 'justify-center px-0' : 'w-full'"
           title="{{ __('Boutique') }}"
           wire:navigate>
          <span class="material-symbols-outlined text-[20px]">storefront</span>
          <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">{{ __('Boutique') }}</span>
        </a>
        @endunlessrole

        @hasrole('admin')
        <!-- Managers Link -->
        <a href="{{ route('managers.index') }}"
           class="flex items-center px-4 bg-[#fef3c7] rounded-md h-[40px] text-[#92400e] font-bold border hover:bg-[#d97706] hover:text-white gap-2 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
           :class="!sidebarOpen ? 'justify-center px-0' : 'w-full'"
           title="{{ __('Managers') }}"
           wire:navigate>
          <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
          <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">{{ __('Managers') }}</span>
        </a>
        @endhasrole

    </div>

    <!-- Bottom Actions Area -->
    <div class="border-t border-gray-100 p-4 flex flex-col gap-3 shrink-0 bg-white">
        
        <!-- Profile Link -->
        <a href="{{ route('profile') }}"
           class="flex items-center gap-2 font-bold border h-[40px] rounded-md px-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 hover:bg-gray-50 text-gray-700"
           :class="!sidebarOpen ? 'justify-center px-0 border-transparent' : 'w-full'"
           title="{{ __('Profile') }}"
           wire:navigate>
          <span class="material-symbols-outlined text-[20px]">account_circle</span>
          <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">{{ __('Profile') }}</span>
        </a>

        <!-- Language Selector -->
        <div class="flex items-center border rounded-md px-2 h-[40px] transition-all hover:bg-gray-50 relative"
             :class="!sidebarOpen ? 'justify-center border-none' : 'w-full'"
             title="Langue">
            <span class="material-symbols-outlined text-[20px]" :class="!sidebarOpen ? 'text-gray-500' : 'text-gray-700'">language</span>
            
            <select class="lang rounded-md h-full font-bold border-none flex-1 bg-transparent cursor-pointer text-gray-700 pl-2 focus:ring-0"
                    x-show="sidebarOpen"
                    x-on:change="window.location.href = $event.target.value">
                <option value="{{ route('language.switch', 'fr') }}" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>FR</option>
                <option value="{{ route('language.switch', 'en') }}" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
            </select>

            <!-- Collapsed invisible selector -->
            <select x-show="!sidebarOpen" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" x-on:change="window.location.href = $event.target.value">
                <option value="{{ route('language.switch', 'fr') }}" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>FR</option>
                <option value="{{ route('language.switch', 'en') }}" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
            </select>
        </div>

        <!-- Logout Button -->
        <button wire:click="logout"
                class="bg-[#FEF2F2] text-red-600 border flex items-center gap-2 font-bold h-[40px] rounded-md px-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                :class="!sidebarOpen ? 'justify-center px-0 border-transparent' : 'w-full border-red-200'"
                title="{{ __('Log Out') }}">
            <span class="material-symbols-outlined text-[20px]">logout</span>
            <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">{{ __('Log Out') }}</span>
        </button>

    </div>
</aside>