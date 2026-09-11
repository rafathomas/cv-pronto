<?php

namespace App\Domain\Resume\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResumeCourse extends Model
{
    use HasFactory;

    protected $touches = ['resume'];

    protected $fillable = [
        'resume_id',
        'name',
        'institution',
        'completed_at',
        'workload_hours',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'date',
        ];
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }
}
