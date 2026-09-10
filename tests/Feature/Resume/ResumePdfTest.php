<?php

use App\Domain\Resume\Enums\ResumeTemplate;
use App\Domain\Resume\Models\Resume;
use App\Domain\Subscription\Enums\SubscriptionStatus;
use App\Domain\Subscription\Models\Plan;
use App\Domain\Subscription\Models\Subscription;
use App\Models\User;
use Smalot\PdfParser\Parser as PdfParser;

it('gera o PDF do currículo para cada template disponível', function (ResumeTemplate $template) {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();
    $resume->experiences()->create([
        'company' => 'Blue Service',
        'position' => 'Analista de Dados',
        'start_date' => '2023-01-01',
        'is_current' => true,
        'description' => 'Descrição da experiência.',
    ]);
    $resume->skills()->create(['name' => 'SQL']);

    $response = $this->actingAs($user)->get(route('resume.pdf.download', ['resume' => $resume, 'template' => $template->value]));

    $response->assertOk();
    expect($response->headers->get('content-type'))->toContain('application/pdf');

    // Regressão: floats aninhados em células de tabela do dompdf podem
    // renderizar como texto invisível. Garante que cargo/empresa/data
    // realmente aparecem no PDF gerado, não só que o download "funciona".
    $text = (new PdfParser)->parseContent($response->getContent())->getText();
    expect($text)
        ->toContain('Analista de Dados')
        ->toContain('Blue Service');
})->with(fn () => ResumeTemplate::cases());

it('rebaixa um template premium para o clássico quando o usuário está no plano free', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    $response = $this->actingAs($user)->get(route('resume.pdf.download', ['resume' => $resume, 'template' => 'moderno']));

    $response->assertOk();
    $text = (new PdfParser)->parseContent($response->getContent())->getText();
    expect($text)->toContain($resume->full_name);
});

it('permite baixar um template premium quando o usuário tem o plano pro', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();
    Subscription::create([
        'user_id' => $user->id,
        'plan_id' => Plan::where('key', 'pro')->firstOrFail()->id,
        'gateway' => 'test',
        'status' => SubscriptionStatus::Active,
    ]);

    $response = $this->actingAs($user)->get(route('resume.pdf.download', ['resume' => $resume, 'template' => 'moderno']));

    $response->assertOk();
});

it('impede um usuário de baixar o PDF do currículo de outro usuário', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $resume = Resume::factory()->for($owner)->create();

    $this->actingAs($intruder)
        ->get(route('resume.pdf.download', $resume))
        ->assertForbidden();
});
