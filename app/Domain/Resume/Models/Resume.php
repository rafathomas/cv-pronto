<?php

namespace App\Domain\Resume\Models;

use App\Domain\Resume\Enums\ResumeSource;
use App\Models\User;
use Database\Factories\ResumeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resume extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory(): ResumeFactory
    {
        return ResumeFactory::new();
    }

    protected $fillable = [
        'user_id',
        'title',
        'full_name',
        'email',
        'phone',
        'city',
        'state',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'professional_summary',
        'template',
        'latest_score',
        'imported_at',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'source' => ResumeSource::class,
            'imported_at' => 'datetime',
            'latest_score' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(ResumeExperience::class)->orderBy('sort_order');
    }

    public function education(): HasMany
    {
        return $this->hasMany(ResumeEducation::class)->orderBy('sort_order');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(ResumeCourse::class)->orderBy('sort_order');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(ResumeSkill::class)->orderBy('sort_order');
    }

    public function languages(): HasMany
    {
        return $this->hasMany(ResumeLanguage::class)->orderBy('sort_order');
    }
}
