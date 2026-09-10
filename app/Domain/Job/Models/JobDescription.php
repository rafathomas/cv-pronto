<?php

namespace App\Domain\Job\Models;

use App\Domain\JobMatch\Models\JobMatch;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobDescription extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'company',
        'url',
        'raw_text',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(JobMatch::class);
    }
}
