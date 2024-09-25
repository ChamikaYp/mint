<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Jobs</h1>
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

          <!-- Action Buttons -->
          <div class="row mb-2">
              <div class="col text-right">
                  <button wire:click="openCreateModal" type="button" class="btn btn-success">
                      New Job
                  </button>
              </div>
          </div>

          <!-- Job List Card -->
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Jobs</h3>
              </div>
              <div class="card-body">
                  <!-- Search Bar -->
                  <div class="row">
                      <div class="form-group col-md-4">
                          {{-- <label for="filterJob">Job</label> --}}
                          <input wire:model.live="searchTerm" type="text" class="form-control" placeholder="Filter by Job Name or Body Corporate">
                      </div>
                  </div>

                  <!-- Job Table -->
                  <div class="row">
                    <div class="table-responsive">
                      <table id="example2" class="table table-bordered table-striped dtr-inline">
                          <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Job</th>
                                  <th>Body Corporate</th>
                                  {{-- <th>Plan</th> --}}
                                  <th>Location</th>
                                  <th>Base Price</th>
                                  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse ($jobs as $job)
                                  <tr>
                                      <td>{{ ($jobs->currentPage() - 1) * $jobs->perPage() + $loop->iteration }}</td>
                                      <td>{{ $job->name }}</td>
                                      <td>{{ $job->body_corporate }}</td>
                                      {{-- <td>{{ $job->plan }}</td> --}}
                                      <td><a href="{{ $job->location_link }}" target="_blank">{{ $job->location }}</a></td>
                                      <td>{{ $job->base_price }}</td>
                                      <td>
                                          <button wire:click="edit({{ $job->id }})" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                          <button wire:click="delete({{ $job->id }})" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                      </td>
                                  </tr>
                              @empty
                                  <tr>
                                      <td colspan="7">No jobs found.</td>
                                  </tr>
                              @endforelse
                          </tbody>
                      </table>
                    </div>
                  </div>

                  <!-- Pagination -->
                  <div class="d-flex justify-content-center">
                      {{ $jobs->links('vendor.pagination.adminlte') }}
                  </div>
              </div>
          </div>
      </div>
  </section>

  <!-- Create/Edit Modal -->
  <div wire:ignore.self class="modal fade" id="jobModal" tabindex="-1" role="dialog" aria-labelledby="jobModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <form wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}">
                  <div class="modal-header">
                      <h5 class="modal-title">{{ $modalTitle }}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetInputFields">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>

                  <div class="modal-body">
                    {{-- <h3>Job Details</h3> --}}
    <div class="d-flex justify-content-between btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn {{ $active ? 'bg-olive active' : 'bg-danger' }}">
            <input type="radio" name="active" id="active" wire:click="$set('active', true)" autocomplete="off" {{ $active ? 'checked' : '' }}> Active
        </label>
        <label class="btn {{ !$active ? 'bg-danger active' : 'bg-olive' }}">
            <input type="radio" name="inactive" id="inactive" wire:click="$set('active', false)" autocomplete="off" {{ !$active ? 'checked' : '' }}> Inactive
        </label>
    </div>
    <hr>
                    <!-- Form Fields -->
                    <div class="form-group">
                        <label for="name">Job Name</label>
                        <input type="text" class="form-control" wire:model="name" id="name" placeholder="Enter Job Name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                
                    <!-- Body Corporate -->
                    <div class="form-group">
                        <label for="body_corporate">Body Corporate</label>
                        <input type="text" class="form-control" wire:model="body_corporate" id="body_corporate" placeholder="Enter Body Corporate">
                        @error('body_corporate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                
                    <!-- Plan -->
                    <div class="form-group">
                        <label for="plan">Plan</label>
                        <input type="text" class="form-control" wire:model="plan" id="plan" placeholder="Enter Plan">
                        @error('plan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                
                    <!-- Location -->
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" wire:model="location" id="location" placeholder="Enter Location">
                        @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                
                    <!-- Location Link -->
                    <div class="form-group">
                        <label for="location_link">Location Link</label>
                        <input type="url" class="form-control" wire:model="location_link" id="location_link" placeholder="Enter Location Link">
                        @error('location_link') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                
                    <!-- Scope -->
                    <div class="form-group">
                        <label for="scope">Scope</label>
                        <textarea class="form-control" wire:model="scope" id="scope" placeholder="Enter Scope"></textarea>
                        @error('scope') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                
                    <!-- Base Price -->
                    <div class="form-group">
                        <label for="base_price">Base Price</label>
                        <input type="number" step="0.01" class="form-control" wire:model="base_price" id="base_price" placeholder="Enter Base Price">
                        @error('base_price') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                      <label for="frequency">Frequency</label>
                      <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">Every</span>
                        </div>
                          <input type="number" min="0" class="form-control" wire:model="frequency" id="frequency" placeholder="Enter Frequency">
                          <div class="input-group-append">
                              <span class="input-group-text">weeks</span>
                          </div>
                      </div>
                      @error('frequency') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                  {{-- <div class="form-group">
                    <label for="status">Status</label> <!-- Label for status -->
                    <div class="row">
                      <div class="btn-group btn-group-toggle" data-toggle="buttons">
                          <label class="btn {{ $active ? 'bg-olive active' : 'bg-danger' }}">
                              <input type="radio" name="active" id="active" wire:click="$set('active', true)" autocomplete="off" {{ $active ? 'checked' : '' }}> Active
                          </label>
                          <label class="btn {{ !$active ? 'bg-danger active' : 'bg-olive' }}">
                              <input type="radio" name="inactive" id="inactive" wire:click="$set('active', false)" autocomplete="off" {{ !$active ? 'checked' : '' }}> Inactive
                          </label>
                      </div>
                      @error('active') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                  </div> --}}
                    <!-- Default Team Members -->
<div class="form-group" wire:ignore>
  <label for="users">Default Team Members</label>

  <div class="select2-blue" wire:ignore>
    <select id="users" class="select2" multiple="multiple" data-placeholder="Select Members" data-dropdown-css-class="select2-blue" style="width: 100%;">
      @foreach($allUsers as $user)
          <option value="{{ $user->id }}">{{ $user->name }}</option>
      @endforeach
    </select>
  </div>
  @error('userIds') <span class="text-danger">{{ $message }}</span> @enderror
</div>

                
                    <!-- Job Scope Section -->
                    <label>Job Scope</label>
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="lawn_care" id="lawn_care">
                                <label class="form-check-label" for="lawn_care">Lawn Care</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="trimming" id="trimming">
                                <label class="form-check-label" for="trimming">Trimming</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="raking_up_leaf_litter" id="raking_up_leaf_litter">
                                <label class="form-check-label" for="raking_up_leaf_litter">Raking Up Leaf Litter</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="weeding" id="weeding">
                                <label class="form-check-label" for="weeding">Weeding</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="blowing" id="blowing">
                                <label class="form-check-label" for="blowing">Blowing</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="clearing_garden_beds" id="clearing_garden_beds">
                                <label class="form-check-label" for="clearing_garden_beds">Clearing Garden Beds</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="collecting_litter" id="collecting_litter">
                                <label class="form-check-label" for="collecting_litter">Collecting Litter</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="entrance_glass_cleaning" id="entrance_glass_cleaning">
                                <label class="form-check-label" for="entrance_glass_cleaning">Entrance Glass Cleaning</label>
                            </div>
                        </div>
                        <!-- Second Column -->
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="bringing_in_bins" id="bringing_in_bins">
                                <label class="form-check-label" for="bringing_in_bins">Bringing In Bins</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="remove_cob_webs" id="remove_cob_webs">
                                <label class="form-check-label" for="remove_cob_webs">Remove Cobwebs</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="vacuum" id="vacuum">
                                <label class="form-check-label" for="vacuum">Vacuum</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="clear_out_bin_corral" id="clear_out_bin_corral">
                                <label class="form-check-label" for="clear_out_bin_corral">Clear Out Bin Corral</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="clearing_drainage_gate" id="clearing_drainage_gate">
                                <label class="form-check-label" for="clearing_drainage_gate">Clearing Drainage Gate</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="vacuum_and_mop_laundry" id="vacuum_and_mop_laundry">
                                <label class="form-check-label" for="vacuum_and_mop_laundry">Vacuum and Mop Laundry</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="remove_waste_laundry" id="remove_waste_laundry">
                                <label class="form-check-label" for="remove_waste_laundry">Remove Waste Laundry</label>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="form-check">
                      <input type="checkbox" class="form-check-input" wire:model="active" id="active">
                      <label class="form-check-label" for="active">Active</label>
                  </div>
                  @error('active') <span class="text-danger">{{ $message }}</span> @enderror --}}
                  {{-- <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn {{ $active ? 'bg-olive active' : 'bg-danger' }}">
                        <input type="radio" name="active" id="active" wire:click="$set('active', true)" autocomplete="off" {{ $active ? 'checked' : '' }}> Active
                    </label>
                    <label class="btn {{ !$active ? 'bg-danger active' : 'bg-olive' }}">
                        <input type="radio" name="inactive" id="inactive" wire:click="$set('active', false)" autocomplete="off" {{ !$active ? 'checked' : '' }}> Inactive
                    </label>
                </div>
                @error('active') <span class="text-danger">{{ $message }}</span> @enderror --}}

                
              
                
                </div>
                

                  <div class="modal-footer">
                      <button type="button" wire:click="resetInputFields" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">{{ $updateMode ? 'Update' : 'Save' }}</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
  <!-- Delete Confirmation Modal -->
  <div wire:ignore.self class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Header and Body -->
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="cancelDelete">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this job?
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" wire:click="cancelDelete" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" wire:click="deleteConfirmed" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
  </div>
</div>

<!-- ... existing content ... -->

<!-- Event Listeners for Modal and Notifications -->
@script
<script>
    Livewire.on('openModal', () => {
      console.log("hi")
        $('#jobModal').modal('show');

        // $('#users').select2({
        //         placeholder: 'Select Team Members',
        //         allowClear: true
        //     });

        //     $('#users').on('change', function (e) {
        //         var data = $(this).val();
        //         @this.set('userIds', data);
        //     });

            $('.select2').select2()


            // Set the selected options based on the Livewire property
            // Get the userIds from Livewire
            let selectedUsers = @this.get('userIds');

            // If userIds is not null or undefined, set the value
            if (selectedUsers) {
                $('#users').val(selectedUsers).trigger('change');
            }

            // Listen for changes in Select2 and update Livewire property
            $('#users').on('change', function (e) {
                let data = $(this).val();
                // Update the Livewire property
                @this.set('userIds', data);
            });
    });

    Livewire.on('closeModal', () => {
        $('#jobModal').modal('hide');
    });

    Livewire.on('show-delete-confirmation', () => {
        $('#deleteConfirmationModal').modal('show');
    });

    Livewire.on('deleted', () => {
        $('#deleteConfirmationModal').modal('hide');
        toastr.success('Job Deleted Successfully.');
    });

    Livewire.on('resetJobId', () => {
        @this.jobId = null;
    });
</script>
@endscript


