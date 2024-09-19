<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobImage extends Model
{
    use HasFactory;

    public function completed_job(): BelongsTo
    {
        return $this->belongsTo(CompletedJob::class);
    }
}
