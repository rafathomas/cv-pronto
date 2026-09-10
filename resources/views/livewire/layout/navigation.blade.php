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

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 font-bold text-gray-900">
                        <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand to-accent flex items-center justify-center text-white text-xs">CV</span>
                        <span class="hidden sm:inline">CVPronto</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('resume.builder')" :active="request()->routeIs('resume.builder')" wire:navigate>
                        Meu currículo
                    </x-nav-link>
                    <x-nav-link :href="route('resume.analyze')" :active="request()->routeIs('resume.analyze')" wire:navigate>
                        Analisar currículo
                    </x-nav-link>
                    <x-nav-link :href="route('job.analyze')" :active="request()->routeIs('job.analyze')" wire:navigate>
                        Analisar vaga
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                @can('admin')
                    <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-800 border border-gray-200 rounded-full px-3 py-1.5 transition">
                        Painel admin
                    </a>
                @endcan

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 rounded-full focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                            <span class="w-8 h-8 rounded-full bg-brand/10 text-brand font-semibold text-sm flex items-center justify-center">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            Perfil
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('billing.plans')" wire:navigate>
                            Planos
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                Sair
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('resume.builder')" :active="request()->routeIs('resume.builder')" wire:navigate>
                Meu currículo
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('resume.analyze')" :active="request()->routeIs('resume.analyze')" wire:navigate>
                Analisar currículo
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('job.analyze')" :active="request()->routeIs('job.analyze')" wire:navigate>
                Analisar vaga
            </x-responsive-nav-link>
            @can('admin')
                <x-responsive-nav-link :href="route('admin.dashboard')">
                    Painel admin
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center gap-3 px-4">
                <span class="w-9 h-9 rounded-full bg-brand/10 text-brand font-semibold text-sm flex items-center justify-center shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <div>
                    <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    Perfil
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('billing.plans')" wire:navigate>
                    Planos
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        Sair
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
