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
        $this->due_jobs = Job::all();
        $this->due_jobs_array = Job::all()->toArray();
        $this->scheduled_jobs = Job::all();
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
                'scheduledDate' => $status['scheduledDate'] ?? 'N/A',
                'type' => $status['type'] ?? 4,
            ];
        }

        return view('livewire.schedule-view', [
            'filteredJobs' => $filteredJobs,
            'scheduledJobs' => $scheduledJobs,
            'jobStatuses' => $jobStatuses,
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
