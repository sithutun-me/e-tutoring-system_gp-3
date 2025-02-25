@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')
<div class="main-container d-flex">



    <div class="main-container d-flex">
        <div class="sidebar" id="side_nav">
            <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
                <h1 class="fs-4 ms-2">
                    <span class="text-white">Group 3 E Tutoring</span>
                </h1>
                <button class="btn d-md-none d-block close-btn px-1 py-0 text-white">
                    <i class="fal fa-bars"></i></button>
            </div>

            <ul class="list-unstyled px-2">
                <li class=""><a href="/admin/dashboard" class="text-decoration-none px-3 py-2 d-block">
                        <i class="fal fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li class=""><a href="/" class="text-decoration-none px-3 py-2 d-block">
                        <i class="fal fa-list me-2"></i> Allocation
                    </a>
                </li>
                <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block ">
                        <i class="bi bi-card-list me-2"></i> Assigned List

                    </a>
                </li>
                <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
                        <i class="fal fa-users me-2"></i> Tutor
                    </a>
                </li>
                <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
                        <i class="fal fa-users me-2"></i> Student
                    </a>
                </li>
                <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
                        <i class="bi bi-graph-up me-2"></i> Reports
                    </a>
                </li>
                <li class=""><a href="/login" class="text-decoration-none px-3 py-2 d-block">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login
                    </a>
                </li>

            </ul>



        </div>


        <div class="content">
            <nav class="navbar navbar-expand-md navbar-light bg-light">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between d-md-none d-block">
                        <button class="btn px-1 py-0 open-btn me-2"><i class="fal fa-bars"></i></button>


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