@extends('layouts.app')
@section('content')
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
                <li class=""><a href="/tutor/dashboard" class="text-decoration-none px-3 py-2 d-block">
                        <img src="/icon images/dashboard.png" style="width:20px; margin-right: 10px;"> Dashboard
                    </a>
                </li>
                <li class=""><a href="/tutor/meetinglists" class="text-decoration-none px-3 py-2 d-block">
                        <img src="/icon images/meeting.png" style="width:20px; margin-right: 10px;"> Meetings
                    </a>
                </li>
                <li class=""><a href="/tutor/blogging" class="text-decoration-none px-3 py-2 d-block ">
                        <img src="/icon images/blogging.png" style="width:20px; margin-right: 10px;"> Blogging

                    </a>
                </li>
                <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
                        <img src="/icon images/notification.png" style="width:20px; margin-right: 10px;"> Notifications
                    </a>
                </li>

                <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
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
                <h2 class="fs-2 fw-bold">Meetings</h2>
                <!-- <div class=" form-group mb-4"> -->
                <div class="text-center fit mb-4 mt-4">
                    <a href="{{ route('student.meetingdetail.create') }}" class="btn btn-primary shadow-none">+ New
                        Meeting</a>
                </div>
                <!-- </div> -->
                <form action="{{ route('student.meetinglists') }}" method="GET">

                    <div class="form-group mb-4 row">
                        <div class="row">
                            <div class="col-md-3 mb-2 d-flex justify-content-center align-items-center">
                                <select class="form-select" name="meeting_type" id="selectDateMeeting"
                                    aria-label="Meeting Type">
                                    <option value="" {{ request('meeting_type') == '' ? 'selected' : '' }}>All
                                    </option>
                                    <option value="Real" {{ request('meeting_type') == 'Real' ? 'selected' : '' }}>Real
                                    </option>
                                    <option value="Virtual" {{ request('meeting_type') == 'Virtual' ? 'selected' : '' }}>
                                        Virtual</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 d-flex justify-content-center align-items-center">

                                <div class="input-group" id="datetimepicker">
                                    <input type="text" class="form-control" name="meeting_date" id="datepicker"
                                        value="{{ request('meeting_date') }}" placeholder="Select a date" readonly />
                                    <span class="input-group-text" id="datepicker-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2 d-flex justify-content-center align-items-center">


                                <select class="form-select form--control" name="tutor_id" id="selectTutorMeeting"
                                    aria-label="Floating label select example" disabled>
                                    {{-- <option value="" {{ request('tutor_id') == '' ? 'selected' : '' }}>-- Choose Tutor --</option> --}}
                                    @foreach ($assignedTutor as $allocated)
                                        <option value="{{ $allocated->tutor->id }}"
                                            {{ request('tutor_id') == $allocated->tutor->id ? 'selected' : '' }}>
                                            {{ $allocated->tutor->first_name }} {{ $allocated->tutor->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 d-flex flex-column align-items-start">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary shadow-none">Search</button>
                                    {{-- <a href="{{route('tutor.meetinglists')}}" class="btn btn-primary shadow-none">Search</a> --}}
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                <div class="table-responsive scroll-table">
                    <table class="table bg-white table-bordered card-table" id="table-meeting">
                        <tbody>
                            @forelse($meeting_schedules as $date => $dayMeetings)
                                <!-- header date -->
                                <tr class="no-top-border fixed-row">
                                    <td colspan="6" style="background-color: #F2F2F2;" class="no-border padding-left">
                                        {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                                    </td>
                                </tr>
                                @foreach ($dayMeetings as $meeting)
                                    <!-- meeting title -->
                                    <tr class="no-border fixed-row">
                                        <td colspan="6" style="font-size: 16px;" class="padding-left">
                                            {{ $meeting->meeting_title }}</td>
                                    </tr>
                                    <!-- meeting detail body -->
                                    <tr class="no-border special">
                                        <td style="width: 15%;" class="no-right-border padding-left special">
                                            {{ \Carbon\Carbon::parse($meeting->meeting_start_time)->format('h:i A') }} -
                                            {{ \Carbon\Carbon::parse($meeting->meeting_end_time)->format('h:i A') }}
                                        </td>
                                        <td style="width: 15%;color: #004AAD;" class="no-border special">Tutor</td>
                                        <td style="width: 15%;color: #004AAD;" class="no-border special"class="no-border special">Status
                                        </td>
                                        <td style="width: 15%;color: #004AAD;" class="no-border special" class="no-border special">Meeting
                                            type</td>
                                        <td class="w-25 no-border special" style="color: #004AAD;">
                                            @if ($meeting->meeting_type === 'virtual')
                                                Meeting link
                                            @else
                                                Location
                                            @endif
                                        </td>
                                        <td style="width: 15%;" rowspan="2"
                                            class="text-center no-left-border special"><a
                                                href="{{ route('student.meetingdetail.view', $meeting->id) }}"
                                                class="btn btn-primary shadow-none">Detail</a></td>
                                    </tr>
                                    <tr class="no-top-border special">
                                        <td style="width: 15%;" class="no-right-border padding-left special">
                                            {{ $meeting->meeting_platform }}</td>
                                        <td style="width: 15%;" class="no-border special">{{ $meeting->tutor_id }}
                                            ({{ $meeting->first_name }} {{ $meeting->last_name }})
                                        </td>
                                        <td style="width: 15%;" class="no-border special">
                                            <u>{{ ucfirst($meeting->meeting_status) }}</u>
                                        </td>
                                        <td style="width: 15%;" class="no-border special">{{ $meeting->meeting_type }}
                                        </td>
                                        <td class="w-25 no-border padding-bot special overflow">
                                            @if ($meeting->meeting_type === 'virtual')
                                                <a href="{{ $meeting->meeting_link }}"
                                                    target="_blank">{{ $meeting->meeting_link }}</a>
                                            @else
                                                {{ $meeting->meeting_location }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No meetings scheduled</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </div>

    </div>
@endsection
@push('scripts')
    <!-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
                <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        console.log("Script is loaded!");
        $(document).ready(function() {
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
        });

        // $(document).ready(function(){
        //     console.log("Data table is loading..");
        //     $('#table-meeting').DataTable({
        //         paging: true,
        //         pageLength: 5,
        //         lengthChange: false,
        //         searching: false,
        //         ordering: true,
        //         "language": {
        //             "info": "Total Records: _TOTAL_",
        //         }
        //     });
        // });


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
        //         window.addEventListener('DOMContentLoaded', (event) => {
        //     // When the page is loaded, we replace the history state so the back button won't lead to the old page
        //     if (window.history.replaceState) {
        //         window.history.replaceState(null, null, window.location.href);
        //     }
        // });

        // window.addEventListener("pageshow", function(event) {
        //     if (event.persisted) {
        //         // If the page was loaded from the cache (back button), reload it
        //         location.reload();
        //     }
        // });
        //for refreshing data
        // window.addEventListener('DOMContentLoaded', (event) => {
        //     // Check if there's a success message and reload the page
        //     if ({{ session('success') ? 'true' : 'false' }}) {
        //         location.reload();  // This will refresh the page
        //     }
        // });
    </script>
@endpush
