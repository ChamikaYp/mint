<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class CompletedJob extends Model
{
    use HasFactory;

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function job_images(): HasMany
    {
        return $this->hasMany(JobImage::class);
    }

    public function getDateAttribute()
    {
        return Carbon::parse($this->attributes['start_time'])->format('d/m/Y');
    }

    public function getStartTimeAttribute()
    {
        return Carbon::parse($this->attributes['start_time'])->format('d/m/Y H:i');
    }
    public function getEndTimeAttribute()
    {
        return Carbon::parse($this->attributes['end_time'])->format('d/m/Y H:i');
    }
}
