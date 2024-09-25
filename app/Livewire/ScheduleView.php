<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Job;
use Livewire\Component;
use App\Models\Schedule;

use Livewire\Attributes\On;

class ScheduleView extends Component
{
    public $date;
    public $dateDate;
    public $dateMonth;
    public $dateDay;

    public $due_jobs;
    public $due_jobs_array;
    public $scheduled_jobs;

    public $searchTerm = '';

    public function mount() 
    {
    }

    public function scheduleJob($jobId)
    {
        $existingSchedule = Schedule::where('job_id', $jobId)
            ->whereDate('scheduled_date', $this->date)
            ->first();

        if ($existingSchedule) {
            $this->dispatch('error', payload: [
                'error' => 'This job is already scheduled for ' . $this->date
            ]);
            return;
        }
    
        Schedule::create([
            'job_id' => $jobId,
            'scheduled_date' => $this->date,
        ]);

        $this->dispatch('message', payload: [
            'message' => 'Job scheduled successfully for ' . $this->date
        ]);
    }

    public function render()
    {
            // Get the start of the week (Monday) based on the selected date
        $startOfWeek = Carbon::parse($this->date)->startOfWeek(Carbon::MONDAY);
        
        // Prepare an array for the week (Monday to Sunday)
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $weekDays[] = $startOfWeek->copy()->addDays($i);
        }

        // Get all schedules for the week
        $scheduledJobs = Schedule::with('job')
            ->whereBetween('scheduled_date', [$startOfWeek, $startOfWeek->copy()->endOfWeek()])
            ->get();

        // Organize jobs by day
        $weeklySchedule = [];
        foreach ($weekDays as $day) {
            $scheduledForDay = $scheduledJobs->filter(function ($schedule) use ($day) {
                return Carbon::parse($schedule->scheduled_date)->isSameDay($day);
            });
            $weeklySchedule[$day->format('Y-m-d')] = $scheduledForDay;
        }

        $scheduledJobs = Schedule::with('job')
            ->whereDate('scheduled_date', $this->date)
            ->get();

        $filteredJobs = Job::where('name', 'like', '%' . $this->searchTerm . '%')
            ->take(3)
            ->get();

        $jobs = Job::all();

        $jobStatuses = [];

        foreach ($jobs as $job) {
            $status = $job->getJobStatus();
    
            $jobStatuses[] = [
                'job' => $job->name,
                'status' => $status['status'],
                'overdueWeeks' => $status['overdueWeeks'] ?? 'N/A',
                // 'scheduledDate' => $status['scheduledDate'] ?? 'N/A',
                'scheduledDate' => $status['scheduledDate'] ? Carbon::parse($status['scheduledDate']) : 'N/A',
                'type' => $status['type'] ?? 4,
                'last_completed' => $status['last_completed'] ? Carbon::createFromFormat('d/m/Y H:i', $status['last_completed'])->format('d/m/Y') : 'N/A',
            ];
        }

        usort($jobStatuses, function ($a, $b) {
            return $b['type'] - $a['type'];  // Sorts by type in descending order
        });

        return view('livewire.schedule-view', [
            'filteredJobs' => $filteredJobs,
            'scheduledJobs' => $scheduledJobs,
            'jobStatuses' => $jobStatuses,
            'weekDays' => $weekDays,
            'weeklySchedule' => $weeklySchedule,
        ]);
    }

    public function deleteSchedule($scheduleId)
    {
        $schedule = Schedule::findOrFail($scheduleId);
        $schedule->delete();

        // Reload the scheduled jobs after deletion
        // $this->loadScheduledJobs();

        $this->dispatch('message', payload: [
            'message' => 'Schedule deleted successfully!'
        ]);
    }


    #[On('date-change')] 
    public function updateDateChange($date)
    {
        $this->date = Carbon::createFromFormat('d/m/Y', $date);
        $this->dateMonth = $this->date->format('M');
        $this->dateDate = $this->date->format('d');
        $this->dateDay = $this->date->format('D');
    }
}
