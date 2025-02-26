
@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')

<div class="main-container d-flex">
    <div class="sidebar" id="side_nav">
        <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
            
            <button class="btn d-md-none d-block close-btn px-1 py-0 text-white">
            <i class="fa-solid fa-square-xmark"></i>
            </button>
        </div>

        <ul class="list-unstyled px-2">
      
        
            <li class="active"><a href="/admin/dashboard" class="text-decoration-none px-3 py-2 d-block">
                <img src="/icon images/dashboard.png" style="width:20px; margin-right: 10px;"> Dashboard
            </a></li>
            <li class=""><a href="/admin/allocation" class="text-decoration-none px-3 py-2 d-block">
                <img src="/icon images/allocation.png" style="width:20px; margin-right: 10px;"/>  Allocation
            </a></li>
            <li class=""><a href="/admin/assignedlists" class="text-decoration-none px-3 py-2 d-block">
                <img src="/icon images/assigned list.png" style="width:20px; margin-right: 10px;"> Assigned List
            </a></li>
            <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
                <img src="/icon images/tutor.png" style="width:20px; margin-right: 10px;"> Tutor
            </a></li>
            <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
                <img src="/icon images/student.png" style="width:20px; margin-right: 10px;"> Student
            </a></li>
            <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
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

            <div class="dashboard-content px-3 pt-4">
                <h2 class="fs-2 fw-bold"> Admin Dashboard</h2>

                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5 class="card-category"></h5>
                        <h3 class="chart-card-title">Students with no interactions</h3>
                    </div>
                    <div class="chart-card-body">
                        <div class="chart-area">
                            <canvas id="StudentInteractionChart" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5 class="card-category"></h5>
                        <h3 class="chart-card-title">Average number of messages of each tutor</h3>
                    </div>
                    <div class="chart-card-body">
                        <div class="chart-area">
                            <canvas id="TutorMessagesChart" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>

                <div class="chart-card ">
                    <div class="chart-card-header">
                        <h4 class="chart-card-title">Students without a personal tutor</h4>
                    </div>
                    <div class="chart-card-body">
                        <div class="table-responsive">
                            <table class="table tablesorter" id="">
                                <thead>
                                    <tr>
                                        <th class="normal-text">
                                            Student ID
                                        </th>
                                        <th class="normal-text">
                                            Student Name
                                        </th>
                                        <th class="text-center normal-text">
                                            ....
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="normal-text">
                                            STU001
                                        </td>
                                        <td class="normal-text">
                                            Dakota Rice
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-secondary">Allocate</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normal-text">
                                            STU002
                                        </td>
                                        <td class="normal-text">
                                            Minerva Hooper
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-secondary">Allocate</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normal-text">
                                            STU003
                                        </td>
                                        <td class="normal-text">
                                            Sage Rodriguez
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-secondary">Allocate</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normal-text">
                                            STU004
                                        </td>
                                        <td class="normal-text">
                                            Philip Chaney
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-secondary">Allocate</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normal-text">
                                            STU005
                                        </td>
                                        <td class="normal-text">
                                            Doris Greene
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-secondary">Allocate</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normal-text">
                                            STU006
                                        </td>
                                        <td class="normal-text">
                                            Mason Porter
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-secondary">Allocate</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normal-text">
                                            STU007
                                        </td>
                                        <td class="normal-text">
                                            Jon Porter
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-secondary">Allocate</a>
                                        </td>
                                    </tr>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Bootstrap JS connection in public file -->
<script src="/bootstrap-5.0.2-dist/js/bootstrap.js"></script>
<script src="{{ asset('white') }}/js/plugins/chartjs.min.js"></script>
<script src="{{ asset('js/chart.js') }}"></script>

<script>

$(".sidebar ul li").on('click', function () {
            $(".sidebar ul li.active").removeClass('active');
            $(this).addClass('active');
        });

        $('.open-btn').on('click', function () {
            $('.sidebar').addClass('active');

        });


        $('.close-btn').on('click', function () {
            $('.sidebar').removeClass('active');

        })

    $(document).ready(function() {
        // Ensure that the canvas element is available before initializing the chart
        var studentInteractionChartElement = document.getElementById('StudentInteractionChart');
        if (studentInteractionChartElement && typeof demo !== 'undefined') {
            demo.initStudentInteractionChart(); // Call the correct function with matching ID
        }
        var tutorMessagesChartElement = document.getElementById('TutorMessagesChart');
        if (tutorMessagesChartElement && typeof demo !== 'undefined') {
            demo.initTutorMessagesChart(); // Call the correct function with matching ID
        }
    });
</script>
@endpush