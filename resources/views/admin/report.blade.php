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
            <li><a href="/admin/dashboard" class="text-decoration-none px-3 py-2 d-block">
                    <img src="/icon images/dashboard.png" style="width:20px; margin-right: 10px;"> Dashboard
                </a></li>
            <li><a href="/admin/allocation" class="text-decoration-none px-3 py-2 d-block">
                    <img src="/icon images/allocation.png" style="width:20px; margin-right: 10px;"> Allocation
                </a></li>
            <li><a href="/admin/assignedlists" class="text-decoration-none px-3 py-2 d-block">
                    <img src="/icon images/assigned list.png" style="width:20px; margin-right: 10px;"> Assigned List
                </a></li>
            <li><a href="/admin/tutorlists" class="text-decoration-none px-3 py-2 d-block">
                    <img src="/icon images/tutor.png" style="width:20px; margin-right: 10px;"> Tutor
                </a></li>
            <li><a href="/admin/studentlists" class="text-decoration-none px-3 py-2 d-block">
                    <img src="/icon images/student.png" style="width:20px; margin-right: 10px;"> Student
                </a></li>
            <li><a href="/admin/report" class="text-decoration-none px-3 py-2 d-block">
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

        <div class="dashboard-content px-4 pt-4">
            <h2 class="fs-2 fw-bold mb-3">Reports</h2>

            <div class="report-container mb-5">
                <div class="admin-tabs">
                    <button id="studentsTab" onclick="showContent('students')">Students with no interactions</button>
                    <button id="tutorsTab" onclick="showContent('tutors')">Average messages for each tutor</button>
                    <button id="mostviewedpagesTab" onclick="showContent('mostviewedpages')">Most viewed pages</button>
                    <button id="mostactiveusersTab" onclick="showContent('mostactiveusers')">Most active users</button>

                </div>
                <div id="students" class="content-area active">
                    <div class="form-group mt-5 mb-3">
                        <div class="row">
                            <div class="col-md-5 mb-2 d-flex justify-content-center align-items-center">
                                <label for="selectNoInteraction" class="sort-label"
                                    style="font-family: 'Poppins'; font-size:0.875rem; ">Search</label>
                                <select class="form-select ms-2" id="selectNoInteraction"
                                    aria-label="Floating label select example" >
                                    <option value="all" {{ request('no_interaction') == 'all' ? 'selected' : '' }}>All</option>
                                    <option value="7days" {{ request('no_interaction') == '7days' ? 'selected' : '' }}>More than 7 days</option>
                                    <option value="30days" {{ request('no_interaction') == '30days' ? 'selected' : '' }}>More than 30 days</option>
                                    <option value="60days" {{ request('no_interaction') == '60days' ? 'selected' : '' }}>More than 60 days</option>
                                </select>
                            </div>
                            <div class="col-md-5 mb-2 d-flex justify-content-center align-items-center">
                                <div class="input-group" id="datetimepicker">
                                    <input type="text" class="form-control" name="interaction_date" id="datepicker"
                                        value="{{ request('interaction_date') }}" placeholder="Select a date" readonly />
                                    <span class="input-group-text" id="datepicker-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2 d-flex justify-content-center align-items-center">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary shadow-none" onclick="updateTableStd()">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" id="no-more-tables">
                        <table class="adminreport" id="adminreportTableInteraction">
                            <thead>
                                <tr>
                                    <th class="small-col">No.</th>
                                    <th class="text-center">Student Code</th>
                                    <th class="text-center">Student Name</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Last Active Date</th>
                                    <th class="text-center">No Interaction Days</th>
                                </tr>
                            </thead>
                            <tbody class="adminreport-row">
                                @foreach ($students as $index => $student)
                                <tr>
                                    <td class="small-col" data-title="No.">{{ $index + 1 }}</td>
                                    <td data-title="StudentCode">{{ $student->user_code }}</td>
                                    <td data-title="StudentName">{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td data-title="Email">{{ $student->email }}</td>
                                    <td data-title="LastActiveDate">@if($student->last_active_date == '1970-01-01')
                                        Never Active
                                    @else
                                    {{ \Carbon\Carbon::parse($student->last_active_date)->format('d M Y') }}
                                    @endif
                                    </td>
                                    <td data-title="NoInteractionDays">{{ $student->interaction_label ?? 'No active' }}</td>
                                </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="tutors" class="content-area">
                    <div class="form-group mt-5 mb-3">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4 mb-2 d-flex justify-content-center align-items-center">
                                    {{-- <div class="col-md-1 mb-2 d-flex justify-content-center align-items-center"> --}}
                                    <label for="selectMsgOrder" class="sort-label"
                                        style="font-family: 'Poppins'; font-size:0.875rem; width:45%;">Sort by Msg</label>
                                    {{-- </div> --}}
                                    <select class="form-select" id="selectMsgOrder"
                                        aria-label="Floating label select example" onchange="updateTable()">
                                        <option value="all" {{ request('msgOrder') == 'all' ? 'selected' : '' }}>All</option>
                                        <option value="asc" {{ request('msgOrder') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                        <option value="desc" {{ request('msgOrder') == 'desc' ? 'selected' : '' }}>Descending</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2 d-flex justify-content-center align-items-center">
                                    {{-- <div class="col-md-1 mb-2 d-flex justify-content-center align-items-center"> --}}
                                    <label for="selectNameOrder" class="sort-label"
                                        style="font-family: 'Poppins'; font-size:0.875rem; width:45%; ">Sort by Name</label>
                                    {{-- </div> --}}
                                    <select class="form-select ms-2" id="selectNameOrder"
                                        aria-label="Floating label select example" onchange="updateTable()">
                                        <option value="all" {{ request('nameOrder') == 'all' ? 'selected' : '' }}>All</option>
                                        <option value="az" {{ request('nameOrder') == 'az' ? 'selected' : '' }}>A-Z</option>
                                        <option value="za" {{ request('nameOrder') == 'za' ? 'selected' : '' }}>Z-A</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2 d-flex justify-content-center align-items-center">
                                    {{-- <div class="col-md-1 mb-2 d-flex justify-content-center align-items-center"> --}}
                                    <label for="selectMonthAdmin" class="sort-label"
                                        style="font-family: 'Poppins'; font-size:0.875rem; width:70%;">Search by Month</label>
                                    {{-- </div> --}}

                                    <select class="form-select ms-2" id="selectMonthAdmin" onchange="updateTable()" aria-label="Floating label select example">
                                        <option value="all" {{ request('month') == 'all' ? 'selected' : '' }}>All</option>
                                        <option value="jan" {{ request('month') == 'jan' ? 'selected' : '' }}>January</option>
                                        <option value="feb" {{ request('month') == 'feb' ? 'selected' : '' }}>February</option>
                                        <option value="mar" {{ request('month') == 'mar' ? 'selected' : '' }}>March</option>
                                        <option value="apr" {{ request('month') == 'apr' ? 'selected' : '' }}>April</option>
                                        <option value="may" {{ request('month') == 'may' ? 'selected' : '' }}>May</option>
                                        <option value="jun" {{ request('month') == 'jun' ? 'selected' : '' }}>June</option>
                                        <option value="jul" {{ request('month') == 'jul' ? 'selected' : '' }}>July</option>
                                        <option value="aug" {{ request('month') == 'aug' ? 'selected' : '' }}>August</option>
                                        <option value="sept" {{ request('month') == 'sept' ? 'selected' : '' }}>September</option>
                                        <option value="oct" {{ request('month') == 'oct' ? 'selected' : '' }}>October</option>
                                        <option value="nov" {{ request('month') == 'nov' ? 'selected' : '' }}>November</option>
                                        <option value="dec" {{ request('month') == 'dec' ? 'selected' : '' }}>December</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" id="no-more-tables">
                        <table class="adminreport" id="adminreportTableMessage">
                            <thead>
                                <tr>
                                    <th class="small-col">No.</th>
                                    <th class="text-center">Tutor Code</th>
                                    <th class="text-center">Tutor Name</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">All Messages</th>
                                    <th class="text-center">Average Messages</th>
                                </tr>
                            </thead>
                            <tbody class="adminreport-row">
                                @foreach ($tutorMessages as $index => $tutorMessage)

                                <tr>
                                    <td class="small-col" data-title="No.">{{ $index + 1 }}</td>
                                    <td data-title="TutorCode">{{ $tutorMessage->user_code }}</td>
                                    <td data-title="TutorName">{{ $tutorMessage->first_name }} {{ $tutorMessage->last_name }}</td>
                                    <td data-title="Email">{{ $tutorMessage->email }}</td>
                                    <td data-title="AllMessages">{{ $tutorMessage->total_messages }}</td>
                                    <td data-title="AvgMessages">{{ $tutorMessage->avg_messages_per_day }}</td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="mostviewedpages" class="content-area mt-4">

                    <div class="table-responsive" id="no-more-tables">
                        <table class="adminreportmostviewed" id="adminreportTableViewedpages">
                            <thead>
                                <tr>
                                    <th class="small-col">No.</th>
                                    <th class="text-center">Pages</th>
                                    <th class="text-center">View Count</th>
                                </tr>
                            </thead>
                            <tbody class="adminreport-row">

                                @foreach ($pageViews as $index => $pageView )
                                <tr>
                                    <td class="small-col" data-title="No.">{{ $index + 1 }}</td>
                                    <td data-title="Pages">{{ $pageView['page_name'] }}</td>
                                    <td data-title="View Count">{{ $pageView['view_count'] }}</td>
                                </tr>
                                @endforeach
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="mostactiveusers" class="content-area mt-4">

                    <div class="table-responsive" id="no-more-tables">
                        <table class="adminreportactive" id="adminreportTableActiveusers">
                            <thead>
                                <tr>
                                    <th class="small-col text-center">No.</th>
                                    <th class="text-center">User Code</th>
                                    <th class="text-center">User Name</th>
                                    <th class="text-center">Interaction Count</th>
                                    <th class="text-center">Last Login</th>
                                </tr>
                            </thead>
                            <tbody class="adminreport-row">
                                @foreach ($activeUsers as $index => $user )
                                <tr>
                                    <td class="small-col" data-title="No.">{{ $index +1 }}</td>
                                    <td data-title="User Code">{{ $user ->user_code }}</td>
                                    <td data-title="User Name">{{ $user ->first_name}} {{ $user->last_name }}</td>
                                    <td data-title="Interaction Count">{{$user->total_activity}}</td>
                                    <td data-title="Last Login">{{ $user->last_login_at ?? 'Never login' }}</td>
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


<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#datepicker').datepicker({
            enableTime: true,
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });
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
        $('#adminreportTableInteraction').DataTable({
            paging: true,
            pageLength: 15,
            lengthChange: false,
            searching: false,
            ordering: false,
            "language": {
                "info": "Total Records: _TOTAL_",
            }
        });
    });

    // $(document).ready(function() {
    //     $('#adminreportTableMessage').DataTable({
    //         paging: true,
    //         pageLength: 15,
    //         lengthChange: false,
    //         searching: false,
    //         ordering: false,
    //         "language": {
    //             "info": "Total Records: _TOTAL_",
    //         }
    //     });
    // });

         $(document).ready(function() {
        $('#adminreportTableActiveusers').DataTable({
            paging: true,
            pageLength: 15,
            lengthChange: false,
            searching: false,
            ordering: false,
            "language": {
                "info": "Total Records: _TOTAL_",
            }
        });
    });


    // document.addEventListener("DOMContentLoaded", function() {
    //     showContent('students'); // Ensure the first tab is active on load
    // });
    // Activate the correct tab based on the query parameters
    document.addEventListener("DOMContentLoaded", () => {
        const params = new URLSearchParams(window.location.search);
        const activeTab = params.get('tab') || 'students'; // Default to Tab 1 if no tab is specified

        // Show the correct tab content
        showContent(activeTab);

        // Set the correct tab button as active
        const activeTabButton = document.getElementById(`${activeTab}Tab`);
        if (activeTabButton) {
            activeTabButton.classList.add('active');
        }
    });

    function showContent(tab) {
        document.querySelectorAll('.content-area').forEach(content => {
            content.classList.remove('active');
        });
        document.querySelectorAll('.admin-tabs button').forEach(button => {
            button.classList.remove('active');
        });
        document.getElementById(tab).classList.add('active');
        document.getElementById(tab + 'Tab').classList.add('active');
    }

    // Function to update the table and reload the page
    function updateTable() {
        // Get selected values from dropdowns
        const msgOrder = document.getElementById("selectMsgOrder").value;
        const nameOrder = document.getElementById("selectNameOrder").value;
        const month = document.getElementById("selectMonthAdmin").value;

        // Get the current active tab
        const activeTab = document.querySelector('.admin-tabs button.active')?.id.replace('Tab', '');

        // Build query string
        const queryParams = new URLSearchParams({
            msgOrder: msgOrder !== "all" ? msgOrder : undefined,
            nameOrder: nameOrder !== "all" ? nameOrder : undefined,
            month: month !== "all" ? month : undefined,
            tab: activeTab, // Include the active tab in the query string
        }).toString();

        // Redirect to the same page with updated query parameters
        window.location.href = `/admin/report?${queryParams}`;
    }
    // Function to update the table and reload the page
    function updateTableStd() {
        // Get selected values from dropdown and datepicker
        const noInteractionPeriod = document.getElementById("selectNoInteraction").value;
        const selectedDate = document.getElementById("datepicker").value;
console.log(noInteractionPeriod);
        // Build query string
        const queryParams = new URLSearchParams({
            no_interaction: noInteractionPeriod !== "all" ? noInteractionPeriod : "all",
            interaction_date: selectedDate || "",
        }).toString();

        // Redirect to the same page with updated query parameters
        window.location.href = `/admin/report?${queryParams}`;
    }

    // Initialize the datepicker
    $(document).ready(function() {
        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    });
</script>
@endpush
