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

                <div class="report-container mb-4">
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
                                        aria-label="Floating label select example">
                                        <option selected>All</option>
                                        <option value="7days">More than 7 days</option>
                                        <option value="30days">More than 30 days</option>
                                        <option value="60days">More than 60 days</option>
                                    </select>
                                </div>
                                <div class="col-md-5 mb-2 d-flex justify-content-center align-items-center">
                                    <div class="input-group" id="datetimepicker">
                                        <input type="text" class="form-control" name="meeting_date" id="datepicker"
                                            value="{{ request('meeting_date') }}" placeholder="Select a date" readonly />
                                        <span class="input-group-text" id="datepicker-icon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2 d-flex justify-content-center align-items-center">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary shadow-none">Search</button>
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
                                    <tr>
                                        <td class="small-col" data-title="No.">1.</td>
                                        <td data-title="StudentCode">std0001</td>
                                        <td data-title="StudentName">8</td>
                                        <td data-title="Email">example@gmail.com</td>
                                        <td data-title="LastActiveDate">12 Jan 2025</td>
                                        <td data-title="NoInteractionDays">15 days</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">1.</td>
                                        <td data-title="StudentCode">std0001</td>
                                        <td data-title="StudentName">8</td>
                                        <td data-title="Email">example@gmail.com</td>
                                        <td data-title="LastActiveDate">12 Jan 2025</td>
                                        <td data-title="NoInteractionDays">15 days</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">1.</td>
                                        <td data-title="StudentCode">std0001</td>
                                        <td data-title="StudentName">Magaret Magaret Magaret</td>
                                        <td data-title="Email">example@gmail.com</td>
                                        <td data-title="LastActiveDate">12 Jan 2025</td>
                                        <td data-title="NoInteractionDays">15 days</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">1.</td>
                                        <td data-title="StudentCode">std0001</td>
                                        <td data-title="StudentName">Thomas Brian</td>
                                        <td data-title="Email">example@gmail.com</td>
                                        <td data-title="LastActiveDate">12 Jan 2025</td>
                                        <td data-title="NoInteractionDays">15 days</td>
                                    </tr>


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
                                            aria-label="Floating label select example">
                                            <option selected>All</option>
                                            <option value="asc">Ascending</option>
                                            <option value="desc">Descending</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2 d-flex justify-content-center align-items-center">
                                        {{-- <div class="col-md-1 mb-2 d-flex justify-content-center align-items-center"> --}}
                                        <label for="selectNameOrder" class="sort-label"
                                            style="font-family: 'Poppins'; font-size:0.875rem; width:45%; ">Sort by Name</label>
                                        {{-- </div> --}}
                                        <select class="form-select ms-2" id="selectNameOrder"
                                            aria-label="Floating label select example">
                                            <option selected>All</option>
                                            <option value="az">A-Z</option>
                                            <option value="za">Z-A</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2 d-flex justify-content-center align-items-center">
                                        {{-- <div class="col-md-1 mb-2 d-flex justify-content-center align-items-center"> --}}
                                        <label for="selectMonthAdmin" class="sort-label"
                                            style="font-family: 'Poppins'; font-size:0.875rem; width:70%;">Search by Month</label>
                                        {{-- </div> --}}

                                        <select class="form-select ms-2" id="selectMonthAdmin"
                                            aria-label="Floating label select example">
                                            <option selected>All</option>
                                            <option value="jan">January</option>
                                            <option value="feb">February</option>
                                            <option value="mar">March</option>
                                            <option value="apr">April</option>
                                            <option value="may">May</option>
                                            <option value="jun">June</option>
                                            <option value="jul">July</option>
                                            <option value="aug">August</option>
                                            <option value="sept">September</option>
                                            <option value="oct">October</option>
                                            <option value="nov">November</option>
                                            <option value="dec">December</option>
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
                                    <tr>
                                        <td class="small-col" data-title="No.">1.</td>
                                        <td data-title="TutorCode">tur0001</td>
                                        <td data-title="TutorName">Magaret Magaret</td>
                                        <td data-title="Email">example@gmail.com</td>
                                        <td data-title="AllMessages">30</td>
                                        <td data-title="AvgMessages">5</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">1.</td>
                                        <td data-title="TutorCode">tur0001</td>
                                        <td data-title="TutorName">Magaret Magaret</td>
                                        <td data-title="Email">example@gmail.com</td>
                                        <td data-title="AllMessages">30</td>
                                        <td data-title="AvgMessages">5</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">1.</td>
                                        <td data-title="TutorCode">tur0001</td>
                                        <td data-title="TutorName">Magaret Magaret</td>
                                        <td data-title="Email">example@gmail.com</td>
                                        <td data-title="AllMessages">30</td>
                                        <td data-title="AvgMessages">5</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">1.</td>
                                        <td data-title="TutorCode">tur0001</td>
                                        <td data-title="TutorName">Magaret Magaret</td>
                                        <td data-title="Email">example@gmail.com</td>
                                        <td data-title="AllMessages">30</td>
                                        <td data-title="AvgMessages">5</td>
                                    </tr>

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
                                    <tr>
                                        <td class="small-col" data-title="No.">1.</td>
                                        <td data-title="Pages">Tutor Meetings</td>
                                        <td data-title="View Count">50</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">2.</td>
                                        <td data-title="Pages">Student Meetings</td>
                                        <td data-title="View Count">48</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">3.</td>
                                        <td data-title="Pages">Tutor Blogging</td>
                                        <td data-title="View Count">45</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">4.</td>
                                        <td data-title="Pages">Student Blogging</td>
                                        <td data-title="View Count">30</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">5.</td>
                                        <td data-title="Pages">Admin Reports</td>
                                        <td data-title="View Count">30</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">6.</td>
                                        <td data-title="Pages">Admin Dashboard</td>
                                        <td data-title="View Count">29</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">7.</td>
                                        <td data-title="Pages">Tutor Dashboard</td>
                                        <td data-title="View Count">28</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">8.</td>
                                        <td data-title="Pages">Student Dashboard</td>
                                        <td data-title="View Count">27</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">9.</td>
                                        <td data-title="Pages">Allocation</td>
                                        <td data-title="View Count">20</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">10.</td>
                                        <td data-title="Pages">Reschedule</td>
                                        <td data-title="View Count">19</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">11.</td>
                                        <td data-title="Pages">Meeting Detail</td>
                                        <td data-title="View Count">17</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">12.</td>
                                        <td data-title="Pages">Assigned list</td>
                                        <td data-title="View Count">16</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">13.</td>
                                        <td data-title="Pages">Tutor List</td>
                                        <td data-title="View Count">15</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">14.</td>
                                        <td data-title="Pages">Student List</td>
                                        <td data-title="View Count">15</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">15.</td>
                                        <td data-title="Pages">Tutor Reports</td>
                                        <td data-title="View Count">13</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">16.</td>
                                        <td data-title="Pages">Student Reports</td>
                                        <td data-title="View Count">13</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">17.</td>
                                        <td data-title="Pages">Reallocation</td>
                                        <td data-title="View Count">20</td>
                                    </tr>
                                    

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="mostactiveusers" class="content-area mt-4">

                        <div class="table-responsive" id="no-more-tables">
                            <table class="adminreport" id="adminreportTableActiveusers">
                                <thead>
                                    <tr>
                                        <th class="small-col">No.</th>
                                        <th class="text-center">User Code</th>
                                        <th class="text-center">User Name</th>
                                        <th class="text-center">Interaction Count</th>
                                        <th class="text-center">Last Login</th>
                                    </tr>
                                </thead>
                                <tbody class="adminreport-row">
                                    <tr>
                                        <td class="small-col" data-title="No.">1.</td>
                                        <td data-title="User Code">std0001</td>
                                        <td data-title="User Name">Jennie</td>
                                        <td data-title="Interaction Count">10</td>
                                        <td data-title="Last Login">2025-04-04 01:11:28</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">2.</td>
                                        <td data-title="User Code">std0002</td>
                                        <td data-title="User Name">Jake</td>
                                        <td data-title="Interaction Count">10</td>
                                        <td data-title="Last Login">2025-04-04 01:11:28</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">3.</td>
                                        <td data-title="User Code">std0003</td>
                                        <td data-title="User Name">Joshua</td>
                                        <td data-title="Interaction Count">10</td>
                                        <td data-title="Last Login">2025-04-04 01:11:28</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">4.</td>
                                        <td data-title="User Code">std0004</td>
                                        <td data-title="User Name">Jay</td>
                                        <td data-title="Interaction Count">10</td>
                                        <td data-title="Last Login">2025-04-04 01:11:28</td>
                                    </tr>
                                    <tr>
                                        <td class="small-col" data-title="No.">5.</td>
                                        <td data-title="User Code">std0005</td>
                                        <td data-title="User Name">Jeonghan</td>
                                        <td data-title="Interaction Count">10</td>
                                        <td data-title="Last Login">2025-04-04 01:11:28</td>
                                    </tr>
                                    

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

        // $(document).ready(function() {
        //     $('#adminreportTableInteraction').DataTable({
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

        //      $(document).ready(function() {
        //     $('#adminreportTableActiveusers').DataTable({
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


        document.addEventListener("DOMContentLoaded", function() {
            showContent('students'); // Ensure the first tab is active on load
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
    </script>
@endpush
