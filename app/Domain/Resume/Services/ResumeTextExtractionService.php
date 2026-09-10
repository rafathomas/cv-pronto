<?php

namespace App\Domain\Resume\Services;

use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use RuntimeException;
use Smalot\PdfParser\Parser as PdfParser;

/**
 * Extrai texto bruto de arquivos PDF ou DOCX enviados pelo usuário, para
 * então ser interpretado pela IA (ver resources/prompts/resume/extract.txt).
 */
class ResumeTextExtractionService
{
    public function extract(string $absolutePath, string $extension): string
    {
        return match (strtolower($extension)) {
            'pdf' => $this->extractFromPdf($absolutePath),
            'docx' => $this->extractFromDocx($absolutePath),
            default => throw new RuntimeException("Formato de arquivo não suportado: {$extension}"),
        };
    }

    private function extractFromPdf(string $absolutePath): string
    {
        $parser = new PdfParser;
        $document = $parser->parseFile($absolutePath);

        return trim($document->getText());
    }

    private function extractFromDocx(string $absolutePath): string
    {
        $phpWord = IOFactory::load($absolutePath, 'Word2007');
        $text = [];

        foreach ($phpWord->getSections() as $section) {
            $this->collectText($section, $text);
        }

        return trim(implode("\n", $text));
    }

    private function collectText(AbstractContainer $container, array &$text): void
    {
        foreach ($container->getElements() as $element) {
            if ($element instanceof Text) {
                $text[] = $element->getText();
            } elseif ($element instanceof TextRun) {
                $this->collectText($element, $text);
            } elseif ($element instanceof AbstractContainer) {
                $this->collectText($element, $text);
            }
        }
    }
}
