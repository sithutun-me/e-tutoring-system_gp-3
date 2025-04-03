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
                    <!-- <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
                            <img src="/icon images/notification.png" style="width:20px; margin-right: 10px;"> Notifications
                        </a>
                    </li> -->

                    <li class=""><a href="/tutor/report" class="text-decoration-none px-3 py-2 d-block">
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
                    <div class="report-container">
                        <form method="GET" action="{{ route('tutor.report') }}">
                            @csrf

                        <div class="form-group mt-4 mb-4">
                            <label for="selectmonth" class="sort-label"
                                style="font-family: 'Poppins'; font-size:0.875rem; ">Search by Name</label>
                            <select class="form-select" name="student_id" id="select"
                                aria-label="Floating label select example" style="width: 320px;">

                                <option value="" {{ request('student_id') == '' ? 'selected' : '' }}>Choose Student</option>
                                @foreach ($studentsDropDown as $allocated)
                                    <option value="{{ $allocated->student->id }}"
                                        {{ request('student_id') == $allocated->student->id ? 'selected' : '' }}>
                                        {{ $allocated->student->first_name }} {{ $allocated->student->last_name }}
                                    </option>
                                @endforeach

                            </select>

                            <?php
                            $monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            $currentMonth = date('n') - 1; // Get current month (0-11)
                            ?>
                            <label for="selectmonth" class="sort-label"
                                style="font-family: 'Poppins'; font-size:0.875rem; ">Search by Month</label>
                            <select class="form-select" id="selectmonth" aria-label="Floating label select example"
                                style="width: 320px;" name="month">
                                <option value="" {{ request('month') == '' ? 'selected' : '' }}>Choose Month</option>
                                <?php for ($i = 0; $i <= $currentMonth; $i++): ?>
                                <option value="<?= $i+1 ?>" {{ request('month') == $i+1 ? 'selected' : '' }}><?= $monthNames[$i] ?></option>
                                <?php endfor; ?>
                                {{-- <option value="all" {{ request('month') == 'all' ? 'selected' : '' }}>All Months</option>
                                @foreach (range(1, 12) as $month)
                                    <option value="{{ $month }}"
                                        {{ request('month',\Carbon\Carbon::now()->month) == $month ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                    </option>
                                @endforeach --}}
                            </select>


                            <button type="submit" name="submit" class="btn btn-primary shadow-none">Search</button>
                        </div>
                    </form>
                        <div class="table-responsive " id="no-more-tables">
                            <table class="tutorreport" id="tutorreportTable">
                                <thead>
                                    <tr>
                                        <th class="small-col">No.</th>
                                        <th class="text-center">Student Code</th>
                                        <th class="text-center">Student Name</th>
                                        <th class="text-center">Posts</th>
                                        <th class="text-center">Comments</th>
                                        <th class="text-center">Documents</th>
                                        <th class="text-center">Meetings</th>
                                    </tr>
                                </thead>
                                <tbody class="tutorreport-row">
                                    @foreach ($studentReports as $index => $student)
                                        <tr>
                                            <td class="small-col" data-title="No.">{{ $index + 1 }}</td>
                                            <td data-title="Student Code">{{ $student->user_code }}</td>
                                            <td data-title="Student Name">{{ $student->first_name }} {{ $student->last_name }}</td>
                                            <td data-title="Posts">{{ $student->posts }}</td>
                                            <td data-title="Comments">{{ $student->comments }}</td>
                                            <td data-title="Documents">{{ $student->documents }}</td>
                                            <td data-title="Meetings">{{ $student->meetings }}</td>
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

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

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
            $('#tutorreportTable').DataTable({
                paging: true,
                pageLength: 15,
                lengthChange: false,
                searching: false,
                ordering: true, // Enable column sorting
                "language": {
                    "info": "Total Records: _TOTAL_",
                }
            });
        });



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
