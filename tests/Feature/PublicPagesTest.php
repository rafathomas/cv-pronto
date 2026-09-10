<?php

it('renderiza a landing page', function () {
    $this->get('/')->assertOk()->assertSee('CVPronto');
});

it('renderiza a política de privacidade', function () {
    $this->get(route('legal.privacy'))->assertOk()->assertSee('LGPD');
});

it('renderiza os termos de uso', function () {
    $this->get(route('legal.terms'))->assertOk()->assertSee('Termos de Uso');
});
