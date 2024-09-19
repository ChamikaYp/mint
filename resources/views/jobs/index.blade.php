@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Jobs List</h1>
    <a href="{{ route('jobs.create') }}" class="btn btn-primary mb-3">Create New Job</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Job</th>
                <th>Body Corporate</th>
                <th>Plan</th>
                <th>Location</th>
                <th>Base Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobs as $job)
            <tr>
                <td>{{ $job->id }}</td>
                <td>{{ $job->name }}</td>
                <td>{{ $job->body_corporate }}</td>
                <td>{{ $job->plan }}</td>
                <td><a href="{{ $job->location_link }}" target="_blank">{{ $job->location }}</a></td>
                <td>${{ $job->base_price }}</td>
                <td>
                    <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-info">View</a>
                    <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $jobs->links() }} <!-- Pagination links -->
</div>
@endsection
