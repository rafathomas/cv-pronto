<?php

namespace App\Domain\Resume\Models;

use App\Domain\Resume\Enums\LanguageLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResumeLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume_id',
        'name',
        'level',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'level' => LanguageLevel::class,
        ];
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }
}
