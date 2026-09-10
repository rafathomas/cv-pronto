<?php

namespace App\Domain\JobMatch\Models;

use App\Domain\Job\Models\JobDescription;
use App\Domain\Resume\Models\Resume;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobMatch extends Model
{
    protected $fillable = [
        'user_id',
        'resume_id',
        'job_description_id',
        'match_score',
        'matched_skills',
        'partial_skills',
        'missing_skills',
        'keywords',
        'recommendations',
    ];

    protected function casts(): array
    {
        return [
            'match_score' => 'integer',
            'matched_skills' => 'array',
            'partial_skills' => 'array',
            'missing_skills' => 'array',
            'keywords' => 'array',
            'recommendations' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    public function jobDescription(): BelongsTo
    {
        return $this->belongsTo(JobDescription::class);
    }
}
