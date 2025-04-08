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
                <!-- <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
                        <img src="/icon images/notification.png" style="width:20px; margin-right: 10px;"> Notifications
                    </a>
                </li> -->

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

            <div class="dashboard-content px-4 pt-4">
                <h2 class="fs-2 fw-bold mb-3">Reports</h2>

                <div class="report-container mb-4">
                        <div class="tabs">
                            <button id="studentsTab" class="active" onclick="showContent('students')">My Activities</button>
                            <button id="tutorsTab" onclick="showContent('tutors')">Tutor Activites</button>
                        </div>
                        <div id="students" class="content-area active">
                            {{-- <form method="GET" action="{{ route('student.report') }}">
                            <div class="form-group mt-5 mb-3">
                                <label for="selectmonth" class="sort-label" style="font-family: 'Poppins'; font-size:0.875rem; ">Search by Month</label>
                                <select class="form-select" id="selectmonth" name="month" aria-label="Floating label select example" style="width: 320px;">
                                    <option selected>All</option>
                                </select>
                                <button id="searchBtn" type="button" name="submit" class="btn btn-primary shadow-none">Search</button> 
                            </div>
                            </form> --}}
                            <div class="table-responsive" id="no-more-tables">
                                <table  class="studentreport" id="studentreportTable">
                                    <thead>
                                        <tr>
                                            <th class="small-col">No.</th>
                                            <th class="text-center">Month</th>
                                            <th class="text-center">Posts</th>
                                            <th class="text-center">Comments</th>
                                            <th class="text-center">Documents</th>
                                            <th class="text-center">Meetings</th>
                                        </tr>
                                    </thead>
                                    <tbody class="studentreport-row" id="student_report_row">
                                        @foreach ($studentMonthlyData as $index => $data)
                                        <tr>
                                            <td class="small-col" data-title="No.">{{ $index + 1 }}.</td>
                                            <td data-title="Month">{{ $data['month'] }}</td>
                                            <td data-title="Posts">{{ $data['posts'] }}</td>
                                            <td data-title="Comments">{{ $data['comments'] }}</td>
                                            <td data-title="Documents">{{ $data['documents'] }}</td>
                                            <td data-title="Meetings">{{ $data['meetings'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="tutors" class="content-area">
                            <div class="tutor-container mt-4">
                                <p class="tutor-text">Current Tutor - <span class="tutor-name">{{$tutorName}}</span></p>
                            </div>
                            
                            <div class="table-responsive" id="no-more-tables">
                                <table class="studentreport" id="studentreportTable">
                                <thead>
                                        <tr>
                                            <th class="small-col">No.</th>
                                            <th class="text-center">Month</th>
                                            <th class="text-center">Posts</th>
                                            <th class="text-center">Comments</th>
                                            <th class="text-center">Documents</th>
                                            <th class="text-center">Meetings</th>
                                        </tr>
                                    </thead>
                                    <tbody class="studentreport-row">
                                        @foreach ($tutorMonthlyData as $index => $data)
                                        <tr>
                                            <td class="small-col" data-title="No.">{{ $index + 1 }}.</td>
                                            <td data-title="Month">{{ $data['month'] }}</td>
                                            <td data-title="Posts">{{ $data['posts'] }}</td>
                                            <td data-title="Comments">{{ $data['comments'] }}</td>
                                            <td data-title="Documents">{{ $data['documents'] }}</td>
                                            <td data-title="Meetings">{{ $data['meetings'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>


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

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

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

        // $(document).ready(function() {
        //     $('#studentreportTable').DataTable({
        //         paging: true,
        //         pageLength: 10,
        //         lengthChange: false,
        //         searching: false,
        //         ordering: false,
        //         "language": {
        //             "info": "Total Records: _TOTAL_",
        //         }
        //     });
        // });

        
        document.addEventListener("DOMContentLoaded", function() {
            showContent('students'); // Ensure the first tab is active on load
        });

        function showContent(tab) {
            document.querySelectorAll('.content-area').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tabs button').forEach(button => {
                button.classList.remove('active');
            });
            document.getElementById(tab).classList.add('active');
            document.getElementById(tab + 'Tab').classList.add('active');
        }

        



    </script>
@endpush
