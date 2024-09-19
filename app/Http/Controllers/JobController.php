<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobScope;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // List all jobs
    public function index()
    {
        $jobs = Job::with('job_scope')->paginate(10); // Include JobScope with jobs
        return view('jobs.index', compact('jobs'));
    }

    // Show create form
    public function create()
    {
        return view('jobs.create');
    }

    // Store a new job
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'body_corporate' => 'required',
            'plan' => 'required',
            'location' => 'required',
            'location_link' => 'required',
            'scope' => 'required',
            'base_price' => 'required|numeric',
        ]);

        $job = Job::create($request->only('name', 'body_corporate', 'plan', 'location', 'location_link', 'scope', 'base_price'));
        
        // Create job scope
        JobScope::create(array_merge($request->only([
            'lawn_care', 'trimming', 'raking_up_leaf_litter', 'weeding', 'blowing',
            'clearing_garden_beds', 'collecting_litter', 'entrance_glass_cleaning',
            'bringing_in_bins', 'remove_cob_webs', 'vacuum', 'clear_out_bin_corral',
            'clearing_drainage_gate', 'vacuum_and_mop_laundry', 'remove_waste_laundry'
        ]), ['job_id' => $job->id]));

        return redirect()->route('jobs.index')->with('success', 'Job created successfully.');
    }

    // Show job details
    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
    }

    // Show edit form
    public function edit(Job $job)
    {
        return view('jobs.edit', compact('job'));
    }

    // Update job
    public function update(Request $request, Job $job)
    {
        $request->validate([
            'name' => 'required',
            'body_corporate' => 'required',
            'plan' => 'required',
            'location' => 'required',
            'location_link' => 'required',
            'scope' => 'required',
            'base_price' => 'required|numeric',
        ]);

        $job->update($request->only('name', 'body_corporate', 'plan', 'location', 'location_link', 'scope', 'base_price'));

        // Update job scope
        $job->job_scope->update($request->only([
            'lawn_care', 'trimming', 'raking_up_leaf_litter', 'weeding', 'blowing',
            'clearing_garden_beds', 'collecting_litter', 'entrance_glass_cleaning',
            'bringing_in_bins', 'remove_cob_webs', 'vacuum', 'clear_out_bin_corral',
            'clearing_drainage_gate', 'vacuum_and_mop_laundry', 'remove_waste_laundry'
        ]));

        return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
    }

    // Delete job
    public function destroy(Job $job)
    {
        $job->job_scope()->delete();
        $job->delete();
        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }
}
