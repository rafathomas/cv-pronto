<?php

namespace App\Domain\Resume\Models;

use App\Domain\Resume\Enums\ResumeSource;
use App\Domain\ResumeAnalysis\Models\ResumeAnalysis;
use App\Models\User;
use Database\Factories\ResumeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
        'accent_color',
        'photo_path',
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

    protected static function booted(): void
    {
        static::forceDeleted(function (Resume $resume) {
            if ($resume->photo_path) {
                Storage::disk('resumes')->delete($resume->photo_path);
            }
        });
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

    public function analyses(): HasMany
    {
        return $this->hasMany(ResumeAnalysis::class)->latest('created_at');
    }

    public function latestAnalysis(): HasOne
    {
        return $this->hasOne(ResumeAnalysis::class)->latestOfMany('created_at');
    }

    public function hasPhoto(): bool
    {
        return (bool) $this->photo_path && Storage::disk('resumes')->exists($this->photo_path);
    }

    /**
     * A foto como data URI base64, para embutir diretamente no PDF gerado
     * pelo dompdf (evita depender de fetch remoto/URL assinada dentro do PDF).
     */
    public function photoDataUri(): ?string
    {
        if (! $this->photo_path || ! Storage::disk('resumes')->exists($this->photo_path)) {
            return null;
        }

        $contents = Storage::disk('resumes')->get($this->photo_path);
        $mime = Storage::disk('resumes')->mimeType($this->photo_path) ?: 'image/jpeg';

        return 'data:'.$mime.';base64,'.base64_encode($contents);
    }
}
