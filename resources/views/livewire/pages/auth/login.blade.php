<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Bem-vindo de volta</h1>
        <p class="text-sm text-gray-500 mt-1">Entre para continuar de onde parou.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1.5 w-full" type="email" name="email" required autofocus autocomplete="username" placeholder="voce@email.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1.5" />
        </div>

        <div>
            <x-input-label for="password" value="Senha" />
            <x-text-input wire:model="form.password" id="password" class="block mt-1.5 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="Sua senha" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center gap-2 text-sm text-gray-600">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-accent focus:ring-accent" name="remember">
                Lembrar de mim
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-accent-dark hover:underline" href="{{ route('password.request') }}" wire:navigate>
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <x-primary-button class="w-full">
            Entrar
        </x-primary-button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Ainda não tem conta?
        <a class="font-semibold text-accent-dark hover:underline" href="{{ route('register') }}" wire:navigate>
            Criar conta grátis
        </a>
    </p>
</div>
