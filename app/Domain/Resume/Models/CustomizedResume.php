<?php

namespace App\Domain\Resume\Models;

use App\Domain\Job\Models\JobDescription;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomizedResume extends Model
{
    protected $fillable = [
        'user_id',
        'resume_id',
        'job_description_id',
        'summary',
        'experience_descriptions',
        'highlighted_skills',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'experience_descriptions' => 'array',
            'highlighted_skills' => 'array',
            'notes' => 'array',
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
