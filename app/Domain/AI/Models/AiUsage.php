<?php

namespace App\Domain\AI\Models;

use App\Domain\AI\Enums\AiOperation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUsage extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'operation',
        'provider',
        'model',
        'input_tokens',
        'output_tokens',
        'estimated_cost',
    ];

    protected function casts(): array
    {
        return [
            'operation' => AiOperation::class,
            'input_tokens' => 'integer',
            'output_tokens' => 'integer',
            'estimated_cost' => 'decimal:6',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
