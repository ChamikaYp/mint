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

class CompletedJobs extends Component
{
    use WithFileUploads;
    
    public $jobs;
    public $users;
    public $auth_user;
    public $completed_jobs;
    public $photos = [];
    public $start_job_scope;
    public $running_job;
    public $running_job_start;

    public function mount() 
    {
        $this->jobs = Job::all();
        $this->users = User::all();
    }
    public function render()
    {
        $this->completed_jobs = CompletedJob::all();
        $this->auth_user = Auth::user();
        $this->running_job = Job::find(Auth::user()->running_job)->toArray();
        $this->running_job_start = Carbon::parse(Auth::user()->running_job_start)->format('d/m/Y H:m');
        return view('livewire.completed-jobs', [
            'jobs' => $this->jobs,
            'users' => $this->users,
            'completed_jobs' => $this->completed_jobs,
            'start_job_scope' => $this->start_job_scope,
            'auth_user' => $this->auth_user,
            'running_job' => $this->running_job,
        ]);
    }
    #[On('delete-submission')] 
    public function deleteCompletedJob($completed_job_id)
    {
        CompletedJob::find($completed_job_id)->delete();
    }

    #[On('submission-created')] 
    public function addNewCompletedJob($editJobId, $jobId, $teamMembers, $startTime, $endTime, $weedSpray, $weedSprayUsed, $greenWaste, $bulbsReplaced, $lawnCare, $trimming, $rakingUpLeafLitter, $weeding, $blowing, $clearingGardenBeds, $collectingLitter, $entranceGlassCleaning, $bringingInBins, $removeCobWebs, $vacuum, $clearOutBinCorral, $clearingDrainageGate, $vacuumAndMopLaundry, $removeWasteLaundry, $notes, $extraWork, $price, $weedSprayCharge, $extraCharge, $GSTCharge, $totalPrice)
    {
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
            $completed_job->price = $price;
            $completed_job->weed_spray_charge = $weedSprayCharge;
            $completed_job->extra_charge = $extraCharge;
            $completed_job->gst_charge = $GSTCharge;
            $completed_job->total_price = $totalPrice;
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
            $completed_job->price = $price;
            $completed_job->weed_spray_charge = $weedSprayCharge;
            $completed_job->extra_charge = $extraCharge;
            $completed_job->gst_charge = $GSTCharge;
            $completed_job->total_price = $totalPrice;
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
        ]);
        // CompletedJob::find($completed_job_id)->delete();
    }

    #[On('start-job')] 
    public function startJob($job_id, $start_time)
    {
        Auth::user()->running_job = $job_id;
        Auth::user()->running_job_start = Carbon::parse($start_time);
        Auth::user()->save();
        // dd($job_id, $start_time, Auth::user() );
    }
}
