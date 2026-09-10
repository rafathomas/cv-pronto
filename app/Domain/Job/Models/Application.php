<?php

namespace App\Domain\Job\Models;

use App\Domain\Job\Enums\ApplicationStatus;
use App\Domain\Resume\Models\Resume;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Rastreador de candidaturas. Arquitetura pronta, mas a feature fica
 * desativada na UI no MVP (ver roadmap do produto).
 */
class Application extends Model
{
    protected $fillable = [
        'user_id',
        'resume_id',
        'company',
        'position',
        'url',
        'status',
        'applied_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => ApplicationStatus::class,
            'applied_at' => 'datetime',
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
}
