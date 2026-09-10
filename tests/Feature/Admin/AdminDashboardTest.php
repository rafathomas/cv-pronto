<?php

use App\Models\User;

it('impede um usuário comum de acessar o painel administrativo', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
});

it('permite que um administrador acesse o painel', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $other = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Painel Administrativo')
        ->assertSee($other->email);
});
