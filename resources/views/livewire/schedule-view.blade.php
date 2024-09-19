<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Schedule</h1>
            </div>
        </div>
        </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="input-group date" id="scheduledate" data-target-input="nearest">
                                <input data-date="@this" id="scheduledateinput" type="text" class="form-control datetimepicker-input" data-target="#scheduledate"/>
                                <div class="input-group-append" data-target="#scheduledate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            <ul class="ml-auto p-2 pagination pagination-month justify-content-center">
                            <li id="prev-day" class="page-item"><a class="page-link" href="#">«</a></li>
                            <li class="page-item active">
                                <a class="page-link" href="#">
                                    <p id="current-month" class="page-year">{{ $dateMonth}}</p>
                                    <p id="current-date"class="page-month">{{ $dateDate }}</p>
                                    <p id="current-day"class="page-year">{{ $dateDay }}</p>
                                </a>
                            </li>
                            <li id="next-day" class="page-item"><a class="page-link" href="#">»</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i>
                            Jobs
                        </h3>
                    </div>
                    <div class="card-body mb-2" style="height: 500px; overflow-y: auto;">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Job</th>
                                    <th>Status</th>
                                    <th>Overdue Weeks</th>
                                    <th>Scheduled Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jobStatuses as $status)
                                <tr>
                                    <td>
                                        @if($status['type'] == 1)
                                            <span class="badge bg-success" style="font-size: 1em;">{{ $status['job'] }}</span> 
                                        @elseif($status['type'] == 2)
                                            <span class="badge bg-primary" style="font-size: 1em;">{{ $status['job'] }}</span>
                                        @elseif($status['type'] == 3)
                                            <span class="badge bg-warning" style="font-size: 1em;">{{ $status['job'] }}</span>
                                        @elseif($status['type'] == 4)
                                            <span class="badge bg-danger" style="font-size: 1em;">{{ $status['job'] }}</span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 1em;">{{ $status['job'] }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $status['status'] }}</td>
                                    <td>{{ $status['overdueWeeks'] ?? 'N/A' }}</td>
                                    <td>{{ $status['scheduledDate'] ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar"></i>
                            Job Search
                        </h3>
                    </div>
                    <div class="card-body pb-2 drag-container drag-container-all scheduled-jobs mb-2">
                        <div class="form-group">
                            <label for="name">Search Job</label>
                            <input type="text" class="form-control" id="name" wire:model.live="searchTerm">
                        </div>
                        @foreach ($filteredJobs as $filteredJob)
                            {{-- <button>{{ $filteredJob->name }}</button> --}}
                            {{-- <button class="btn btn-warning" style="">
                                <strong>{{ $filteredJob->name }}</strong> 
                            </button> --}}
                            <button class="btn btn-warning" wire:click="scheduleJob({{ $filteredJob->id }})">
                                {{ $filteredJob->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar"></i>
                            Scheduled
                        </h3>
                    </div>
                    <div class="card-body pb-2 drag-container drag-container-all scheduled-jobs">
                        @if ($scheduledJobs->isNotEmpty())
                            @foreach ($scheduledJobs as $schedule)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <!-- Display Job Name -->
                                    <span>{{ $schedule->job->name }}</span>
                                    
                                    <!-- Delete Button -->
                                    <button class="btn btn-danger btn-sm" wire:click="deleteSchedule({{ $schedule->id }})">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <p>No jobs scheduled</p>
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
      </div>
    </section>
</div>

@script
<script>
    $(function () {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $('#scheduledate').datetimepicker({
            format: 'L',
            format: 'DD/MM/YYYY',
        });

        $('#scheduledate').on("change.datetimepicker", function (e) {
            Livewire.dispatch('date-change', { date: $(this).data('date') });
        })

        $('#scheduledate').datetimepicker("date", moment());
        
        document.querySelector('#prev-day').addEventListener('click', () => {
            $('#scheduledate').datetimepicker("date", $('#scheduledate').datetimepicker("date").subtract(1, 'days'));
        });
        document.querySelector('#next-day').addEventListener('click', () => {
            $('#scheduledate').datetimepicker("date", $('#scheduledate').datetimepicker("date").add(1, 'days'));
        });

        Livewire.on('error', ({ payload }) => {
            Toast.fire({
                icon: 'error',
                title: payload.error
              })
        });
        Livewire.on('message', ({ payload }) => {
            Toast.fire({
                icon: 'success',
                title: payload.message
              })
        });

    })
</script>
@endscript