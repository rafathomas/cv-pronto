<?php

it('renderiza as páginas públicas de SEO', function (string $slug) {
    $this->get('/'.$slug)->assertOk()->assertSee('CVPronto');
})->with([
    'criar-curriculo',
    'curriculo-com-ia',
    'curriculo-primeiro-emprego',
    'curriculo-estagio',
    'curriculo-programador',
    'curriculo-administrativo',
    'curriculo-vendedor',
]);

it('retorna 404 para uma página de SEO inexistente', function () {
    $this->get('/curriculo-astronauta')->assertNotFound();
});
