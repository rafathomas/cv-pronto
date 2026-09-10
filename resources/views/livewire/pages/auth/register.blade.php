<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Crie sua conta grátis</h1>
        <p class="text-sm text-gray-500 mt-1">Comece a analisar seu currículo em menos de 1 minuto.</p>
    </div>

    <form wire:submit="register" class="space-y-5">
        <div>
            <x-input-label for="name" value="Nome completo" />
            <x-text-input wire:model="name" id="name" class="block mt-1.5 w-full" type="text" name="name" required autofocus autocomplete="name" placeholder="Seu nome completo" />
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input wire:model="email" id="email" class="block mt-1.5 w-full" type="email" name="email" required autocomplete="username" placeholder="voce@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <x-input-label for="password" value="Senha" />
            <x-text-input wire:model="password" id="password" class="block mt-1.5 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmar senha" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1.5 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Repita a senha" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <x-primary-button class="w-full">
            Criar conta e continuar
        </x-primary-button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Já tem conta?
        <a class="font-semibold text-accent-dark hover:underline" href="{{ route('login') }}" wire:navigate>
            Entrar
        </a>
    </p>
</div>
