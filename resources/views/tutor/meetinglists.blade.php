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
                <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block ">
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
                    <a href="/tutor/meetingdetail" class="btn btn-primary shadow-none">+ New Meeting</a>
                </div>
                <!-- </div> -->
                <div class="form-group mb-4 row">
                    <div class="row">
                        <div class="col-md-3 mb-2 d-flex justify-content-center align-items-center">
                            <select class="form-select" id="selectDateMeeting" aria-label="Floating label select example">
                                <option selected>All</option>
                                <option value="Real">Real</option>
                                <option value="Virtual">Virtual</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 d-flex justify-content-center align-items-center">

                            <div class="input-group" id="datetimepicker">
                                <input type="text" class="form-control" id="datepicker" placeholder="Select a date" readonly />
                                <span class="input-group-text" id="datepicker-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2 d-flex justify-content-center align-items-center">

                            <select class="form-select form--control" name="student_id" id="selectStudentMeeting" aria-label="Floating label select example">
                                <option value="" selected disabled>-- Choose Student --</option>
                                <option value="1">Student One</option>
                                <option value="2">Student Two</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 d-flex flex-column align-items-start">

                            <div class="text-center">
                                <a href="#" class="btn btn-primary shadow-none">Search</a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table bg-white table-bordered card-table" id="table-meeting">
                        <tbody>
                            <!-- header date -->
                            <tr class="no-top-border fixed-row">
                                <td colspan="6" style="background-color: #F2F2F2;" class="no-border padding-left">26 Feb 2025</td>
                            </tr>
                            <!-- meeting title -->
                            <tr class="no-border fixed-row">
                                <td colspan="6" style="font-size: 16px;" class="padding-left">Design meeting</td>
                            </tr>
                            <!-- meeting detail body -->
                            <tr class="no-border special">
                                <td class="w-15 no-right-border padding-left special">09:00 AM - 10:00 AM</td>
                                <td class="w-15 no-border special" style="color: #004AAD;">Student</td>
                                <td class="w-15 no-border special" style="color: #004AAD;">Status</td>
                                <td class="w-15 no-border special" style="color: #004AAD;">Meeting type</td>
                                <td class="w-25 no-border special" style="color: #004AAD;">Meeting link</td>
                                <td rowspan="2" class="text-center w-15 no-left-border special"><a href="/tutor/meetingdetail/1" class="btn btn-primary shadow-none">Detail</a></td>
                            </tr>
                            <tr class="no-top-border special">
                                <td class="w-15 no-right-border padding-left special">Zoom Meeting</td>
                                <td class="w-15 no-border special">std0011 (John Antony)</td>
                                <td class="w-15 no-border special"><u>New</u></td>
                                <td class="w-15 no-border special">Virtual</td>
                                <td class="w-25 no-border padding-bot special overflow"><a>https://us02web.zoom.us/j/84053495203?pwd=8RdUi1P1BpY3yHhbajBznC4JvNzXij.1</a></td>
                            </tr>

                            <!-- meeting title -->
                            <tr class="no-border">
                                <td colspan="6" style="font-size: 16px;" class="padding-left">Database meeting</td>
                            </tr>
                            <!-- meeting detail body -->
                            <tr class="no-border special">
                                <td class="w-15 no-right-border padding-left special">09:00 AM - 10:00 AM</td>
                                <td class="w-15 no-border special" style="color: #004AAD;">Student</td>
                                <td class="w-15 no-border special" style="color: #004AAD;">Status</td>
                                <td class="w-15 no-border special" style="color: #004AAD;">Meeting type</td>
                                <td class="w-25 no-border special" style="color: #004AAD;">Location</td>
                                <td rowspan="2" class="text-center w-15 no-left-border special"><a href="/tutor/meetingdetail/1" class="btn btn-primary shadow-none">Detail</a></td>
                            </tr>
                            <tr class="no-top-border special">
                                <td class="w-15 no-right-border padding-left special"></td>
                                <td class="w-15 no-border special">std0011 (John Antony)</td>
                                <td class="w-15 no-border special"><u>New</u></td>
                                <td class="w-15 no-border special">Real</td>
                                <td class="w-25 no-border padding-bot special overflow"><a>108A, 1st floor, Building C, Mya Kwar Nyo Housing, Yangon</a></td>
                            </tr>

                            <!-- header date -->
                            <tr class="no-top-border fixed-row">
                                <td colspan="6" style="background-color: #F2F2F2;" class="no-border padding-left">26 Feb 2025</td>
                            </tr>
                            <!-- meeting title -->
                            <tr class="no-border fixed-row">
                                <td colspan="6" style="font-size: 16px;" class="padding-left">Business Management</td>
                            </tr>
                            <!-- meeting detail body -->
                            <tr class="no-border special">
                                <td class="w-15 no-right-border padding-left special">09:00 AM - 10:00 AM</td>
                                <td class="w-15 no-border special" style="color: #004AAD;">Student</td>
                                <td class="w-15 no-border special" style="color: #004AAD;">Status</td>
                                <td class="w-15 no-border special" style="color: #004AAD;">Meeting type</td>
                                <td class="w-25 no-border special" style="color: #004AAD;">Meeting link</td>
                                <td rowspan="2" class="text-center w-15 no-left-border special"><a href="/tutor/meetingdetail/1" class="btn btn-primary shadow-none">Detail</a></td>
                            </tr>
                            <tr class="no-top-border special">
                                <td class="w-15 no-right-border padding-left special">Zoom Meeting</td>
                                <td class="w-15 no-border special">std0011 (John Antony)</td>
                                <td class="w-15 no-border special"><u>Completed</u></td>
                                <td class="w-15 no-border special">Virtual</td>
                                <td class="w-25 no-border padding-bot special overflow"><a>https://us02web.zoom.us/j/84053495203?pwd=8RdUi1P1BpY3yHhbajBznC4JvNzXij.1</a></td>
                            </tr>

                            <!-- meeting title -->
                            <tr class="no-border">
                                <td colspan="6" style="font-size: 16px;" class="padding-left">Database meeting</td>
                            </tr>
                            <!-- meeting detail body -->
                            <tr class="no-border special">
                                <td class="w-15 no-right-border padding-left special">09:00 AM - 10:00 AM</td>
                                <td class="w-15 no-border special" style="color: #004AAD;">Student</td>
                                <td class="w-15 no-border special" style="color: #004AAD;">Status</td>
                                <td class="w-15 no-border special" style="color: #004AAD;">Meeting type</td>
                                <td class="w-25 no-border special" style="color: #004AAD;">Location</td>
                                <td rowspan="2" class="text-center w-15 no-left-border special"><a href="/tutor/meetingdetail/1" class="btn btn-primary shadow-none">Detail</a></td>
                            </tr>
                            <tr class="no-top-border special">
                                <td class="w-15 no-right-border padding-left special"></td>
                                <td class="w-15 no-border special">std0011 (John Antony)</td>
                                <td class="w-15 no-border special"><u>New</u></td>
                                <td class="w-15 no-border special">Real</td>
                                <td class="w-25 no-border padding-bot special overflow"><a>108A, 1st floor, Building C, Mya Kwar Nyo Housing, Yangon</a></td>
                            </tr>


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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

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
</script>
@endpush