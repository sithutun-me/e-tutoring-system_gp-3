@extends('layouts.app')
@section('content')
<div class="main-container d-flex">



    <div class="main-container d-flex">
        <div class="sidebar" id="side_nav">
            <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
                <h1 class=" header-title fs-4 mt-3">
                    <span class="text-white fw-bold" style="margin-left:20px;">TripleEDU</span>
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
                <span onclick="history.back()" style="cursor: pointer;" class="header-text">
                    <i class="fa-solid fa-chevron-left"></i> <u>Back</u>
                </span>
                <div class="form-group mb-4 mt-4">
                    <form action="{{ route('tutor.meetinglists') }}" method="POST" enctype="multipart/form-data">

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
                                        <div class="col-md-2 mt-4 d-flex align-items-start flex-column">
                                            <div class="text-center">
                                                <a href="#" class="btn btn-primary shadow-none" style="width: auto;">Mark as complete</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row hidden-title new-title-div">
                                        <div class="col-md-6 mt-4 d-flex justify-content-center align-items-center">
                                            <h4 class="chart-card-title" style="font-size:1rem;">Create New Meeting</h4>
                                        </div>
                                    </div>


                                </div>
                                <div class="chart-card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="normal-text">
                                                Meeting Title
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="normal-text">
                                                <input type="text" class="form-control" placeholder="Add title" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="normal-text">
                                                Student
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <select class="form-select" id="selectStudentMeetingDetail" aria-label="Floating label select example">
                                                <option value="" selected disabled>-- Choose Student --</option>
                                                <option value="1">Student One</option>
                                                <option value="2">Student Two</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="normal-text">
                                                Date
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="input-group normal-text">
                                                <input type="text" class="form-control" id="meetingdatepicker" placeholder="Select a date" readonly required />
                                                <span class="input-group-text" id="datepicker-icon">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 mb-2">
                                            <div class="normal-text">
                                                Start time
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2">

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <div class="normal-text">
                                                End time
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 mb-2">
                                            <input id="starttimepicker" type="text" class="form-control" required />
                                        </div>
                                        <div class="col-md-2 mb-2 d-flex justify-content-center align-items-center">
                                            <div class="normal-text">
                                                <i class="fa-solid fa-right-long fa-2xl"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <input id="endtimepicker" type="text" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="normal-text">
                                                Meeting type
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="meetingtype" id="realmeeting" value="real" data-target=".location-div">
                                                <label class="form-check-label" for="realmeeting">Real</label>
                                            </div>
                                        </div>
                                        <div class="col-md-1 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="meetingtype" id="virtualmeeting" value="virtual" data-target=".platform-div,.link-div">
                                                <label class="form-check-label" for="virtualmeeting">Virtual</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row location-div hidden-div">
                                        <div class="col-md-6 mb-2">
                                            <div class="normal-text">
                                                Location
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row location-div hidden-div">
                                        <div class="col-md-6 mb-2">
                                            <div class="normal-text">
                                                <input type="text" class="form-control" placeholder="Add room, floor, building" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row platform-div hidden-div">
                                        <div class="col-md-6 mb-2">
                                            <div class="normal-text">
                                                Platform
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row platform-div hidden-div">
                                        <div class="col-md-6 mb-2">
                                            <select class="form-select" id="selectMeetingType" aria-label="Floating label select example">
                                                <option value="Zoom">Zoom Meeting</option>
                                                <option value="Google">Google Meet</option>
                                                <option value="Teams">Microsoft Teams Meeting</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row link-div hidden-div">
                                        <div class="col-md-6 mb-2">
                                            <div class="normal-text">
                                                Meeting Link
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row link-div hidden-div">
                                        <div class="col-md-6 mb-2">
                                            <div class="normal-text">
                                                <input type="text" class="form-control" placeholder="Add link" />
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
                                                <textarea class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row hidden-button new-button-div">
                                        <div class="col-md-6 mb-2 mt-2">
                                            <button type="button" class="btn btn-primary shadow-none full-button">Save</button>
                                        </div>
                                    </div>
                                    <div class="row hidden-button update-button-div">
                                        <div class="col-md-6 mb-2 mt-2">
                                            <button type="button" class="btn btn-primary shadow-none full-button">Update</button>
                                        </div>
                                    </div>
                                    <div class="row hidden-button detail-button-div">
                                        <div class="col-md-6 mb-2 mt-2">
                                            <button type="button" class="btn btn-primary shadow-none full-button"><a href="/tutor/meetingdetail/1/edit" class="text-decoration-none" style="color: white;">Reschedule</a></button>
                                        </div>
                                    </div>
                                    <div class="row hidden-button detail-button-div">
                                        <div class="col-md-6 mb-2 mt-1">
                                            <button type="button" class="btn btn-secondary shadow-none full-button">Cancel</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>

</div>
@endsection
@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
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
            todayHighlight: true
        });

        $('input[name="meetingtype"][value="real"]').prop('checked', true);
        $(".location-div").show();
        $('input[name="meetingtype"]').change(function() {
            $(".hidden-div").hide();
            let targets = $(this).data("target").split(",");
            targets.forEach(function(divClass) {
                $(divClass.trim()).show();
            });
        });
    });
    flatpickr("#starttimepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i K",
        minuteIncrement: 15,
    });
    flatpickr("#endtimepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i K",
        minuteIncrement: 15,
    });

    var currentRoute = "{{ Route::currentRouteName() }}";
    var currentRouteId = "{{ request()->route('id') }}";
    var isEditPage = currentRoute === 'tutor.meetingdetail.update';

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
</script>
@endpush