@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Job Details</h1>
    <ul>
        <li><strong>Name:</strong> {{ $job->name }}</li>
        <li><strong>Body Corporate:</strong> {{ $job->body_corporate }}</li>
        <li><strong>Plan:</strong> {{ $job->plan }}</li>
        <li><strong>Location:</strong> <a href="{{ $job->location_link }}" target="_blank">{{ $job->location }}</a></li>
        <li><strong>Scope:</strong> {{ $job->scope }}</li>
        <li><strong>Base Price:</strong> ${{ $job->base_price }}</li>
    </ul>

    <h3>Job Scope</h3>
    <ul>
        <li><strong>Lawn Care:</strong> {{ $job->job_scope->lawn_care ? 'Yes' : 'No' }}</li>
        <li><strong>Trimming:</strong> {{ $job->job_scope->trimming ? 'Yes' : 'No' }}</li>
        <!-- Add all other scope fields here -->
    </ul>

    <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
