<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Job Submissions</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Simple Tables</li>
            </ol>
            </div>
        </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col text-right">
                  <button data-source="submit-job" id="newSubmission" type="button" class="btn btn-success" data-toggle="modal" data-target="#jobModal">
                    New Submission
                  </button>
                  @if ($auth_user == null || $auth_user->running_job == null)
                  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#startJobModal">
                    Start Job
                  </button>
                  @else
                  <button data-source="end-job" data-running-job="{{$auth_user->running_job}}" type="button" class="btn btn-danger" data-toggle="modal" data-target="#jobModal">
                    End Job
                  </button>
                  @endif
                </div>
            </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Completed Jobs</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Job</th>
                    <th>Date</th>
                    <th>Invoiced</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($completed_jobs as $completed_job)
                    <tr>
                      <td>{{$completed_job->id}}</td>
                      <td>{{$completed_job->job->name}}</td>
                      <td>{{$completed_job->getDateAttribute()}}</td>
                      <td><input {{$completed_job->invoiced == 0 ? "" : "checked"}} wire:click="toggleInvoice({{$completed_job->id}})" type="checkbox"></td>
                      <td>
                        <button wire:click="viewClick({{$completed_job->id}})" class="btn btn-info btn-sm">
                          <i class="fas fa-eye"></i>
                        </button>
                        <button wire:click="editClick({{$completed_job->id}})" class="btn btn-warning btn-sm">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button data-toggle="modal" data-target="#deleteConfirmationModal" data-id="{{$completed_job->id}}" class="btn btn-danger btn-sm">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div><!-- /.container-fluid -->
    </section>

    <div wire:ignore class="modal fade" id="startJobModal" tabindex="-1" role="dialog" aria-labelledby="startJobModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Start a Job</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Job</label>
                                <select id="selectedJobStart" class="form-control select2bs4" style="width: 100%;">
                                  <option selected value="-1">Choose a Job</option>
                                  @foreach($jobs as $job)
                                    <option value="{{$job->id}}">{{$job->name}}</option>
                                  @endforeach
                                </select>
                            </div>
                          </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                          <div id="startJobScope"></div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="startJob" type="button" class="btn btn-primary">Start</button>
              </div>
            </div>
          </div>
    </div>

    <div wire:ignore class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete this item?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
          </div>
        </div>
      </div>
    </div>
    <div wire:ignore class="modal fade" id="jobModal" tabindex="-1" role="dialog" aria-labelledby="jobModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="jobModalLabel">Submit Job Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container-fluid">
                <div class="row">
                  <!-- Job Field -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Job</label>
                      <select id="selectedJob" class="form-control select2bs4" style="width: 100%;">
                        <option selected value="-1">Choose a Job</option>
                        @foreach($jobs as $job)
                          <option data-price="{{$job->base_price}}" value="{{$job->id}}">{{$job->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <!-- Team Members -->
                  <div class="col-md-8">
                    <div class="form-group">
                        <label>Team Members</label>
                        <div class="select2-blue">
                          <select id="teamMembers" class="select2" multiple="multiple" data-placeholder="Select Members" data-dropdown-css-class="select2-blue" style="width: 100%;">
                            @foreach($users as $user)
                              <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                  </div>
                </div>
                <div class="row">
                  <!-- Date -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="date">Date</label>
                      <div class="input-group date" id="date" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#date"/>
                        <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Start Time -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="startTime">Start Time</label>
                      <div class="input-group date" id="startTime" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#startTime"/>
                        <div class="input-group-append" data-target="#startTime" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- End Time -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="endTime">End Time</label>
                      <div class="input-group date" id="endTime" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#endTime"/>
                        <div class="input-group-append" data-target="#endTime" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <!-- Weed Spray Used -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="weedSprayUsed">Weed Spray</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                  <input type="checkbox" id="weedSpray">
                                </span>
                              </div>
                          <input type="number" class="form-control" id="weedSprayUsed" value="0">
                          <div class="input-group-append">
                            <span class="input-group-text">ml</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Green Waste -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="greenWaste">Green Waste</label>
                        <div class="input-group">
                          <input type="number" class="form-control" id="greenWaste" value="0">
                          <div class="input-group-append">
                            <span class="input-group-text">bags</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="bulbsReplaced">Bulbs Replaced</label>
                        <div class="input-group">
                          <input type="number" class="form-control" id="bulbsReplaced" value="0">
                          <div class="input-group-append">
                            <span class="input-group-text">bulbs</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <!-- Lawn care -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="lawnCare">
                          <label class="form-check-label" for="lawnCare">Lawn Care</label>
                        </div>
                      </div>
                    </div>
                    <!-- Trimming -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="trimming">
                          <label class="form-check-label" for="trimming">Trimming</label>
                        </div>
                      </div>
                    </div>
                    <!-- Raking Up Leaf Litter -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="rakingUpLeafLitter">
                          <label class="form-check-label" for="rakingUpLeafLitter">Raking Up Leaf Litter</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <!-- Weeding -->
                    <div class="col-md-4">
                        <div class="form-group">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="weeding">
                            <label class="form-check-label" for="weeding">Weeding</label>
                          </div>
                        </div>
                      </div>
                    <!-- Blowing -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="blowing">
                          <label class="form-check-label" for="blowing">Blowing</label>
                        </div>
                      </div>
                    </div>
                    <!-- Clearing Garden Beds -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="clearingGardenBeds">
                          <label class="form-check-label" for="clearingGardenBeds">Clearing Garden Beds</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                      <!-- Collecting Litter -->
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="collectingLitter">
                            <label class="form-check-label" for="collectingLitter">Collecting Litter</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="entranceGlassCleaning">
                            <label class="form-check-label" for="entranceGlassCleaning">Entrance Glass Cleaning</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="bringingInBins">
                            <label class="form-check-label" for="bringingInBins">Bringing in Bins</label>
                          </div>
                        </div>
                      </div>
                  </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="removeCobWebs">
                            <label class="form-check-label" for="removeCobWebs">Remove Cob Webs</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="vacuum">
                            <label class="form-check-label" for="vacuum">Vacuum</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="clearOutBinCorral">
                            <label class="form-check-label" for="clearOutBinCorral">Clear Out Bin Corral</label>
                          </div>
                        </div>
                      </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="clearingDrainageGate">
                            <label class="form-check-label" for="clearingDrainageGate">Clearing Drainage Gate</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="vacuumAndMopLaundry">
                            <label class="form-check-label" for="vacuumAndMopLaundry">Vacuum and Mop - Laundry</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="removeWasteLaundry">
                            <label class="form-check-label" for="removeWasteLaundry">Remove Waste - Laundry</label>
                          </div>
                        </div>
                      </div>
                </div>
                <div class="row">
                  <!-- Notes -->
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="notes">Notes</label>
                      <textarea class="form-control" id="notes" rows="3"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <!-- Extra Work -->
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="extraWork">Extra Work</label>
                      <textarea class="form-control" id="extraWork" rows="3"></textarea>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="extraCharge">Extra Charge</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="number" class="form-control" id="extraCharge" value="0">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="weedSprayCharge">Weed Spray Invoice</label>
                      <div class="input-group">
                        <input type="number" class="form-control" id="weedSprayCharge" value="0">
                        <div class="input-group-append">
                          <span class="input-group-text">l</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="images">Images</label>
                        <div id="custom-file" class="custom-file">
                          {{-- Inserted By JS --}}
                        </div>
                        <div id="imagePreview" class="mt-3"></div>
                        {{-- <small id="fileHelp" class="form-text text-muted">You can upload multiple images. Only JPEG, PNG, and GIF files are allowed.</small> --}}
                      </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                  <div>
                      <label>Base Price: </label>
                      <span id="baseprice">$0</span>
                  </div>
                </div>
                <div class="d-flex justify-content-end">
                  <div>
                      <label>Weed Spray: </label>
                      <span id="weedsspraycharge">$0</span>
                  </div>
                </div>
                <div class="d-flex justify-content-end">
                  <div>
                      <label>Green Waste: </label>
                      <span id="greenwastecharge">$0</span>
                  </div>
                </div>
                <div class="d-flex justify-content-end">
                  <div>
                      <label>Bulbs: </label>
                      <span id="bulbscharge">$0</span>
                  </div>
                </div>
                <div class="d-flex justify-content-end">
                  <div>
                      <label>Extra Charge: </label>
                      <span id="extrachargeprice">$0</span>
                  </div>
                </div>
                <hr>
                <div class="d-flex justify-content-end">
                  <div>
                      <label>Price: </label>
                      <span id="price">$0</span>
                  </div>
                </div>
                <div class="d-flex justify-content-end">
                  <div>
                    <label>GST: </label>
                    <span id="priceGST">$0</span>
                  </div>
                </div>
                <div class="d-flex justify-content-end">
                  <div>
                    <label>Total: </label>
                    <span id="totalPrice">$0</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button id="saveChangesButton" type="button" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </div>
    </div>
</div>

@script
<script>
  moment.fn.changeTime = function(timeString) {
    let m1 = moment(timeString, 'hh:mm A');
    return this.set({h: m1.hours(), m: m1.minutes()});
  }

    let selectedFiles = [];

    let editJobID = null;
    let endJobID = null;
    let price;
    let extraCharge;
    let GSTCharge;
    let totalPrice;

    function updatePrice() {
      jobPrice = $('#selectedJob option:selected').data('price') != null ? parseFloat($('#selectedJob option:selected').data('price')) : 0;
      weedSprayCharge = parseFloat($('#weedSprayCharge').val() != '' ? $('#weedSprayCharge').val() * 25 : 0);
      greenWasteCharge = parseFloat($('#greenWaste').val() != '' ? $('#greenWaste').val() * 12 : 0);
      bulbsCharge = parseFloat($('#bulbsReplaced').val() != '' ? $('#bulbsReplaced').val() * 20 : 0);
      extraCharge = parseFloat($('#extraCharge').val() != '' ? parseFloat($('#extraCharge').val()) : 0);
      price = parseFloat(jobPrice + weedSprayCharge + greenWasteCharge + bulbsCharge + extraCharge).toFixed(2);
      GSTCharge = parseFloat(price*0.1).toFixed(2);
      totalPrice = parseFloat(price*1.1).toFixed(2);
      $("#baseprice").html("$"+jobPrice);
      $("#weedsspraycharge").html("$"+weedSprayCharge);
      $("#greenwastecharge").html("$"+greenWasteCharge);
      $("#bulbscharge").html("$"+bulbsCharge);
      $("#extrachargeprice").html("$"+extraCharge);
      $("#price").html("$"+price);
      $("#priceGST").html("$"+GSTCharge);
      $("#totalPrice").html("$"+totalPrice);
    }

    $(document).ready(function() {
      initializeImageUpload();
      $('#newSubmission').click(() => {
        
      });

        $('#date').datetimepicker({
            format: 'L',
            format: 'DD/MM/YYYY',
        });

        // Initialize time picker for start time
        $('#startTime').datetimepicker({
            format: 'LT'
        });

        // Initialize time picker for end time
        $('#endTime').datetimepicker({
            format: 'LT'
        });
        $('.select2').select2()

        //Initialize Select2 Elements
        $('#selectedJob').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#jobModal')
        })
        $('#selectedJobStart').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#startJobModal')
        })
        $('#jobModal').on('shown.bs.modal', function (e) {
        });

        $('#selectedJob').on('change', function (e) {
            updatePrice();
        });

        $('#selectedJobStart').on('change', function (e) {
          let id = $(this).val();
          if (id != -1) {
            Livewire.dispatch('change-start-job', {
              job_id: id
            });
            console.log(id);
          }
        });
        
        $('#greenWaste').on('change', function (e) {
            updatePrice();
        });
        $('#bulbsReplaced').on('change', function (e) {
            updatePrice();
        });
        $('#extraCharge').on('change', function (e) {
            updatePrice();
        });
        $('#weedSprayCharge').on('change', function (e) {
            updatePrice();
        });
   
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $('#jobModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget); // Button that triggered the modal
            let source = button.data('source'); // Extract info from data-* attributes
            console.log('Modal opened by: ' + source);
            if (source == "end-job") {
              endJobID = button.data('running-job');
              job = $wire.get('running_job');
              startedTime = $wire.get('running_job_start');
              console.log(startedTime);
              $('#selectedJob').val(job.id).trigger('change');
              $('#date').datetimepicker("date", moment(startedTime, 'DD/MM/YYYY H:m'));
              $('#startTime').datetimepicker("date", moment(startedTime, 'DD/MM/YYYY H:m'));
            }
        });

        let deleteItemId = null;

        $('#deleteConfirmationModal').on('show.bs.modal', function (event) {
          const button = $(event.relatedTarget); // Button that triggered the modal
          deleteItemId = button.data('id'); // Extract info from data-* attributes
        });

        document.getElementById('confirmDelete').addEventListener('click', function () {
          if (deleteItemId !== null) {
            // Perform the delete action here (e.g., send an AJAX request to the server)
            console.log('Deleting item with ID:', deleteItemId);

            Livewire.dispatch('delete-submission', { 
              completed_job_id: deleteItemId,
            });
            // Close the modal
            $('#deleteConfirmationModal').modal('hide');
          }
        });

        function restrictInputToNumbers(selector) {
            $(selector).on('input', function() {
                const currentValue = this.value;
                const regex = /^-?\d*\.?\d*$/;
                if (!regex.test(currentValue)) {
                    this.value = this.value.replace(/[^0-9.]/g, '');
                    const parts = this.value.split('.');
                    if (parts.length > 2) {
                        this.value = parts[0] + '.' + parts.slice(1).join('');
                    }
                }
            });
            $(selector).on('keypress', function(event) {
                if (event.key === 'e' || event.key === 'E') {
                    event.preventDefault();
                }
            });
        }
        restrictInputToNumbers('#weedSprayUsed');
        restrictInputToNumbers('#greenWaste');
        restrictInputToNumbers('#bulbsReplaced');
        restrictInputToNumbers('#extraCharge');
        restrictInputToNumbers('#weedSprayCharge');

        $('#saveChangesButton').click(function() {
            if ($('#selectedJob').val() == -1) {
              Toast.fire({
                icon: 'error',
                title: 'Select a Job'
              })
            } else if ($('#teamMembers').val().length == 0) {
              Toast.fire({
                icon: 'error',
                title: 'Select at leaset 1 Team Member'
              })
            } else if ($('#date').datetimepicker("date") == null) {
              Toast.fire({
                icon: 'error',
                title: 'Date is required'
              })
            } else if ($('#startTime').datetimepicker("date") == null) {
              Toast.fire({
                icon: 'error',
                title: 'Start Time is required'
              })
            } else if ($('#endTime').datetimepicker("date") == null) {
              Toast.fire({
                icon: 'error',
                title: 'End Time is required'
              })
            } else {
              let startTime = moment($('#date').datetimepicker("date").format()).changeTime($('#startTime').datetimepicker("date").format('hh:mm A')).format();
              let endTime = moment($('#date').datetimepicker("date").format()).changeTime($('#endTime').datetimepicker("date").format('hh:mm A')).format();

              if (startTime > endTime) {
                Toast.fire({
                  icon: 'error',
                  title: 'Start Time must be before End Time'
                }) 
              } else {
                Livewire.dispatch('submission-created', { 
                  editJobId: editJobID,
                  jobId: parseFloat($('#selectedJob').val()),
                  teamMembers: $('#teamMembers').val(),
                  startTime: startTime,
                  endTime: endTime,
                  weedSpray: $('#weedSpray').is(':checked'),
                  weedSprayUsed: parseFloat($('#weedSprayUsed').val()),
                  greenWaste: parseFloat($('#greenWaste').val()),
                  bulbsReplaced: parseFloat($('#bulbsReplaced').val()),
                  lawnCare: $('#lawnCare').is(':checked'),
                  trimming: $('#trimming').is(':checked'),
                  rakingUpLeafLitter: $('#rakingUpLeafLitter').is(':checked'),
                  weeding: $('#weeding').is(':checked'),
                  blowing: $('#blowing').is(':checked'),
                  clearingGardenBeds: $('#clearingGardenBeds').is(':checked'),
                  collectingLitter: $('#collectingLitter').is(':checked'),
                  entranceGlassCleaning: $('#entranceGlassCleaning').is(':checked'),
                  bringingInBins: $('#bringingInBins').is(':checked'),
                  removeCobWebs: $('#removeCobWebs').is(':checked'),
                  vacuum: $('#vacuum').is(':checked'),
                  clearOutBinCorral: $('#clearOutBinCorral').is(':checked'),
                  clearingDrainageGate: $('#clearingDrainageGate').is(':checked'),
                  vacuumAndMopLaundry: $('#vacuumAndMopLaundry').is(':checked'),
                  removeWasteLaundry: $('#removeWasteLaundry').is(':checked'),
                  notes: $('#notes').val(),
                  extraWork: $('#extraWork').val(),
                  price: price,
                  weedSprayCharge: parseFloat($('#weedSprayCharge').val()),
                  extraCharge: extraCharge,
                  GSTCharge: GSTCharge,
                  totalPrice: totalPrice,
                });
  
                $('#jobModal').modal('hide');
                Toast.fire({
                  icon: 'success',
                  title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
                })
                updatePrice();
              }
            }
        });

        $("#startJob").on("click", () => {
          if ($("#selectedJobStart").val() == -1) {
            Toast.fire({
              icon: 'error',
              title: 'Select a Job'
            })
          } else {
            $('#startJobModal').modal('hide');
            Livewire.dispatch('start-job', {
              job_id: $("#selectedJobStart").val(),
              start_time: moment().format()
            });
          }
        });

    });
    
    $('#jobModal').on('hidden.bs.modal', function (e) {
      updateField('selectedJob', -1);
      updateField('teamMembers', null);
      $('#date').datetimepicker("date", null);
      $('#startTime').datetimepicker("date", null);
      $('#endTime').datetimepicker("date", null);
      updateField('weedSpray', false);
      updateField('weedSprayUsed', 0);
      updateField('greenWaste', 0);
      updateField('bulbsReplaced', 0);
      updateField('lawnCare', false);
      updateField('trimming', false);
      updateField('rakingUpLeafLitter', false);
      updateField('weeding', false);
      updateField('blowing', false);
      updateField('clearingGardenBeds', false);
      updateField('collectingLitter', false);
      updateField('entranceGlassCleaning', false);
      updateField('bringingInBins', false);
      updateField('removeCobWebs', false);
      updateField('vacuum', false);
      updateField('clearOutBinCorral', false);
      updateField('clearingDrainageGate', false);
      updateField('vacuumAndMopLaundry', false);
      updateField('removeWasteLaundry', false);
      updateField('notes', null);
      updateField('extraWork', null);
      updateField('extraCharge', 0);
      updateField('weedSprayCharge', 0);
      let editJobID = null;
      let endJobID = null;
      let price = null;
      let weedSprayCharge = null;
      let extraCharge = null;
      let GSTCharge = null;
      let totalPrice = null;
      initializeImageUpload();
      $('#saveChangesButton').prop( "disabled", false );
      enableAllFields()
    })

    let images;
    function initializeImageUpload() {
        selectedFiles = [];
        $('#custom-file').html('<input type="file" class="custom-file-input" id="images" multiple><label class="custom-file-label" for="images">Choose files</label>');
        $('#imagePreview').html('');
        document.querySelector('#images').addEventListener('change', function (e) {
            const files = Array.from(e.target.files);
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            let valid = true;

            for (const file of files) {
            if (!allowedTypes.includes(file.type)) {
                valid = false;
                break;
            }

            selectedFiles.push(file);

            const reader = new FileReader();
            reader.onload = function (e) {
                const container = document.createElement('div');
                container.style.display = 'inline-block';
                container.style.position = 'relative';
                container.style.marginRight = '10px';
                container.style.marginBottom = '10px';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px';
                img.style.border = '1px solid #ddd';
                img.style.borderRadius = '4px';
                img.style.padding = '5px';

                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = 'Remove';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '5px';
                removeBtn.style.right = '5px';
                removeBtn.style.backgroundColor = 'red';
                removeBtn.style.color = 'white';
                removeBtn.style.border = 'none';
                removeBtn.style.borderRadius = '4px';
                removeBtn.style.padding = '2px 5px';
                removeBtn.style.cursor = 'pointer';

                removeBtn.onclick = function () {
                container.remove();
                const index = selectedFiles.indexOf(file);
                if (index > -1) {
                    selectedFiles.splice(index, 1);
                }
                // Update the file input
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(f => dataTransfer.items.add(f));
                document.querySelector('#images').files = dataTransfer.files;
                document.querySelector('.custom-file-label').innerHTML = Array.from(dataTransfer.files).map(f => f.name).join(', ');
                imagesChanged();
                };

                container.appendChild(img);
                container.appendChild(removeBtn);
                document.getElementById('imagePreview').appendChild(container);
            }
            reader.readAsDataURL(file);
            }

            if (!valid) {
            alert('Only JPEG, PNG, and GIF files are allowed.');
            e.target.value = '';
            document.querySelector('.custom-file-label').innerHTML = 'Choose files';
            document.getElementById('imagePreview').innerHTML = '';
            selectedFiles.length = 0; // Clear the selected files
            } else {
            // Update the file input
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(f => dataTransfer.items.add(f));
            document.querySelector('#images').files = dataTransfer.files;
            document.querySelector('.custom-file-label').innerHTML = Array.from(dataTransfer.files).map(f => f.name).join(', ');
            }
            imagesChanged();
            
        });
    }

    function imagesChanged() {
      $('#saveChangesButton').prop( "disabled", true );
      $wire.uploadMultiple('photos', $('#images').prop('files'), (uploadedFilename) => {
        console.log(uploadedFilename);
        $('#saveChangesButton').prop( "disabled", false );
      });
    };

    function updateField(fieldId, value) {
        const field = document.getElementById(fieldId);

        if (!field) {
            console.warn(`Field with id "${fieldId}" not found.`);
            return;
        }

        switch (field.type) {
            case 'checkbox':
                field.checked = Boolean(value);
                break;
            case 'select-multiple':
                $(field).val(value).trigger('change'); // Use jQuery for select2
                break;
            case 'select-one':
                $(field).val(value).trigger('change'); // Use jQuery for select2
                break;
            case 'file':
                initializeImageUpload();
                document.getElementById('imagePreview').innerHTML = ''; // Clear image previews
                break;
            default:
                field.value = value;
                break;
        }

        // Handle additional cases if necessary
    }

    function logSelectedImages() {
        const imagesInput = document.getElementById('images');
        const files = imagesInput.files;

        if (files.length === 0) {
            console.log('No files selected.');
        } else {
            console.log(`Number of selected files: ${files.length}`);
            Array.from(files).forEach(file => {
            console.log(`File name: ${file.name}`);
            console.log(`File size: ${file.size} bytes`);
            console.log(`File type: ${file.type}`);
            console.log(`-----------------------`);
            });
        }
    }

    function disableAllFields() {
        // Disable input fields
        $('#selectedJob').prop('disabled', true);
        $('#teamMembers').prop('disabled', true);
        $('#weedSprayUsed').prop('disabled', true);
        $('#greenWaste').prop('disabled', true);
        $('#bulbsReplaced').prop('disabled', true);
        $('#extraCharge').prop('disabled', true);
        $('#weedSprayCharge').prop('disabled', true);
        
        $('input[type="checkbox"]').prop('disabled', true);
        $('input[type="file"]').prop('disabled', true);

        // Disable textareas
        $('textarea').prop('disabled', true);

        // Disable DateTimePickers
        $('#date').datetimepicker('disable');
        $('#startTime').datetimepicker('disable');
        $('#endTime').datetimepicker('disable');
    }

    function enableAllFields() {
        // Enable input fields
        $('#selectedJob').prop('disabled', false);
        $('#teamMembers').prop('disabled', false);
        $('#weedSprayUsed').prop('disabled', false);
        $('#greenWaste').prop('disabled', false);
        $('#bulbsReplaced').prop('disabled', false);
        $('#extraCharge').prop('disabled', false);
        $('#weedSprayCharge').prop('disabled', false);

        // Enable checkboxes
        $('input[type="checkbox"]').prop('disabled', false);

        // Enable file input
        $('input[type="file"]').prop('disabled', false);

        // Enable textareas
        $('textarea').prop('disabled', false);

        // Enable DateTimePickers
        $('#date').datetimepicker('enable');
        $('#startTime').datetimepicker('enable');
        $('#endTime').datetimepicker('enable');
    }

    function createImagePreview(url, viewMode) {
        const container = document.createElement('div');
        container.style.display = 'inline-block';
        container.style.position = 'relative';
        container.style.marginRight = '20px'; // Increased margin for better spacing
        container.style.marginBottom = '20px'; // Increased margin for better spacing

        const img = document.createElement('img');
        img.src = url;
        img.style.maxWidth = '150px'; // Increased size of the image
        img.style.border = '1px solid #ddd';
        img.style.borderRadius = '4px';
        img.style.padding = '5px';

        container.appendChild(img);

        if (viewMode) {
          const downloadBtn = document.createElement('button');
          downloadBtn.innerHTML = 'Download';
          downloadBtn.style.position = 'absolute';
          downloadBtn.style.bottom = '5px'; // Moved the button to the bottom for better layout
          downloadBtn.style.right = '5px'; // Moved the button to the right for better layout
          downloadBtn.style.backgroundColor = 'blue'; // Changed color to blue
          downloadBtn.style.color = 'white';
          downloadBtn.style.border = 'none';
          downloadBtn.style.borderRadius = '4px';
          downloadBtn.style.padding = '2px 5px';
          downloadBtn.style.cursor = 'pointer';

          downloadBtn.onclick = function () {
              downloadImage(url);
          };

          container.appendChild(downloadBtn);
        }
        document.getElementById('imagePreview').appendChild(container);
    }

    function downloadImage(imageUrl) {
        const filename = imageUrl.substring(imageUrl.lastIndexOf('/') + 1);
        const downloadLink = document.createElement('a');
        downloadLink.href = imageUrl;
        downloadLink.download = filename;
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }

    Livewire.on('show-start-scope', ({ payload }) => {
      console.log(payload.scope);
      $('#startJobScope').html(payload.scope.replace(/\n/g, '<br>'));
    });

    Livewire.on('show-job', ({ payload }) => {
      payload.jobImages.forEach(url => {
          createImagePreview(url, true);
      });
      $('#selectedJob').val(payload.job.job_id).trigger('change');
      updateField('teamMembers', payload.teamMembers);
      $('#date').datetimepicker("date", moment(payload.startTime, 'DD/MM/YYYY H:m'));
      $('#startTime').datetimepicker("date", moment(payload.startTime, 'DD/MM/YYYY H:m'));
      $('#endTime').datetimepicker("date", moment(payload.endTime, 'DD/MM/YYYY H:m'));
      updateField('weedSpray', payload.job.weed_spray);
      updateField('weedSprayUsed', payload.job.weed_spray_used);
      updateField('greenWaste', payload.job.green_waste);
      updateField('bulbsReplaced', payload.job.bulbs_replaced);
      updateField('lawnCare', payload.job.lawn_care);
      updateField('trimming', payload.job.trimming);
      updateField('rakingUpLeafLitter', payload.job.raking_up_leaf_litter);
      updateField('weeding', payload.job.weeding);
      updateField('blowing', payload.job.blowing);
      updateField('clearingGardenBeds', payload.job.clearing_garden_beds);
      updateField('collectingLitter', payload.job.collecting_litter);
      updateField('entranceGlassCleaning', payload.job.entrance_glass_cleaning);
      updateField('bringingInBins', payload.job.bringing_in_bins);
      updateField('removeCobWebs', payload.job.remove_cob_webs);
      updateField('vacuum', payload.job.vacuum);
      updateField('clearOutBinCorral', payload.job.clear_out_bin_corral);
      updateField('clearingDrainageGate', payload.job.clearing_drainage_gate);
      updateField('vacuumAndMopLaundry', payload.job.vacuum_and_mop_laundry);
      updateField('removeWasteLaundry', payload.job.remove_waste_laundry);
      updateField('notes', payload.job.notes);
      updateField('extraWork', payload.job.extra_work);
      updateField('extraCharge', payload.job.extra_charge);
      updateField('weedSprayCharge', payload.job.weed_spray_charge);
      disableAllFields();
      updatePrice();
      $('#saveChangesButton').prop( "disabled", true );
      $('#jobModal').modal('show');
    })

    Livewire.on('edit-job', ({ payload }) => {
      editJobID = payload.job.id;
      payload.jobImages.forEach(url => {
          createImagePreview(url, false);
      });
      $('#selectedJob').val(payload.job.job_id).trigger('change');
      updateField('teamMembers', payload.teamMembers);
      $('#date').datetimepicker("date", moment(payload.startTime, 'DD/MM/YYYY H:m'));
      $('#startTime').datetimepicker("date", moment(payload.startTime, 'DD/MM/YYYY H:m'));
      $('#endTime').datetimepicker("date", moment(payload.endTime, 'DD/MM/YYYY H:m'));
      updateField('weedSpray', payload.job.weed_spray);
      updateField('weedSprayUsed', payload.job.weed_spray_used);
      updateField('greenWaste', payload.job.green_waste);
      updateField('bulbsReplaced', payload.job.bulbs_replaced);
      updateField('lawnCare', payload.job.lawn_care);
      updateField('trimming', payload.job.trimming);
      updateField('rakingUpLeafLitter', payload.job.raking_up_leaf_litter);
      updateField('weeding', payload.job.weeding);
      updateField('blowing', payload.job.blowing);
      updateField('clearingGardenBeds', payload.job.clearing_garden_beds);
      updateField('collectingLitter', payload.job.collecting_litter);
      updateField('entranceGlassCleaning', payload.job.entrance_glass_cleaning);
      updateField('bringingInBins', payload.job.bringing_in_bins);
      updateField('removeCobWebs', payload.job.remove_cob_webs);
      updateField('vacuum', payload.job.vacuum);
      updateField('clearOutBinCorral', payload.job.clear_out_bin_corral);
      updateField('clearingDrainageGate', payload.job.clearing_drainage_gate);
      updateField('vacuumAndMopLaundry', payload.job.vacuum_and_mop_laundry);
      updateField('removeWasteLaundry', payload.job.remove_waste_laundry);
      updateField('notes', payload.job.notes);
      updateField('extraWork', payload.job.extra_work);
      updateField('extraCharge', payload.job.extra_charge);
      updateField('weedSprayCharge', payload.job.weed_spray_charge);
      updatePrice();
      $('input[type="file"]').prop('disabled', true);
      $('#jobModal').modal('show');
    })
</script>
@endscript