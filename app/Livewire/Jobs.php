<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Job;
use App\Models\User;

class Jobs extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $name, $body_corporate, $plan, $location, $location_link, $scope, $base_price;
    public $jobId;
    public $updateMode = false;
    public $modalTitle = 'Create Job';
    public $frequency;
    public $userIds = []; // To hold selected user IDs
    public $allUsers; // To hold all available users
    public $active; // To hold all available users


    // Job Scope properties
    public $lawn_care = false;
    public $trimming = false;
    public $raking_up_leaf_litter = false;
    public $weeding = false;
    public $blowing = false;
    public $clearing_garden_beds = false;
    public $collecting_litter = false;
    public $entrance_glass_cleaning = false;
    public $bringing_in_bins = false;
    public $remove_cob_webs = false;
    public $vacuum = false;
    public $clear_out_bin_corral = false;
    public $clearing_drainage_gate = false;
    public $vacuum_and_mop_laundry = false;
    public $remove_waste_laundry = false;

    protected $rules = [
        'name' => 'required|string',
        'body_corporate' => 'required|string',
        'plan' => 'nullable|string',
        'location' => 'required|string',
        'location_link' => 'nullable|url',
        'scope' => 'nullable|string',
        'base_price' => 'required|numeric',
        'frequency' => 'nullable|integer|min:1',
        'active' => 'boolean',

        // Validation rules for job scope fields
        'lawn_care' => 'boolean',
        'trimming' => 'boolean',
        'raking_up_leaf_litter' => 'boolean',
        'weeding' => 'boolean',
        'blowing' => 'boolean',
        'clearing_garden_beds' => 'boolean',
        'collecting_litter' => 'boolean',
        'entrance_glass_cleaning' => 'boolean',
        'bringing_in_bins' => 'boolean',
        'remove_cob_webs' => 'boolean',
        'vacuum' => 'boolean',
        'clear_out_bin_corral' => 'boolean',
        'clearing_drainage_gate' => 'boolean',
        'vacuum_and_mop_laundry' => 'boolean',
        'remove_waste_laundry' => 'boolean',
    ];

    public function render()
    {
        $jobs = Job::where('name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('body_corporate', 'like', '%' . $this->searchTerm . '%')
            ->paginate(10);

        $this->allUsers = User::all();

        return view('livewire.jobs', [
            'jobs' => $jobs,
        ]);
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->body_corporate = '';
        $this->plan = '';
        $this->location = '';
        $this->location_link = '';
        $this->scope = '';
        $this->base_price = '';
        $this->frequency = 1;
        $this->active = true;

        // Reset job scope fields
        $this->lawn_care = false;
        $this->trimming = false;
        $this->raking_up_leaf_litter = false;
        $this->weeding = false;
        $this->blowing = false;
        $this->clearing_garden_beds = false;
        $this->collecting_litter = false;
        $this->entrance_glass_cleaning = false;
        $this->bringing_in_bins = false;
        $this->remove_cob_webs = false;
        $this->vacuum = false;
        $this->clear_out_bin_corral = false;
        $this->clearing_drainage_gate = false;
        $this->vacuum_and_mop_laundry = false;
        $this->remove_waste_laundry = false;

        $this->userIds = [];
    }

    public function openCreateModal()
    {
        $this->resetInputFields();
        $this->updateMode = false;
        $this->modalTitle = 'Create Job';
        $this->dispatch('openModal');
    }

    public function store()
    {
        $this->validate();

        $job = Job::create([
            'name' => $this->name,
            'body_corporate' => $this->body_corporate,
            'plan' => $this->plan,
            'location' => $this->location,
            'location_link' => $this->location_link,
            'scope' => $this->scope,
            'base_price' => $this->base_price,
            'frequency' => $this->frequency ?: null,
            'active' => $this->active ? true : false,
        ]);

        // Create the job scope
        $job->job_scope()->create([
            'lawn_care' => $this->lawn_care,
            'trimming' => $this->trimming,
            'raking_up_leaf_litter' => $this->raking_up_leaf_litter,
            'weeding' => $this->weeding,
            'blowing' => $this->blowing,
            'clearing_garden_beds' => $this->clearing_garden_beds,
            'collecting_litter' => $this->collecting_litter,
            'entrance_glass_cleaning' => $this->entrance_glass_cleaning,
            'bringing_in_bins' => $this->bringing_in_bins,
            'remove_cob_webs' => $this->remove_cob_webs,
            'vacuum' => $this->vacuum,
            'clear_out_bin_corral' => $this->clear_out_bin_corral,
            'clearing_drainage_gate' => $this->clearing_drainage_gate,
            'vacuum_and_mop_laundry' => $this->vacuum_and_mop_laundry,
            'remove_waste_laundry' => $this->remove_waste_laundry,
        ]);

        $job->users()->attach($this->userIds);

        session()->flash('message', 'Job Created Successfully.');

        $this->resetInputFields();
        $this->dispatch('closeModal');
    }

    public function edit($id)
    {
        $job = Job::with(['job_scope', 'users'])->findOrFail($id);
        $this->jobId = $id;
        $this->name = $job->name;
        $this->body_corporate = $job->body_corporate;
        $this->plan = $job->plan;
        $this->location = $job->location;
        $this->location_link = $job->location_link;
        $this->scope = $job->scope;
        $this->base_price = $job->base_price;
        $this->frequency = $job->frequency;
        $this->active = $job->active;

        // Load job scope fields
        $this->lawn_care = $job->job_scope->lawn_care;
        $this->trimming = $job->job_scope->trimming;
        $this->raking_up_leaf_litter = $job->job_scope->raking_up_leaf_litter;
        $this->weeding = $job->job_scope->weeding;
        $this->blowing = $job->job_scope->blowing;
        $this->clearing_garden_beds = $job->job_scope->clearing_garden_beds;
        $this->collecting_litter = $job->job_scope->collecting_litter;
        $this->entrance_glass_cleaning = $job->job_scope->entrance_glass_cleaning;
        $this->bringing_in_bins = $job->job_scope->bringing_in_bins;
        $this->remove_cob_webs = $job->job_scope->remove_cob_webs;
        $this->vacuum = $job->job_scope->vacuum;
        $this->clear_out_bin_corral = $job->job_scope->clear_out_bin_corral;
        $this->clearing_drainage_gate = $job->job_scope->clearing_drainage_gate;
        $this->vacuum_and_mop_laundry = $job->job_scope->vacuum_and_mop_laundry;
        $this->remove_waste_laundry = $job->job_scope->remove_waste_laundry;

        $this->userIds = $job->users->pluck('id')->toArray();

        $this->updateMode = true;
        $this->modalTitle = 'Edit Job';
        $this->dispatch('openModal');
    }

    public function update()
    {
        $this->validate();

        if ($this->jobId) {
            $job = Job::find($this->jobId);
            $job->update([
                'name' => $this->name,
                'body_corporate' => $this->body_corporate,
                'plan' => $this->plan,
                'location' => $this->location,
                'location_link' => $this->location_link,
                'scope' => $this->scope,
                'base_price' => $this->base_price,
                'frequency' => $this->frequency ?: null,
                'active' => $this->active ? true : false,
            ]);

            // Update or create the job scope
            $job->job_scope()->updateOrCreate(
                ['job_id' => $this->jobId],
                [
                    'lawn_care' => $this->lawn_care,
                    'trimming' => $this->trimming,
                    'raking_up_leaf_litter' => $this->raking_up_leaf_litter,
                    'weeding' => $this->weeding,
                    'blowing' => $this->blowing,
                    'clearing_garden_beds' => $this->clearing_garden_beds,
                    'collecting_litter' => $this->collecting_litter,
                    'entrance_glass_cleaning' => $this->entrance_glass_cleaning,
                    'bringing_in_bins' => $this->bringing_in_bins,
                    'remove_cob_webs' => $this->remove_cob_webs,
                    'vacuum' => $this->vacuum,
                    'clear_out_bin_corral' => $this->clear_out_bin_corral,
                    'clearing_drainage_gate' => $this->clearing_drainage_gate,
                    'vacuum_and_mop_laundry' => $this->vacuum_and_mop_laundry,
                    'remove_waste_laundry' => $this->remove_waste_laundry,
                ]
            );

            $job->users()->sync($this->userIds);

            session()->flash('message', 'Job Updated Successfully.');

            $this->resetInputFields();
            $this->dispatch('closeModal');
        }
    }

    public function delete($id)
    {
        $this->jobId = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function deleteConfirmed()
    {
        Job::find($this->jobId)->delete();
        session()->flash('message', 'Job Deleted Successfully.');
        $this->dispatch('deleted');
    }

    public function cancelDelete()
    {
        $this->jobId = null; // Reset the jobId, so no job is selected for deletion
    }
}
