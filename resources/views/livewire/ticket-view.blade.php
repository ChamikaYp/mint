<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ticket Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Ticket Details</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Ticket Details Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Ticket: <strong>{{ $ticket->name }}</strong></h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Job Name:</strong> {{ $ticket->job ? $ticket->job->name : 'No Job Assigned' }}</p>
                            <p><strong>Status:</strong> {{ $ticket->status }}</p>
                            <p><strong>Description:</strong></p>
                            <p>{{ $ticket->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="timeline">
                        <!-- Display all comments -->
                        @foreach ($ticket->comments as $comment)
                        <div>
                            <i class="fas fa-comments bg-yellow"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}</span>
                                <h3 class="timeline-header"><a href="#">{{ $comment->user->name }}</a> commented:</h3>
                                <div class="timeline-body">
                                    {{ $comment->body }}
                                    @if ($comment->images->count() > 0)
                                    <div class="row m-3">
                                        <div class="">
                                            @foreach ($comment->images as $image)
                                                {{-- <a href="{{ Storage::url($image->path) }}" target="_blank" rel="noopener noreferrer">
                                                    <img src="{{ Storage::url($image->path) }}" alt="Comment Image" class="img-thumbnail ml-1 mr-1" width="300">
                                                </a> --}}
                                                <a href="{{ Storage::disk('comments')->url($image->path) }}" target="_blank">
                                                    <img src="{{ Storage::disk('comments')->url($image->path) }}" alt="Comment Image" class="img-thumbnail ml-1 mr-1" width="300">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <!-- End comments -->

                        <!-- Comment form -->
                        <div>
                            <i class="fas fa-pencil-alt bg-blue"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Write a comment</h3>
                                <div class="timeline-body">
                                    <form wire:submit.prevent="addComment">
                                        <div class="form-group">
                                            <textarea class="form-control" wire:model="newComment" placeholder="Type your comment..."></textarea>
                                            @error('newComment') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="imageUpload">Upload Images</label>
                                            <input type="file" class="form-control" wire:model="imageUploads" multiple> <!-- Allow multiple files -->
                                            @error('imageUploads.*') <span class="text-danger">{{ $message }}</span> @enderror
                                            
                                            <!-- Show a loading message or spinner while uploading -->
                                            <div wire:loading wire:target="imageUploads">
                                                <p class="text-warning">Uploading images, please wait...</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Disable the button while loading -->
                                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="imageUploads">
                                            Submit
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End comment form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
