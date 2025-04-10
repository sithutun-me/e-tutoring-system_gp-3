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
                <li class=""><a href="{{ $isTutor ? '/tutor/dashboard' : '/admin/tutor/dashboard' }}"
                        class="text-decoration-none px-3 py-2 d-block">
                        <img src="/icon images/dashboard.png" style="width:20px; margin-right: 10px;"> Dashboard
                    </a>
                </li>
                @if ($isTutor)
                    <li class=""><a href="/tutor/meetinglists" class="text-decoration-none px-3 py-2 d-block"
                            data-link="/tutor/meetinglists">
                            <img src="/icon images/meeting.png" style="width:20px; margin-right: 10px;"> Meetings
                        </a>
                    </li>
                    <li class=""><a href="/tutor/blogging" class="text-decoration-none px-3 py-2 d-block"
                            data-link="/tutor/blogging">
                            <img src="/icon images/blogging.png" style="width:20px; margin-right: 10px;"> Blogging

                        </a>
                    </li>
                    <li class=""><a href="/tutor/report" class="text-decoration-none px-3 py-2 d-block"
                            data-link="/tutor/report">
                            <img src="/icon images/reports.png" style="width:20px; margin-right: 10px;"> Reports
                        </a>
                    </li>
                @endif

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
                    <h2 class="fw-bold" style="font-size:2rem;">Tutor Dashboard</h2>
                    <form method="GET" action="{{ route('tutor.interactions') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-4 row mt-4">
                            <div class="col-md-3 mb-2">
                                <select class="form-select" name="interaction_type" id="selectInteraction"
                                    aria-label="Interaction Type">
                                    <option value="All">All Interactions</option>
                                    <option value="Posts">Posts</option>
                                    <option value="Comments">Comments</option>
                                    <option value="Documents">Documents</option>
                                    <option value="Meetings">Meetings</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 d-flex flex-column align-items-start">
                                <div class="text-center">
                                    <button id="filterButton" type="button"
                                        class="btn btn-primary shadow-none">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row mt-4">
                        <div class="chart-container-full">
                            <div class="chart-card">
                                <div class="chart-card-header">
                                    <h5 class="chart-card-category"></h5>
                                    <h3 class="chart-card-title">Student interactions</h3>
                                </div>
                                <div class="chart-card-body">
                                    <div class="chart-area">
                                        <canvas id="StudentInteractionCountChart" class="chart-canvas"></canvas>
                                        @if ($isTutor)
                                            <div class="box-align-right">
                                                <a href="/tutor/report" class="small-text">View Report>>></a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        {{-- <div class="chart-container"> --}}
                        <div class="chart-card chart-card-full ms-3">
                            <div class="chart-card-header">
                                <h4 class="chart-card-title">Upcoming meetings within the next week</h4>
                            </div>
                            <div class="chart-card-body">
                                <div class="table-responsive">
                                    <table class="table tablesorter" id="tutor-upcoming-meetings">
                                        <thead>
                                            <tr>
                                                <th class="normal-text" style="width: 25%;">
                                                    Meeting Title
                                                </th>
                                                <th class="normal-text" style="width: 12%;">
                                                    Date
                                                </th>
                                                <th class="normal-text" style="width: 18%;">
                                                    Time
                                                </th>
                                                <th class="normal-text" style="width: 25%;">
                                                    Students
                                                </th>
                                                <th class="normal-text" style="width: 15%;">
                                                    Meeting Type
                                                </th>
                                                <th class="text-center normal-text" style="width: 10%;">
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
                                                        <td class="normal-text">{{ $meeting->first_name }}
                                                            {{ $meeting->last_name }}</td>
                                                        <td class="normal-text">{{ $meeting->meeting_type }}</td>
                                                        <td class="text-center">
                                                            <a href="{{ route('tutor.meetingdetail.view', $meeting->id) }}"
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
                        {{-- </div> --}}
                    </div>

                </div>

            </section>


        </div>

    </div>
@endsection
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap JS connection in public file -->
    <script src="/bootstrap-5.0.2-dist/js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>

    <script src="{{ asset('js/chart_tutor.js') }}"></script>
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
            $.ajax({
                url: '/tutor_student_interaction_dashboard', // Adjust this to your correct route
                method: 'GET',
                data: {
                    interaction_type: "All"
                },
                success: function(response) {
                    console.log("Data received:", response);

                    const studentNames = response.map(item => item.student.first_name + " " + item
                        .student.last_name);
                    const interactionCounts = response.map(item => item.interactions);
                    // Assuming response contains the interaction counts
                    var studentInteractionChartElement = document.getElementById(
                        'StudentInteractionCountChart');

                    if (studentInteractionChartElement) {
                        if (typeof demo !== 'undefined') {
                            demo.initStudentInteractionsChart(
                                studentNames, interactionCounts
                            ); // Pass the data to your chart function
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed:", error);
                }
            });
            // var studentInteractionChartElement = document.getElementById('StudentInteractionCountChart');
            // if (studentInteractionChartElement && typeof demo !== 'undefined') {

            //     demo.initStudentInteractionsChart();
            // }

            console.log("Data table is loading..");
            $('#tutor-upcoming-meetings').DataTable({
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
        async function getInteractionCounts(filterValue) {
            try {

                const response = await fetch('/tutor_student_interaction_dashboard?interaction_type=${filterValue}');
                const data = await response.json();

                // Extract student names and interaction counts
                const studentNames = data.map(item => item.student.first_name + " " + item.student.last_name);
                const interactionCounts = data.map(item => item.interactions);
                console.log("method called try")
                return {
                    studentNames,
                    interactionCounts
                };

            } catch (error) {
                console.error('Error fetching student interaction data:', error);
                return {
                    studentNames: [],
                    interactionCounts: []
                };

            }
        }
        $("#filterButton").on("click", async function() {
            console.log("button clicked");
            var selectedInteractionType = $("#selectInteraction").val();
            console.log(selectedInteractionType);
            const {
                studentNames,
                interactionCounts
            } = getInteractionCounts(selectedInteractionType);
            console.log(interactionCounts);
            $.ajax({
                url: '/tutor_student_interaction_dashboard', // Adjust this to your correct route
                method: 'GET',
                data: {
                    interaction_type: selectedInteractionType
                },
                success: function(response) {
                    console.log("Data received:", response);

                    const studentNames = response.map(item => item.student.first_name + " " + item
                        .student.last_name);
                    const interactionCounts = response.map(item => item.interactions);
                    // Assuming response contains the interaction counts
                    var studentInteractionChartElement = document.getElementById(
                        'StudentInteractionCountChart');

                    if (studentInteractionChartElement) {
                        if (typeof demo !== 'undefined') {
                            demo.initStudentInteractionsChart(
                                studentNames, interactionCounts
                            ); // Pass the data to your chart function
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed:", error);
                }
            });
            // var studentInteractionChartElement = document.getElementById('StudentInteractionCountChart');

            // if (studentInteractionChartElement && typeof demo !== 'undefined') {
            //     demo.initStudentInteractionsChart();
            // }
        });
    </script>
@endpush
