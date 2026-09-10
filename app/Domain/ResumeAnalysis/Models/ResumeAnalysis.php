<?php

namespace App\Domain\ResumeAnalysis\Models;

use App\Domain\Resume\Models\Resume;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResumeAnalysis extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'resume_id',
        'user_id',
        'score',
        'summary',
        'strengths',
        'weaknesses',
        'recommendations',
        'categories',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'strengths' => 'array',
            'weaknesses' => 'array',
            'recommendations' => 'array',
            'categories' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
