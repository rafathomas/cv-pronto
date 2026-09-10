<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Confirme sua senha</h1>
        <p class="text-sm text-gray-500 mt-1">Esta é uma área protegida. Confirme sua senha antes de continuar.</p>
    </div>

    <form wire:submit="confirmPassword" class="space-y-5">
        <div>
            <x-input-label for="password" value="Senha" />

            <x-text-input wire:model="password"
                          id="password"
                          class="block mt-1.5 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" placeholder="Sua senha" />

            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <x-primary-button class="w-full">
            Confirmar
        </x-primary-button>
    </form>
</div>
