<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class Tickets extends Component
{
    use WithPagination;

    public $name, $description, $status = 'Open', $job_id;
    public $ticketId = null;
    public $updateMode = false;
    public $selectedJob = '';
    public $statusFilter = ''; // Status filter

    protected $rules = [
        'name' => 'required|string',
        'description' => 'required|string',
        'status' => 'required|in:Open,Pending,On hold,Solved,Closed',
        'job_id' => 'required|exists:jobs,id',
        // 'user_id' => 'required|exists:users,id',
    ];

    public function render()
    {
        // $tickets = Ticket::with('job')->paginate(10);
        $jobs = Job::all();
        $tickets = Ticket::when($this->selectedJob, function($query) {
            $query->where('job_id', $this->selectedJob);
        })
        ->when($this->statusFilter, function ($query) {
            return $query->where('status', $this->statusFilter);
        })
        ->paginate(10);
        
        return view('livewire.tickets', [
            'tickets' => $tickets, 
            'jobs' => $jobs
        ]);
    }

    public function store()
    {
        $this->validate();

        Ticket::create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'job_id' => $this->job_id,
            'user_id' => Auth::id(),
        ]);

        $this->resetInputFields();
        session()->flash('message', 'Ticket Created Successfully.');
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->ticketId = $id;
        $this->name = $ticket->name;
        $this->description = $ticket->description;
        $this->status = $ticket->status;
        $this->job_id = $ticket->job_id;
        $this->updateMode = true;

        // $this->dispatch('setJob', payload: ['jobId' => $this->job_id]);

        $this->dispatch('setJob', payload: [
            'jobId' => $this->job_id
        ]);
    }

    public function update()
    {
        $this->validate();

        if ($this->ticketId) {
            $ticket = Ticket::find($this->ticketId);
            $ticket->update([
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status,
                'job_id' => $this->job_id,
            ]);

            $this->resetInputFields();
            $this->updateMode = false;
            session()->flash('message', 'Ticket Updated Successfully.');
        }
    }

    public function confirmDelete($id)
    {
        $this->ticketId = $id;
        $this->dispatch('showDeleteConfirmation');
    }

    public function deleteConfirmed()
    {
        Ticket::find($this->ticketId)->delete();
        $this->resetInputFields();
        session()->flash('message', 'Ticket Deleted Successfully.');
        $this->dispatch('hideDeleteConfirmation');
    }

    private function resetInputFields()
    {
        $this->dispatch('unsetJob');
        $this->name = '';
        $this->description = '';
        $this->status = 'Open';
        $this->job_id = '';
        $this->ticketId = null;
        $this->updateMode = false;
    }

}
