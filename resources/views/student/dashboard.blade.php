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

                        </a>
                    </li>
                    <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
                            <img src="/icon images/notification.png" style="width:20px; margin-right: 10px;"> Notifications
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

                <section class="p-3">
                    <div class="dashboard-content px-2 pt-4">
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h2 class="fs-2 fw-bold"> Student Dashboard</h2>
                            </div>
                            <div class="col-md-6 header-text text-end">
                                Tutor - {{ $tutorName }}
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="chart-container">
                                <div class="chart-card col-md-6">
                                    <div class="chart-card-header">
                                        <h5 class="chart-card-category"></h5>
                                        <h3 class="chart-card-title">Meeting count</h3>
                                    </div>
                                    <div class="chart-card-body">

                                        <div class="chart-area">
                                            <canvas id="MeetingCountChart" class="chart-canvas"></canvas>
                                            {{-- <div class="box-align-right">
                                                <a href="#" class="small-text">View Report>>></a>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="parent-post">
                                    <div class="post-title">
                                        Posts
                                    </div>
                                    <div class="rounded-box col-md-6">
                                        <div style="width: 100%;" class="flex-step-box">
                                            <div class="top-row">
                                                @if ($postCount !== 0)
                                                    <div class="center-text large-font">
                                                        {{ $postCount }}
                                                    </div>
                                                    <div class="center-text">
                                                        new posts by tutor
                                                    </div>
                                                @else
                                                    <div class="center-text">
                                                        No new post by tutor
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="box-align-bottom center-text">
                                                <a href="#"> View posts>></a>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="chart-container">
                                <div class="chart-card">
                                    <div class="chart-card-header">
                                        <h5 class="chart-card-category"></h5>
                                        <h3 class="chart-card-title">My activities</h3>
                                    </div>
                                    <div class="chart-card-body">
                                        <div class="chart-area">
                                            <canvas id="StudentActivityChart" class="chart-canvas"></canvas>
                                            <div class="box-align-right">
                                                <a href="#" class="small-text">View Report>>></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chart-card">
                                    <div class="chart-card-header">
                                        <h5 class="chart-card-category"></h5>
                                        <h3 class="chart-card-title">Tutor activities</h3>
                                    </div>
                                    <div class="chart-card-body">
                                        <div class="chart-area">
                                            <canvas id="TutorActivityChart" class="chart-canvas"></canvas>
                                            <div class="box-align-right">
                                                <a href="#" class="small-text">View Report>>></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="chart-container">
                                <div class="chart-card chart-card-full">
                                    <div class="chart-card-header">
                                        <h4 class="chart-card-title">Upcoming meetings</h4>
                                    </div>
                                    <div class="chart-card-body">
                                        <div class="table-responsive">
                                            <table class="table tablesorter" id="student-upcoming-meetings">
                                                <thead>
                                                    <tr>
                                                        <th class="normal-text" style="width: 30%;">
                                                            Meeting Title
                                                        </th>
                                                        <th class="normal-text" style="width: 15%;">
                                                            Date
                                                        </th>
                                                        <th class="normal-text" style="width: 20%;">
                                                            Time
                                                        </th>
                                                        <th class="normal-text" style="width: 15%;">
                                                            Meeting Type
                                                        </th>
                                                        <th class="text-center normal-text" style="width: 20%;">
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @if ($meetings->isEmpty())
                                                        <tr>
                                                            <td colspan="5" class="text-muted py-4">
                                                                <i class="fas fa-calendar-times"></i> No Meetings Available
                                                            </td>
                                                        </tr>
                                                    @else
                                                        @foreach ($meetings as $meeting)
                                                            <tr>
                                                                <td class="normal-text">{{ $meeting->meeting_title }}</td>
                                                                <td class="normal-text">
                                                                    {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}
                                                                </td>
                                                                <td class="normal-text">
                                                                    {{ \Carbon\Carbon::parse($meeting->meeting_start_time)->format('h:i A') }}
                                                                    -
                                                                    {{ \Carbon\Carbon::parse($meeting->meeting_end_time)->format('h:i A') }}
                                                                </td>
                                                                <td class="normal-text">{{ $meeting->meeting_type }}</td>

                                                                <td class="text-center">
                                                                    <a href="{{ route('student.meetingdetail.view', $meeting->id) }}"
                                                                        class="btn btn-primary shadow-none">Detail</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif


                                                </tbody>

                                            </table>
                                            <div id="pagination-container"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>



            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="/bootstrap-5.0.2-dist/js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script src="{{ asset('js/chart_student.js') }}"></script>


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
            Chart.register(ChartDataLabels);

            var studentActivityChartElement = document.getElementById('StudentActivityChart');
            if (studentActivityChartElement && typeof demo !== 'undefined') {
                demo.initStudentActivityChart();
            }
            var tutorActivityChartElement = document.getElementById('TutorActivityChart');
            if (tutorActivityChartElement && typeof demo !== 'undefined') {
                demo.initTutorActivityChart();
            }
            var meetingCountChartElement = document.getElementById('MeetingCountChart');
            if (meetingCountChartElement && typeof demo !== 'undefined') {
                demo.initMeetingCountChart();
            }

            console.log("Data table is loading..");
            $('#student-upcoming-meetings').DataTable({
                dom: 'rt<"bottom"ip>',
                paging: true,
                pageLength: 5,
                lengthChange: false,
                searching: false,
                scrollY: '280px',
                ordering: true,
                "language": {
                    "info": "Total Records: _TOTAL_",
                }
            });
            $('.bottom').appendTo("#pagination-container");


        });
    </script>
@endpush
