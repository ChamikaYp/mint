@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Job</h1>

    <form action="{{ route('jobs.update', $job->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('jobs.partials.form')
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
