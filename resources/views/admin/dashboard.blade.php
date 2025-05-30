@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')
    <div class="main-container d-flex">

        <div class="sidebar" id="side_nav">
            <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
                <h1 class=" header-title fs-2 mt-3">
                    <span class="text-white fw-bold" style="margin-left:10px;">TripleEDU</span>
                </h1>

                <button class="btn d-md-none d-block close-btn px-1 py-0  text-white">
                    <i class="fa-solid fa-square-xmark"></i>
                </button>
            </div>

            <ul class="list-unstyled px-2">


                <li class="active"><a href="/admin/dashboard" class="text-decoration-none px-3 py-2 d-block">
                        <img src="/icon images/dashboard.png" style="width:20px; margin-right: 10px;"> Dashboard
                    </a></li>
                <li class=""><a href="/admin/allocation" class="text-decoration-none px-3 py-2 d-block">
                        <img src="/icon images/allocation.png" style="width:20px; margin-right: 10px;" /> Allocation
                    </a></li>
                <li class=""><a href="/admin/assignedlists" class="text-decoration-none px-3 py-2 d-block">
                        <img src="/icon images/assigned list.png" style="width:20px; margin-right: 10px;"> Assigned List
                    </a></li>
                <li class=""><a href="/admin/tutorlists" class="text-decoration-none px-3 py-2 d-block">
                        <img src="/icon images/tutor.png" style="width:20px; margin-right: 10px;"> Tutor
                    </a></li>
                <li class=""><a href="/admin/studentlists" class="text-decoration-none px-3 py-2 d-block">
                        <img src="/icon images/student.png" style="width:20px; margin-right: 10px;"> Student
                    </a></li>
                <li class=""><a href="/admin/report" class="text-decoration-none px-3 py-2 d-block">
                        <img src="/icon images/reports.png" style="width:20px; margin-right: 10px;"> Reports
                    </a></li>
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

            <div class="dashboard-content px-2 pt-4">
                <h2 class="fw-bold" style="padding-left: 15px;font-size:2rem;"> Admin Dashboard</h2>
                <div class="row mt-5 mb-5" style="width:100%;">
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <div class="rounded-box">
                            <div style="width: 100%; padding-left:20px;padding-right:20px;" class="flex-step-box">
                                <div class="top-row">
                                    <div class="center-text">
                                        Most viewed page
                                    </div>
                                </div>
                                <div class="top-row">
                                    <div class="most-view-text">
                                        <img id="mostViewedIcon" src="/icon images/page.png"
                                            style="width:40px; margin-right: 10px;">
                                            {{-- @foreach ( $pageViews as $p )
                                            {{$p->page_name}}
                                        @endforeach --}}
                                        <div id="mostViewedText" class="large-medium-font">{{$friendlyName}}</div>
                                    </div>
                                </div>
                                <div class="box-align-bottom center-text">
                                    <a href="/admin/report"> View all>></a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class=" d-flex justify-content-center col-md-6 d-flex justify-content-center align-items-center">
                        <div class="rounded-box">
                            <div style="width: 100%; padding-left:20px;padding-right:20px;" class="flex-step-box">
                                <div class="top-row">
                                    <div class="center-text">
                                        Most active user
                                    </div>
                                </div>
                                <div class="top-row">
                                    <div class="most-view-text">
                                        <img id="mostActiveIcon" src="/icon images/person.png"
                                            style="width:40px; margin-right: 10px;">
                                        <div id="mostActiveText" class="large-medium-font">{{ $mostActiveUser->first_name}} {{ $mostActiveUser->last_name}}</div>
                                    </div>
                                </div>
                                <div class="box-align-bottom center-text">
                                    <a href="/admin/report"> View all>></a>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="chart-container">
                    <div class="chart-card">
                        <div class="chart-card-header">
                            <h5 class="chart-card-category"></h5>
                            <h3 class="chart-card-title">Students with no interactions</h3>
                        </div>
                        <div class="chart-card-body">
                            <div class="chart-area">
                                <canvas id="StudentInteractionChart" class="chart-canvas"></canvas>
                                <div class="box-align-right">
                                    <a href="/admin/report" class="small-text">View Report>>></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chart-card col-md-6">
                        <div class="chart-card-header">
                            <h5 class="chart-card-category"></h5>
                            <h3 class="chart-card-title">Used browsers (%)</h3>
                        </div>
                        <div class="chart-card-body">

                            <div class="chart-area">
                                <canvas id="UsedBrowsersChart" class="chart-canvas"></canvas>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chart-container-full">

                    <div class="chart-card">
                        <div class="chart-card-header">
                            <h5 class="chart-card-category"></h5>
                            <h3 class="chart-card-title">Average number of messages of each tutor</h3>
                        </div>
                        <div class="chart-card-body">
                            <div class="chart-area">
                                <canvas id="TutorMessagesChart" class="chart-canvas"></canvas>
                                <div class="box-align-right">
                                    <a href="/admin/report" class="small-text">View Report>>></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart-card chart-card-full">
                        <div class="chart-card-header">
                            <h4 class="chart-card-title">Students without a personal tutor</h4>
                        </div>
                        <div class="chart-card-body">
                            <div class="table-responsive">
                                <table class="table tablesorter" id="table-student-no-tutor">
                                    <thead>
                                        <tr>
                                            <th class="normal-text">
                                                Student Code
                                            </th>
                                            <th class="normal-text">
                                                Student Name
                                            </th>
                                            <th class="text-center normal-text">

                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach ($students as $student)
                                            <tr>
                                                <td class="normal-text">{{ $student->user_code }}</td>
                                                <td class="normal-text">{{ $student->first_name }}
                                                    {{ $student->last_name }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.allocation', $student->id) }}"
                                                        class="btn btn-primary shadow-none">Allocate</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if ($students->isEmpty())
                                            <tr>
                                                <td colspan="3" class="text-center">All students have tutors assigned!
                                                </td>
                                            </tr>
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


    <script src="{{ asset('js/chart.js') }}"></script>

    <script>
        $(".sidebar ul li").on('click', function() {
            $(".sidebar ul li.active").removeClass('active');
            $(this).addClass('active');
        });

        $('.open-btn').on('click', function() {
            $('.sidebar').addClass('active');

        });


        $('.close-btn').on('click', function() {
            $('.sidebar').removeClass('active');

        })



        $(document).ready(function() {
            Chart.register(ChartDataLabels);

            var studentInteractionChartElement = document.getElementById('StudentInteractionChart');
            if (studentInteractionChartElement && typeof demo !== 'undefined') {
                demo.initStudentInteractionChart();
            }
            var tutorMessagesChartElement = document.getElementById('TutorMessagesChart');
            if (tutorMessagesChartElement && typeof demo !== 'undefined') {
                demo.initTutorMessagesChart();
            }
            var usedBrowsersChartElement = document.getElementById('UsedBrowsersChart');
            if (usedBrowsersChartElement && typeof demo !== 'undefined') {
                demo.initUsedBrowsersChart();
            }

            console.log("Data table is loading..");
            $('#table-student-no-tutor').DataTable({
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
