<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Confirme seu e-mail</h1>
        <p class="text-sm text-gray-500 mt-2">
            Obrigado por se cadastrar! Antes de começar, clique no link de confirmação que acabamos de enviar para o seu e-mail. Se não recebeu, podemos enviar novamente.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <x-alert type="success" class="mb-4 font-medium">
            Um novo link de confirmação foi enviado para o e-mail que você cadastrou.
        </x-alert>
    @endif

    <div class="flex items-center justify-between gap-4">
        <x-primary-button wire:click="sendVerification">
            Reenviar e-mail de confirmação
        </x-primary-button>

        <button wire:click="logout" type="submit" class="text-sm font-semibold text-gray-500 hover:text-gray-800">
            Sair
        </button>
    </div>
</div>
