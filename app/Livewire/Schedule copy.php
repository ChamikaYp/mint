<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Job;
use Livewire\Component;

use Livewire\Attributes\On;

class Schedule extends Component
{
    public $date;
    public $dateDate;
    public $dateMonth;
    public $dateDay;

    public $due_jobs;
    public $due_jobs_array;
    public $scheduled_jobs;

    public function mount() 
    {
        $this->due_jobs = Job::all();
        $this->due_jobs_array = Job::all()->toArray();
        $this->scheduled_jobs = Job::all();
    }

    public function render()
    {
        return view('livewire.schedule');
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
