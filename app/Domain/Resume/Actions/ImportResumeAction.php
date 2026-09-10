<?php

namespace App\Domain\Resume\Actions;

use App\Domain\AI\DTOs\ResumeExtractionResult;
use App\Domain\AI\Services\AiUsageService;
use App\Domain\Resume\Services\ResumeTextExtractionService;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class ImportResumeAction
{
    public function __construct(
        private readonly ResumeTextExtractionService $textExtraction,
        private readonly AiUsageService $usageService,
    ) {}

    public function handle(User $user, UploadedFile $file): ResumeExtractionResult
    {
        $extension = $file->getClientOriginalExtension();
        $rawText = $this->textExtraction->extract($file->getRealPath(), $extension);

        if (trim($rawText) === '') {
            throw new RuntimeException('Não foi possível extrair texto deste arquivo. Verifique se ele não é uma imagem escaneada.');
        }

        return $this->usageService->extractResume($user, $rawText);
    }
}
