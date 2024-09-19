@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Job</h1>

    <form action="{{ route('jobs.store') }}" method="POST">
        @csrf
        @include('jobs.partials.form')
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection
