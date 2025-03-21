@extends('layouts.app')
@section('content')
    <div class="main-container d-flex">



        <div class="main-container d-flex">
            <div class="sidebar" id="side_nav">
                <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
                    <h1 class=" header-title fs-2 mt-3">
                        <span class="text-white fw-bold" style="margin-left:10px;">TripleEDU</span>
                    </h1>

                    <button class="btn d-md-none d-block close-btn px-1 py-0 text-white">
                        <i class="fa-solid fa-square-xmark"></i></button>
                </div>

                <ul class="list-unstyled px-2">
                    <ul class="list-unstyled px-2">
                        <li class=""><a href="/student/dashboard" class="text-decoration-none px-3 py-2 d-block">
                                <img src="/icon images/dashboard.png" style="width:20px; margin-right: 10px;"> Dashboard
                            </a>
                        </li>
                        <li class=""><a href="/student/meetinglists" class="text-decoration-none px-3 py-2 d-block">
                                <img src="/icon images/meeting.png" style="width:20px; margin-right: 10px;"> Meetings
                            </a>
                        </li>
                        <li class=""><a href="/student/blogging" class="text-decoration-none px-3 py-2 d-block ">
                                <img src="/icon images/blogging.png" style="width:20px; margin-right: 10px;"> Blogging
                        <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
                                <img src="/icon images/notification.png" style="width:20px; margin-right: 10px;">
                                Notifications
                            </a>
                        </li>

                        <li class=""><a href="/student/report" class="text-decoration-none px-3 py-2 d-block">
                                <img src="/icon images/reports.png" style="width:20px; margin-right: 10px;"> Reports
                            </a>
                        </li>

                    </ul>



            </div>


            <div class="content">
                <nav class="navbar navbar-expand-md navbar-light bg-light">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between d-md-none d-block">
                            <button class="btn px-1 py-0 open-btn me-2"><i class="fa-solid fa-bars"></i></button>


                        </div>


                    </div>
                </nav>


                <div class="dashboard-content px-3 pt-4">
                    <span onclick="history.back()" style="cursor: pointer;" class="header-text">
                        <i class="fa-solid fa-chevron-left"></i> <u>Back</u>
                    </span>
                    <div class="form-group mb-4 mt-4">
                        <form id="meetingForm"
                            action="{{ isset($meeting_schedules->id) ? route('update', $meeting_schedules->id) : route('save') }}"
                            method="POST" enctype="multipart/form-data">

                            @csrf
                            @if (isset($meeting_schedules->id))
                                @method('PUT')
                            @endif
                            <!-- Hidden input to track action type (for status)-->
                            <input type="hidden" name="action_type" id="action_type" value="save_update">
                            <div class="chart-container">

                                <div class="chart-card chart-card-full meeting-card">
                                    <div class="chart-card-header">
                                        <div class="row hidden-title update-title-div">
                                            <div class="col-md-6 mt-4 d-flex justify-content-center align-items-center">
                                                <h4 class="chart-card-title" style="font-size:1rem;">Reschedule Meeting</h4>
                                            </div>
                                        </div>
                                        <div class="row hidden-title detail-title-div">
                                            <div class="col-md-2 mt-4 d-flex justify-content-center align-items-center">

                                            </div>
                                            <div class="col-md-2 mt-4 d-flex justify-content-center align-items-center">
                                                <h4 class="chart-card-title" style="font-size:1rem;">Meeting Detail</h4>
                                            </div>
                                            @if (isset($meeting_schedules))
                                                <div class="col-md-2 mt-4 d-flex align-items-start flex-column ">
                                                    <div class="text-center">
                                                        <button type="submit" onclick="setAction('toggle_status')"
                                                            class="btn btn-primary shadow-none" style="width: auto;"
                                                            @if ($meeting_schedules->meeting_status === 'cancelled') disabled @endif>

                                                            {{ $meeting_schedules->meeting_status === 'completed' ? 'Mark as new' : 'Mark as Complete' }}


                                                        </button>

                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="row hidden-title new-title-div">
                                            <div class="col-md-6 mt-4 d-flex justify-content-center align-items-center">
                                                <h4 class="chart-card-title" style="font-size:1rem;">Create New Meeting
                                                </h4>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="chart-card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="normal-text">
                                                    Meeting Title *
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="normal-text">
                                                    <input type="text" name="meeting_title"
                                                        value="{{ $meeting_schedules ? $meeting_schedules->meeting_title : old('meeting_title') }}"
                                                        {{ $readOnly ? 'readonly' : '' }} class="form-control"
                                                        placeholder="Add title" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="normal-text">
                                                    Tutor
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                @php $disabled = $readOnly ? 'disabled' : '' @endphp
                                                <select class="form-select" id="selectTutorMeetingDetail"
                                                    disabled name="tutor_id" 
                                                    aria-label="Floating label select example">
                                                    
                                                    
                                                    {{-- Show the currently assigned student (even if not allocated) --}}
                                                    {{-- @if ($currentStudent && !in_array($currentStudent->id, $students->pluck('student.id')->toArray()))
                                                        <option value="{{ $currentStudent->id }}" selected>
                                                            {{ $currentStudent->first_name }}
                                                            {{ $currentStudent->last_name }} (Unassigned)
                                                        </option>
                                                    @endif

                                                    {{-- Choose Student Option --}}
                                                    {{-- <option value="" disabled
                                                        {{ empty($meeting_schedules->student_id) ? 'selected' : '' }}>
                                                        -- Choose Student --
                                                    </option> --}} 

                                                    {{-- Allocated Students --}}
                                                    @foreach ($assignedTutor as $allocated)
                                                        <option value="{{ $allocated->tutor->id }}"
                                                            {{ request('tutor_id') == $allocated->tutor->id ? 'selected' : '' }}>
                                                            @if(!$isTutorAllocated)
                                                            {{ $allocated->tutor->first_name }}
                                                            {{ $allocated->tutor->last_name }}
                                                                (Unassigned)
                                                            @else
                                                            {{ $allocated->tutor->first_name }}
                                                            {{ $allocated->tutor->last_name }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="normal-text">
                                                    Date *
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="input-group normal-text">
                                                    <input type="text" name="meeting_date"
                                                        value="{{ $meeting_schedules ? $meeting_schedules->meeting_date : old('meeting_date') }}"
                                                        {{ $readOnly ? 'readonly' : '' }} class="form-control"
                                                        id="meetingdatepicker" placeholder="Select a date" readonly
                                                        required />
                                                    <span class="input-group-text" id="datepicker-icon">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2 mb-2">
                                                <div class="normal-text">
                                                    Start time *
                                                </div>
                                            </div>
                                            <div class="col-md-2 mb-2">

                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <div class="normal-text">
                                                    End time *
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2 mb-2">
                                                <input id="starttimepicker" type="text" name="meeting_start_time"
                                                    {{ $readOnly ? 'readonly' : '' }}
                                                    value="{{ old('meeting_start_time', isset($meeting_schedules->meeting_start_time) ? \Carbon\Carbon::parse($meeting_schedules->meeting_start_time)->format('H:i') : '') }}
"
                                                    class="form-control" required />

                                            </div>
                                            <div class="col-md-2 mb-2 d-flex justify-content-center align-items-center">
                                                <div class="normal-text">
                                                    <i class="fa-solid fa-right-long fa-2xl"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <input id="endtimepicker" type="text" name="meeting_end_time"
                                                    {{ $readOnly ? 'readonly' : '' }}
                                                    value="{{ old('meeting_end_time', isset($meeting_schedules->meeting_end_time) ? \Carbon\Carbon::parse($meeting_schedules->meeting_end_time)->format('H:i') : '') }}
"
                                                    class="form-control" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="normal-text">
                                                    Meeting type *
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="meeting_type"
                                                        id="realmeeting" value="real" data-target=".location-div"
                                                        {{ $readOnly ? 'disabled' : '' }}
                                                        {{ old('meeting_type', $meeting_schedules->meeting_type ?? '') === 'real' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="realmeeting">Real</label>
                                                </div>
                                            </div>
                                            <div class="col-md-1 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="meeting_type"
                                                        id="virtualmeeting" value="virtual"
                                                        data-target=".platform-div,.link-div"
                                                        {{ $readOnly ? 'disabled' : '' }}
                                                        {{ old('meeting_type', $meeting_schedules->meeting_type ?? '') === 'virtual' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="virtualmeeting">Virtual</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row location-div hidden-div">
                                            <div class="col-md-6 mb-2">
                                                <div class="normal-text">
                                                    Location *
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row location-div hidden-div">
                                            <div class="col-md-6 mb-2">
                                                <div class="normal-text">
                                                    <input type="text" name="meeting_location"
                                                        value="{{ $meeting_schedules ? $meeting_schedules->meeting_location : old('meeting_location') }}"
                                                        {{ $readOnly ? 'readonly' : '' }} class="form-control"
                                                        placeholder="Add room, floor, building" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row platform-div hidden-div">
                                            <div class="col-md-6 mb-2">
                                                <div class="normal-text">
                                                    Platform *
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row platform-div hidden-div">
                                            <div class="col-md-6 mb-2">
                                                <select class="form-select" name="meeting_platform" {{ $disabled }}
                                                    id="selectMeetingType" aria-label="Floating label select example">
                                                    <option value="Zoom Meeting"
                                                        {{ isset($meeting_schedules->meeting_platform) && $meeting_schedules->meeting_platform == 'Zoom Meeting' ? 'selected' : '' }}>
                                                        Zoom Meeting</option>
                                                    <option value="Google Meet"
                                                        {{ isset($meeting_schedules->meeting_platform) && $meeting_schedules->meeting_platform == 'Google Meet' ? 'selected' : '' }}>
                                                        Google Meet</option>
                                                    <option value="Teams Meeting"
                                                        {{ isset($meeting_schedules->meeting_platform) && $meeting_schedules->meeting_platform == 'Teams Meeting' ? 'selected' : '' }}>
                                                        Teams Meeting</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row link-div hidden-div">
                                            <div class="col-md-6 mb-2">
                                                <div class="normal-text">
                                                    Meeting Link *
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row link-div hidden-div">
                                            <div class="col-md-6 mb-2">
                                                <div class="normal-text">
                                                    <input type="text" name="meeting_link"
                                                        value="{{ $meeting_schedules ? $meeting_schedules->meeting_link : old('meeting_link') }}"
                                                        {{ $readOnly ? 'readonly' : '' }} class="form-control"
                                                        placeholder="Add link" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="normal-text">
                                                    Description
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="normal-text">
                                                    <textarea class="form-control" name="meeting_description" {{ $readOnly ? 'readonly' : '' }}>{{ $meeting_schedules ? $meeting_schedules->meeting_description : old('meeting_description') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        @if (request()->routeIs('student.meetingdetail.create'))
                                            <div class="row hidden-button new-button-div">
                                                <div class="col-md-6 mb-2 mt-2">
                                                    <button type="submit"
                                                        class="btn btn-primary shadow-none full-button">Save</button>
                                                </div>
                                            </div>
                                        @elseif(request()->routeIs('student.meetingdetail.update'))
                                            <div class="row hidden-button update-button-div">
                                                <div class="col-md-6 mb-2 mt-2">
                                                    <button type="submit"
                                                        class="btn btn-primary shadow-none full-button">Update</button>
                                                </div>
                                            </div>
                                        @endif
                                        @if (isset($meeting_schedules))
                                            <div class="row hidden-button detail-button-div">
                                                <div class="col-md-6 mb-2 mt-2">
                                                    <button type="button" class="btn btn-primary shadow-none full-button"
                                                        @if (
                                                            $meeting_schedules->meeting_status === 'completed' ||
                                                                $meeting_schedules->meeting_status === 'cancelled' ||
                                                                $meeting_schedules-> meeting_status === 'overdue' ||
                                                                !$isTutorAllocated) disabled @endif><a
                                                            href="{{ route('student.meetingdetail.update', $meeting_schedules->id ?? '') }}"
                                                            class="text-decoration-none"
                                                            style="color: white;">Reschedule</a></button>
                                                </div>
                                            </div>
                                        @endif
                                        @if (isset($meeting_schedules))
                                            <div class="row hidden-button detail-button-div">
                                                <div class="col-md-6 mb-2 mt-1">
                                                    <button type="button" data-id="{{ $meeting_schedules->id }}"
                                                        class="btn btn-secondary shadow-none full-button delete"
                                                        @if ($meeting_schedules->meeting_status === 'completed' || $meeting_schedules->meeting_status === 'cancelled') disabled @endif>Cancel
                                                        Meeting</button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>



            </div>
        </div>

    </div>

    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight: 500;">Cancel confirmation!</h5>
                    <button type="button" class="confirm-btn close btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form action="{{ route('student.meetingdetail.cancelmeeting') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="modal-body">
                            <div class="form-group">
                                <p style="font-family: 'Poppins'; font-size:1rem;">Are you sure you want to cancel this
                                    meeting?</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="submit" class="btn btn-primary"
                            style="background-color: #004AAD; width: 90px;">Confirm</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"
                            style=" width: 90px;">Close</i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="errorModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight: 500;">Validation Errors!</h5>
                    <button type="button" class="confirm-btn close btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        @foreach ($errors->all() as $error)
                            <li style="font-family: 'Poppins'; font-size:1rem;margin-bottom:10px;">
                                {{ $error }}</li>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer ">
                    <button onclick="closeModal()" type="button" class="btn btn-danger" data-bs-dismiss="modal"
                        aria-label="Close" style=" width: 90px;">Close</i></button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Script for the side bar nav
        $(".sidebar ul li").on('click', function() {
            $(".sidebar ul li.active").removeClass('active');
            $(this).addClass('active');
        });

        $('.open-btn').on('click', function() {
            $('.sidebar').addClass('active');

        });


        $('.close-btn').on('click', function() {
            $('.sidebar').removeClass('active');

        });

        $(document).ready(function() {


            $('#meetingdatepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,

                todayHighlight: true,
                clickOpens: false,
                allowInput: false

            });
            // Disable datepicker if $readOnly is true
            @if ($readOnly)
                // Disable the datepicker for readonly mode
                $('#meetingdatepicker').prop('disabled', true); // Disable field completely
            @endif


            // Only set default if no value is set
            if (!$('input[name="meeting_type"]:checked').val()) {
                $('input[name="meeting_type"][value="real"]').prop('checked', true);
                $(".location-div").show();
            }

            $('input[name="meeting_type"]').change(function() {
                $(".hidden-div").hide();
                let targets = $(this).data("target").split(",");
                targets.forEach(function(divClass) {
                    $(divClass.trim()).show();
                });
            });

            // Trigger change event on page load to show the correct div based on selected meeting type
            $('input[name="meeting_type"]:checked').trigger('change');
        });
        @if ($readOnly)
            const startPicker = flatpickr("#starttimepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K",
                minuteIncrement: 15,
                clickOpens: false,
                allowInput: false,
                formatDate: function(date) {
                    const hours = date.getHours() % 12 || 12; // Convert to 12-hour format
                    const paddedHours = hours.toString().padStart(2, "0"); // Add leading zero
                    const minutes = date.getMinutes().toString().padStart(2, "0"); // Add leading zero
                    const ampm = date.getHours() < 12 ? "AM" : "PM";
                    return `${paddedHours}:${minutes} ${ampm}`;
                }
            });
            const endPicker = flatpickr("#endtimepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K",
                minuteIncrement: 15,
                clickOpens: false,
                allowInput: false,
                formatDate: function(date) {
                    const hours = date.getHours() % 12 || 12; // Convert to 12-hour format
                    const paddedHours = hours.toString().padStart(2, "0"); // Add leading zero
                    const minutes = date.getMinutes().toString().padStart(2, "0"); // Add leading zero
                    const ampm = date.getHours() < 12 ? "AM" : "PM";
                    return `${paddedHours}:${minutes} ${ampm}`;
                }
            });
        @else
            const startPicker = flatpickr("#starttimepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K",
                minuteIncrement: 15,
                formatDate: function(date) {
                    const hours = date.getHours() % 12 || 12; // Convert to 12-hour format
                    const paddedHours = hours.toString().padStart(2, "0"); // Add leading zero
                    const minutes = date.getMinutes().toString().padStart(2, "0"); // Add leading zero
                    const ampm = date.getHours() < 12 ? "AM" : "PM";
                    return `${paddedHours}:${minutes} ${ampm}`;
                }
            });
            const endPicker = flatpickr("#endtimepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i K",
                minuteIncrement: 15,
                formatDate: function(date) {
                    const hours = date.getHours() % 12 || 12; // Convert to 12-hour format
                    const paddedHours = hours.toString().padStart(2, "0"); // Add leading zero
                    const minutes = date.getMinutes().toString().padStart(2, "0"); // Add leading zero
                    const ampm = date.getHours() < 12 ? "AM" : "PM";
                    return `${paddedHours}:${minutes} ${ampm}`;
                }

            });
        @endif

        var currentRoute = "{{ Route::currentRouteName() }}";
        var currentRouteId = "{{ request()->route('id') }}";
        var isEditPage = currentRoute === 'student.meetingdetail.update';

        if (currentRouteId) {
            if (isEditPage) {
                $(".update-title-div").show();
                $(".update-button-div").show();

            } else {
                $(".detail-title-div").css('display', 'flex').show();
                $(".detail-button-div").show();
            }
        } else {
            $(".new-title-div").show();
            $(".new-button-div").show();
        }

        function setAction(action) {
            document.getElementById('action_type').value = action;

            @if (isset($meeting_schedules))
                if (action === 'toggle_status') {
                    document.getElementById('meetingForm').action =
                        "{{ route('student.meetingdetail.toggleStatus', $meeting_schedules->id) }}";
                } else {
                    document.getElementById('meetingForm').action =
                        "{{ route('update', $meeting_schedules->id) }}";
                }
            @else
                document.getElementById('meetingForm').action =
                    "{{ route('save') }}"; // Fallback route if no meeting is available
            @endif
        }
        $('.delete').on('click', function() {
            var modal = $('#deleteModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.modal('show');
        });

        function closeModal() {
            document.getElementById('errorModal').style.display = 'none';
        }
        // function toggleMeetingType() {
        //     const real = document.getElementById('realmeeting').checked;
        //     document.querySelector('.location-div').style.display = real ? 'block' : 'none';
        //     document.querySelector('.platform-div').style.display = real ? 'none' : 'block';
        //     document.querySelector('.link-div').style.display = real ? 'none' : 'block';
        // }
    </script>
    @if ($errors->any())
        <script>
            // window.errors = @json($errors->all());
            var myModal = new bootstrap.Modal(document.getElementById('errorModal'));
            myModal.show();
        </script>
    @endif

@endpush
