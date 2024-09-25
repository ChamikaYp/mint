<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tickets</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Success Message -->
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Create/Update Ticket Form -->
            <div class="card">
                <div class="card-header">
                    <h4>{{ $updateMode ? 'Update Ticket' : 'Create Ticket' }}</h4>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}">
                        <div class="form-group">
                            <label for="name">Ticket Name</label>
                            <input type="text" class="form-control" id="name" wire:model="name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" wire:model="description"></textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" wire:model="status">
                                <option value="Open">Open</option>
                                <option value="Pending">Pending</option>
                                <option value="On hold">On hold</option>
                                <option value="Solved">Solved</option>
                                <option value="Closed">Closed</option>
                            </select>
                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="job_id">Job</label>
                            <div wire:ignore>
                                <select class="form-control select2" id="job_id" style="width: 100%;">
                                    <option value="">-- Select Job --</option>
                                    @foreach($jobs as $job)
                                        <option value="{{ $job->id }}">{{ $job->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('job_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ $updateMode ? 'Update' : 'Save' }}</button>
                        @if($updateMode)
                            <button type="button" wire:click="resetInputFields" class="btn btn-secondary">Cancel</button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Filter: Jobs -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Filter by Job</h4>
                </div>
                <div class="card-body">
                    <div class="form-group" wire:ignore>
                        <label for="filterJob">Filter Tickets by Job</label>
                        <select class="form-control select2bs4" id="filterJob" style="width: 100%;">
                            <option value="">All Jobs</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}">{{ $job->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="statusFilter">Filter by Status</label>
                        <select wire:model.live="statusFilter" class="form-control" id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="Open">Open</option>
                            <option value="Pending">Pending</option>
                            <option value="On hold">On hold</option>
                            <option value="Solved">Solved</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Ticket List Card -->
            <div class="card mt-5">
                <div class="card-header">
                    <h4>Tickets List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Job</th>
                                    <th style="min-width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->name }}</td>
                                        <td>{{ Str::words($ticket->description, 20, '...') }}</td>
                                        <td>{{ $ticket->status }}</td>
                                        <td>{{ $ticket->job->name }}</td>
                                        <td style="min-width: 150px;">
                                            <a href="{{ url('tickets/' . $ticket->id) }}" target="_blank" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button wire:click="edit({{ $ticket->id }})" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $ticket->id }})" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $tickets->links() }}
            </div>
        </div>
    </section>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this ticket?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" wire:click="deleteConfirmed" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS for delete confirmation modal and Select2 handling -->
@script
<script>
    $(document).ready(function () {
        // Initialize Select2 for job filter
        $('#filterJob').select2({
            theme: 'bootstrap4',
        });
        $('#filterJob').on('change', function () {
            @this.set('selectedJob', $(this).val());
        });

        // Initialize Select2 for job selection in form
        $('#job_id').select2({
            theme: 'bootstrap4',
        });
        $('#job_id').on('change', function () {
            @this.set('job_id', $(this).val());
        });

        // Handle modal actions for delete confirmation
        Livewire.on('showDeleteConfirmation', () => {
            $('#deleteConfirmationModal').modal('show');
        });

        Livewire.on('hideDeleteConfirmation', () => {
            $('#deleteConfirmationModal').modal('hide');
        });

        Livewire.on('setJob', ({ payload }) => {
            $('#job_id').val(payload.jobId).trigger('change');
        });
        Livewire.on('unsetJob', () => {
            $('#job_id').val(null).trigger('change');
        });
    });

    // Whenever Livewire updates the page, reinitialize Select2
    Livewire.hook('message.processed', (message, component) => {
        $('#filterJob').select2({
            theme: 'bootstrap4',
        });
        $('#job_id').select2({
            theme: 'bootstrap4',
        });
    });
</script>
@endscript
