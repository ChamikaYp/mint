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
            <div class="col-12 col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tasks"></i>
                            Due Jobs
                        </h3>
                    </div>
                    <div class="card-body pb-2 drag-container due-jobs">
                        @foreach ($due_jobs as $job)
                        <div ondragstart="event.target.classList.add('dragging')" ondragend="event.target.classList.remove('dragging')" class="draggable" draggable="true" style="background: #e91e63;"><img src="/dist/img/draggable-icon.svg">{{$job->name}}</div>
                        @endforeach
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tasks"></i>
                            All Jobs
                        </h3>
                    </div>
                    <div class="card-body pb-2 drag-container-all all-jobs">
                        @foreach ($due_jobs as $job)
                        <div ondragstart="event.target.classList.add('dragging-all')" ondragend="event.target.classList.remove('dragging-all')" class="draggable-all" draggable="true" style="background: #e91e63;"><img src="/dist/img/draggable-icon.svg">{{$job->name}}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-8">
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
                <div class="card col-12 col-sm-6">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar"></i>
                            Schedule
                        </h3>
                    </div>
                    <div class="card-body pb-2 drag-container drag-container-all scheduled-jobs">
                        @foreach ($scheduled_jobs as $job)
                            <div ondragstart="event.target.classList.add('no-dragging')" ondragend="event.target.classList.remove('no-dragging')" class="draggable" draggable="true" style="background: #e91e63;"><img src="/dist/img/draggable-icon.svg">{{$job->name}}</div>
                        @endforeach
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
        document.querySelectorAll('.drag-container').forEach(container => {
            container.addEventListener('dragover', e => {
                e.preventDefault()
                let afterElement = getDragAfterElement(container, e.clientY)
                let draggable = document.querySelector('.dragging')
                if (afterElement == null) {
                container.appendChild(draggable)
                } else {
                container.insertBefore(draggable, afterElement)
                }
            })
        })

        document.querySelectorAll('.drag-container-all').forEach(container => {
            container.addEventListener('dragover', e => {
                e.preventDefault()
                let afterElement = getDragAfterElement(container, e.clientY)
                let draggable = document.querySelector('.dragging-all')
                if (afterElement == null) {
                container.appendChild(draggable)
                } else {
                container.insertBefore(draggable, afterElement)
                }
            })
        })
        function getDragAfterElement(container, y) {
            let draggableElements = [...container.querySelectorAll('.draggable:not(.dragging), .draggable-all:not(.dragging-all)')]

            return draggableElements.reduce((closest, child) => {
                let box = child.getBoundingClientRect()
                let offset = y - box.top - box.height / 2
                if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child }
                } else {
                return closest
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element
        }

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
    })
</script>
@endscript