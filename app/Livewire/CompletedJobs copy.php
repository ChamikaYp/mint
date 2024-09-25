<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Job;
use App\Models\CompletedJob;
use App\Models\JobImage;
use App\Models\User;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class CompletedJobs extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $jobs;
    public $users;
    public $auth_user;
    public $is_user_admin;
    public $photos = [];
    public $start_job_scope;
    public $running_job;
    public $running_job_start;

    public $delete_job_id;

    public $notes = '';
    public $extra_work = '';

    public $job_modal_mode = -1;

    public $filterJob = '';
    public $filterDate = '';
    public $filterInvoiced = '';

    public $sortField = 'submitted_on'; // Default sort field
    public $sortDirection = 'desc'; // Default sort direction

    public function mount() 
    {
        $this->jobs = Job::all();
        $this->users = User::all();
        if (Auth::user()->admin) {
            $this->is_user_admin = true;
        } else {
            $this->is_user_admin = false;
        }
    }

    public function updatingFilterJob()
    {
        $this->resetPage(); // Resets pagination when filter changes
    }

    public function updatingFilterDate()
    {
        $this->resetPage();
    }

    public function updatingFilterInvoiced()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = CompletedJob::query();

        // Check if the user is an admin
        $this->auth_user = Auth::user();

        // If the user is not an admin, filter jobs by the authenticated user's relation in completed_job_user table
        if (!$this->auth_user->admin) {
            $query->whereHas('users', function ($q) {
                $q->where('user_id', $this->auth_user->id);
            });
        }

        // Apply filters before pagination
        if ($this->filterJob) {
            $query->whereHas('job', function ($q) {
                $q->where('name', 'like', '%' . $this->filterJob . '%');
            });
        }

        if ($this->filterDate) {
            $query->whereDate('start_time', Carbon::parse($this->filterDate));
        }

        if ($this->filterInvoiced !== '') {
            $query->where('invoiced', $this->filterInvoiced);
        }

        // Apply sorting
        if ($this->sortField === 'completed_on') {
            // Sort by the completed_on date (assuming it maps to start_time)
            $query->orderBy('start_time', $this->sortDirection);
        } elseif ($this->sortField === 'submitted_on') {
            // Sort by the submitted_on date (created_at)
            $query->orderBy('created_at', $this->sortDirection);
        }

        // Paginate the filtered results
        $completed_jobs = $query->paginate(15);

        return view('livewire.completed-jobs', [
            'jobs' => $this->jobs,
            'users' => $this->users,
            'completed_jobs' => $completed_jobs,
            'auth_user' => $this->auth_user,
        ]);

        // $query = CompletedJob::query();

        // // Apply filters before pagination
        // if ($this->filterJob) {
        //     $query->whereHas('job', function ($q) {
        //         $q->where('name', 'like', '%' . $this->filterJob . '%');
        //     });
        // }

        // if ($this->filterDate) {
        //     $query->whereDate('start_time', Carbon::parse($this->filterDate));
        // }

        // if ($this->filterInvoiced !== '') {
        //     $query->where('invoiced', $this->filterInvoiced);
        // }

        // // Apply sorting
        // if ($this->sortField === 'completed_on') {
        //     // Sort by the completed_on date (assuming it maps to start_time)
        //     $query->orderBy('start_time', $this->sortDirection);
        // } elseif ($this->sortField === 'submitted_on') {
        //     // Sort by the submitted_on date (created_at)
        //     $query->orderBy('created_at', $this->sortDirection);
        // }

        // // Paginate the filtered results
        // $completed_jobs = $query->paginate(15);

        // $this->auth_user = Auth::user();
        // $this->auth_userjs = Auth::user()->toArray();
        // return view('livewire.completed-jobs', [
        //     'jobs' => $this->jobs,
        //     'users' => $this->users,
        //     'completed_jobs' => $completed_jobs,
        //     'auth_user' => $this->auth_user,
        // ]);
    }
    // #[On('delete-submission')] 
    // public function deleteCompletedJob($completed_job_id)
    // {
    //     CompletedJob::find($completed_job_id)->delete();
    //     $this->dispatch('$refresh');
    // }
    #[On('update-scope')] 
    public function updateScope($job_id)
    {
        if ($job_id != null && $this->job_modal_mode != "view" && $this->job_modal_mode != "edit") {
            // if ($this->job_modal_mode != "view" && $this->job_modal_mode != "edit") {
                
            // }
                // dd("hi");
            // if ($this->job_modal_mode != "view" || $this->job_modal_mode != "edit")
            // $job_scope = Job::find($job_id)->job_scope()->get()->toArray();
            // if (!empty($job_scope)) {
            //     $this->dispatch('auto-fill-scope', payload: $job_scope[0]);
            //     // dd($job_scope);
            // }

            $job = Job::find($job_id);
    
            // Get job scope and users
            $job_scope = $job->job_scope()->get()->toArray();
            $job_users = $job->users()->get()->pluck('id')->toArray();
            
            if (!empty($job_scope)) {
                // Merge job_scope and job_users arrays
                $merged_data = array_merge($job_scope[0], ['users' => $job_users]);

                $this->dispatch('auto-fill-scope', payload: $merged_data);
                // dd($merged_data);
            }
        }
        // CompletedJob::find($completed_job_id)->delete();
        
    }

    #[On('submission-created')] 
    public function addNewCompletedJob($editJobId, $jobId, $teamMembers, $startTime, $endTime, $weedSpray, $weedSprayUsed, $greenWaste, $bulbsReplaced, $lawnCare, $trimming, $rakingUpLeafLitter, $weeding, $blowing, $clearingGardenBeds, $collectingLitter, $entranceGlassCleaning, $bringingInBins, $removeCobWebs, $vacuum, $clearOutBinCorral, $clearingDrainageGate, $vacuumAndMopLaundry, $removeWasteLaundry, $notes, $extraWork, $weedSprayCharge = 0, $extraCharge = 0)
    {
        // dd($this->job_modal_mode);
        if ($editJobId == null) {
            $photo_links = [];

            foreach ($this->photos as $photo) {
                $path = $photo->store('photos', 'public');
                $url = Storage::url($path);
                $photo_links[] = $url;
            }

            $completed_job = new CompletedJob;
            $completed_job->job_id = $jobId;
            $completed_job->start_time = Carbon::parse($startTime);
            $completed_job->end_time = Carbon::parse($endTime);
            $completed_job->weed_spray = $weedSpray;
            $completed_job->weed_spray_used = $weedSprayUsed;
            $completed_job->green_waste = $greenWaste;
            $completed_job->bulbs_replaced = $bulbsReplaced;
            $completed_job->lawn_care = $lawnCare;
            $completed_job->trimming = $trimming;
            $completed_job->raking_up_leaf_litter = $rakingUpLeafLitter;
            $completed_job->weeding = $weeding;
            $completed_job->blowing = $blowing;
            $completed_job->clearing_garden_beds = $clearingGardenBeds;
            $completed_job->collecting_litter = $collectingLitter;
            $completed_job->entrance_glass_cleaning = $entranceGlassCleaning;
            $completed_job->bringing_in_bins = $bringingInBins;
            $completed_job->remove_cob_webs = $removeCobWebs;
            $completed_job->vacuum = $vacuum;
            $completed_job->clear_out_bin_corral = $clearOutBinCorral;
            $completed_job->clearing_drainage_gate = $clearingDrainageGate;
            $completed_job->vacuum_and_mop_laundry = $vacuumAndMopLaundry;
            $completed_job->remove_waste_laundry = $removeWasteLaundry;
            $completed_job->notes = $notes;
            $completed_job->extra_work = $extraWork;
            $completed_job->price = Job::find($jobId)->base_price;
            $completed_job->weed_spray_charge = $weedSprayCharge;
            $completed_job->extra_charge = $extraCharge;
            $completed_job->total_price = ($completed_job->price + $greenWaste*12 + $bulbsReplaced*20 + $weedSprayCharge*25 + $extraCharge)*1.1;
            $completed_job->invoiced = false;

            $completed_job->save();

            foreach ($teamMembers as $team_member) {
                $user = User::find($team_member);
                $user->completed_jobs()->attach($completed_job->id);
            }

            foreach ($photo_links as $photo_link) {
                $job_image = new JobImage;
                $job_image->url = $photo_link;
                $job_image->completed_job_id = $completed_job->id;
                $job_image->save();
            }

            if ($this->job_modal_mode == "end") {
                // dd("hh");
                Auth::user()->running_job = null;
                Auth::user()->running_job_start = null;
                Auth::user()->save();
            }
        } else {
            $completed_job = CompletedJob::find($editJobId);
            $completed_job->job_id = $jobId;
            $completed_job->start_time = Carbon::parse($startTime);
            $completed_job->end_time = Carbon::parse($endTime);
            $completed_job->weed_spray = $weedSpray;
            $completed_job->weed_spray_used = $weedSprayUsed;
            $completed_job->green_waste = $greenWaste;
            $completed_job->bulbs_replaced = $bulbsReplaced;
            $completed_job->lawn_care = $lawnCare;
            $completed_job->trimming = $trimming;
            $completed_job->raking_up_leaf_litter = $rakingUpLeafLitter;
            $completed_job->weeding = $weeding;
            $completed_job->blowing = $blowing;
            $completed_job->clearing_garden_beds = $clearingGardenBeds;
            $completed_job->collecting_litter = $collectingLitter;
            $completed_job->entrance_glass_cleaning = $entranceGlassCleaning;
            $completed_job->bringing_in_bins = $bringingInBins;
            $completed_job->remove_cob_webs = $removeCobWebs;
            $completed_job->vacuum = $vacuum;
            $completed_job->clear_out_bin_corral = $clearOutBinCorral;
            $completed_job->clearing_drainage_gate = $clearingDrainageGate;
            $completed_job->vacuum_and_mop_laundry = $vacuumAndMopLaundry;
            $completed_job->remove_waste_laundry = $removeWasteLaundry;
            $completed_job->notes = $notes;
            $completed_job->extra_work = $extraWork;
            $completed_job->price = Job::find($jobId)->base_price;
            $completed_job->weed_spray_charge = $weedSprayCharge;
            $completed_job->extra_charge = $extraCharge;
            $completed_job->total_price = ($completed_job->price + $greenWaste*12 + $bulbsReplaced*20 + $weedSprayCharge*25 + $extraCharge)*1.1;
            $completed_job->invoiced = false;

            $completed_job->save();

            $currentTeamMembers = $completed_job->users()->get();
            foreach ($currentTeamMembers as $team_member) {
                $team_member->completed_jobs()->detach($completed_job->id);
            }

            foreach ($teamMembers as $team_member) {
                $user = User::find($team_member);
                $user->completed_jobs()->attach($completed_job->id);
            }
        }


        // dd($jobId, $teamMembers, Carbon::parse($startTime), Carbon::parse($endTime), $photo_links);
    }

    public function viewClick($completed_job_id)
    {
        $this->job_modal_mode = "view";
        $completed_job = CompletedJob::find($completed_job_id);
        $this->dispatch('show-job', payload: [
            'job' => $completed_job,
            'startTime' => $completed_job->getStartTimeAttribute(),
            'endTime' => $completed_job->getEndTimeAttribute(),
            'teamMembers' => $completed_job->users()->get()->pluck('id')->toArray(),
            'jobImages' => $completed_job->job_images()->get()->pluck('url')->toArray(),
        ]);
        // dd($completed_job_id);
    }

    public function editClick($completed_job_id)
    {
        $this->job_modal_mode = "edit";
        $completed_job = CompletedJob::find($completed_job_id);
        $this->dispatch('edit-job', payload: [
            'job' => $completed_job,
            'startTime' => $completed_job->getStartTimeAttribute(),
            'endTime' => $completed_job->getEndTimeAttribute(),
            'teamMembers' => $completed_job->users()->get()->pluck('id')->toArray(),
            'jobImages' => $completed_job->job_images()->get()->pluck('url')->toArray(),
        ]);
        // dd($completed_job_id);
    }

    public function deleteClick($id)
    {
        $this->delete_job_id = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function deleteConfirmed()
    {
        CompletedJob::find($this->delete_job_id)->delete();
        $this->dispatch('deleted');
    }

    public function cancelDelete()
    {
        $this->jobId = null; // Reset the jobId, so no job is selected for deletion
    }

    public function toggleInvoice($completed_job_id)
    {
        $completed_job = CompletedJob::find($completed_job_id);
        // dd($completed_job);
        $completed_job->invoiced = !$completed_job->invoiced;
        $completed_job->save();
    }

    

    #[On('change-start-job')] 
    public function changeStartJob($job_id)
    {
        // dd($job_id);
        // $this->start_job_scope = Job::find($job_id)->scope;
        $this->dispatch('show-start-scope', payload: [
            'scope' => Job::find($job_id)->scope,
            'tickets' => Job::find($job_id)->tickets()->get()->toArray()
        ]);
        // CompletedJob::find($completed_job_id)->delete();
    }

    #[On('start-job')] 
    public function startJob($job_id, $start_time)
    {
        Auth::user()->running_job = $job_id;
        Auth::user()->running_job_start = Carbon::parse($start_time);
        Auth::user()->save();
    }

    #[On('open-modal')] 
    public function openModal($mode, $job)
    {
        $this->job_modal_mode = $mode;
        $payload = [];

        if ($mode == "create") {
            $payload = [
                'mode' => "create"
            ];
        } else if ($mode == "end") {
            $job = Job::find(Auth::user()->running_job)->toArray();
            // dd($job);
            $payload = [
                'mode' => "end",
                'job' => $job,
                'start' => Carbon::parse(Auth::user()->running_job_start)->format('d/m/Y H:i'),
                'team_member' => Auth::user()->id
            ];
        }


        $this->dispatch('show-submit-job', payload: $payload)->self();
    }
}
