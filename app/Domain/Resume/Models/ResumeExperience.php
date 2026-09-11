<?php

namespace App\Domain\Resume\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResumeExperience extends Model
{
    use HasFactory;

    protected $touches = ['resume'];

    protected $fillable = [
        'resume_id',
        'company',
        'position',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
        ];
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }
}
