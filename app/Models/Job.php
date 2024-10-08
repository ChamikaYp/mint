<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'body_corporate',
        'plan',
        'location',
        'location_link',
        'scope',
        'base_price',
        'frequency',
        'active',
    ];

    public function body_corporate(): BelongsTo
    {
        return $this->belongsTo(BodyCorporate::class);
    }

    public function jobs_completed(): HasMany
    {
        return $this->hasMany(CompletedJob::class);
    }

    public function job_scope(): HasOne
    {
        return $this->hasOne(JobScope::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'job_user', 'job_id', 'user_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function getJobStatus()
    {
        // Get the Monday of the current date (this week's Monday 00:00)
        $now = Carbon::now()->startOfWeek(Carbon::MONDAY)->setTime(0, 0);
        
        // Get the last completed job
        $lastCompletedJob = $this->jobs_completed()->orderBy('end_time', 'desc')->first();
        
        $lastSchedule = $this->schedules()->orderBy('scheduled_date', 'desc')->first();

        if (!$this->active) {
            return ['status' => 'Inactive', 'overdueWeeks' => null, 'scheduledDate' => null, 'type' => 1, 'last_completed' => null];
        }

        if (is_null($this->frequency) || $this->frequency === 0) {
            if ($lastCompletedJob) {
                return ['status' => 'Completed', 'overdueWeeks' => null, 'scheduledDate' => null, 'type' => 1, 'last_completed' => $lastCompletedJob->end_time];
            }
            if ($lastSchedule && $now->lessThanOrEqualTo($lastSchedule->scheduled_date)) {
                return ['status' => 'One Off', 'overdueWeeks' => null, 'scheduledDate' => $lastSchedule->scheduled_date, 'type' => 1, 'last_completed' => null];
            }
            if ($lastSchedule) {
                return ['status' => 'One Off', 'overdueWeeks' => null, 'scheduledDate' => $lastSchedule->scheduled_date, 'type' => 4, 'last_completed' => null];
            }
            return ['status' => 'One Off', 'overdueWeeks' => null, 'scheduledDate' => null, 'type' => 4, 'last_completed' => null];
        }

        if (!$lastCompletedJob) {
            if ($lastSchedule && $now->lessThanOrEqualTo($lastSchedule->scheduled_date)) {
                return ['status' => 'Not Started', 'overdueWeeks' => null, 'scheduledDate' => $lastSchedule->scheduled_date, 'type' => 1, 'last_completed' => null];
            }
            if ($lastSchedule) {
                return ['status' => 'Not Started', 'overdueWeeks' => null, 'scheduledDate' => $lastSchedule->scheduled_date, 'type' => 4, 'last_completed' => null];
            }
            return ['status' => 'Not Started', 'overdueWeeks' => null, 'scheduledDate' => null, 'type' => 4, 'last_completed' => null];
        } else {
            $lastCompletedAt = Carbon::createFromFormat('d/m/Y H:i', $lastCompletedJob->end_time)->startOfWeek(Carbon::MONDAY)->setTime(0, 0);
            $nextDueDate = $lastCompletedAt->copy()->addWeeks($this->frequency);
            if ($now->lessThan($nextDueDate)) {
                if ($lastSchedule) {
                    if ($nextDueDate->isSameWeek(Carbon::parse($lastSchedule->scheduled_date))) {
                        return ['status' => 'Pending', 'overdueWeeks' => floor(-1*Carbon::now()->diffInWeeks($nextDueDate)), 'scheduledDate' => $lastSchedule->scheduled_date, 'type' => 1, 'last_completed' => $lastCompletedJob->end_time];
                    }
                    if ($now->lessThanOrEqualTo(Carbon::parse($lastSchedule->scheduled_date))) {
                        return ['status' => 'Pending', 'overdueWeeks' => floor(-1*Carbon::now()->diffInWeeks($nextDueDate)), 'scheduledDate' => $lastSchedule->scheduled_date, 'type' => 3, 'last_completed' => $lastCompletedJob->end_time];
                    }
                }
                return ['status' => 'Pending', 'overdueWeeks' => floor(-1*Carbon::now()->diffInWeeks($nextDueDate)), 'scheduledDate' => null, 'type' => 2, 'last_completed' => $lastCompletedJob->end_time];
            } elseif ($now->equalTo($nextDueDate)) {
                if ($lastSchedule) {
                    if ($nextDueDate->isSameWeek(Carbon::parse($lastSchedule->scheduled_date))) {
                        return ['status' => 'Outstanding', 'overdueWeeks' => floor(-1*Carbon::now()->diffInWeeks($nextDueDate)), 'scheduledDate' => $lastSchedule->scheduled_date, 'type' => 1, 'last_completed' => $lastCompletedJob->end_time];
                    }
                    if ($now->lessThanOrEqualTo(Carbon::parse($lastSchedule->scheduled_date))) {
                        return ['status' => 'Outstanding', 'overdueWeeks' => floor(-1*Carbon::now()->diffInWeeks($nextDueDate)), 'scheduledDate' => $lastSchedule->scheduled_date, 'type' => 3, 'last_completed' => $lastCompletedJob->end_time];
                    }
                }
                return ['status' => 'Outstanding', 'overdueWeeks' => floor(-1*Carbon::now()->diffInWeeks($nextDueDate)), 'scheduledDate' => null, 'type' => 3, 'last_completed' => $lastCompletedJob->end_time];
            } else {
                if ($lastSchedule) {
                    if ($now->isSameWeek(Carbon::parse($lastSchedule->scheduled_date))) {
                        return ['status' => 'Overdue', 'overdueWeeks' => floor(-1*Carbon::now()->diffInWeeks($nextDueDate)), 'scheduledDate' => $lastSchedule->scheduled_date, 'type' => 1, 'last_completed' => $lastCompletedJob->end_time];
                    }
                    if ($now->lessThanOrEqualTo(Carbon::parse($lastSchedule->scheduled_date))) {
                        return ['status' => 'Overdue', 'overdueWeeks' => floor(-1*Carbon::now()->diffInWeeks($nextDueDate)), 'scheduledDate' => $lastSchedule->scheduled_date, 'type' => 3, 'last_completed' => $lastCompletedJob->end_time];
                    }
                }
                return ['status' => 'Overdue', 'overdueWeeks' => floor(-1*Carbon::now()->diffInWeeks($nextDueDate)), 'scheduledDate' => null, 'type' => 4, 'last_completed' => $lastCompletedJob->end_time];
            }
        }
    }
}
