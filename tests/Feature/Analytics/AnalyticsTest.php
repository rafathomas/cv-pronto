<?php

use App\Domain\Analytics\Models\AnalyticsEvent;
use App\Models\User;
use Livewire\Volt\Volt;

it('registra um evento de landing_view ao visitar a home', function () {
    $this->get('/');

    expect(AnalyticsEvent::where('event', 'landing_view')->count())->toBe(1);
});

it('registra um evento de signup ao criar uma conta', function () {
    Volt::test('pages.auth.register')
        ->set('name', 'Rafael Souza')
        ->set('email', 'rafael@teste.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register');

    $user = User::where('email', 'rafael@teste.com')->firstOrFail();

    expect(AnalyticsEvent::where('event', 'signup')->where('user_id', $user->id)->count())->toBe(1);
});
