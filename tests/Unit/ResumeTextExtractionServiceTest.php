<?php

use App\Domain\Resume\Services\ResumeTextExtractionService;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

it('extrai texto de um arquivo DOCX', function () {
    $phpWord = new PhpWord;
    $section = $phpWord->addSection();
    $section->addText('Rafael Souza');
    $section->addText('Analista de Dados Pleno com experiência em SQL e Python.');

    $path = tempnam(sys_get_temp_dir(), 'cv').'.docx';
    IOFactory::createWriter($phpWord, 'Word2007')->save($path);

    $text = app(ResumeTextExtractionService::class)->extract($path, 'docx');

    unlink($path);

    expect($text)
        ->toContain('Rafael Souza')
        ->toContain('Analista de Dados Pleno');
});
