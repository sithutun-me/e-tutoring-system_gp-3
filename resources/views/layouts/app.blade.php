<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-tutoring System</title>
    @vite(['resources/js/app.js'])
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <!-- Google Font -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- font awsome css-->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Bootstrap Connection in public file -->

    <link href="/bootstrap-5.0.2-dist/css/bootstrap.css" rel="stylesheet">

    <!-- datatables connection -->

    <!-- datatables connection -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toast Message -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <link href="{{ asset('white') }}/css/white-dashboard.css?v=1.0.0" rel="stylesheet" />
    <!-- <link href="{{ asset('white') }}/css/theme.css" rel="stylesheet" /> -->
</head>

<body>
    @include('components.header')

    @yield('content')

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

        });

        //sidebar active page script

        document.addEventListener("DOMContentLoaded", function () {
        let links = document.querySelectorAll(".sidebar li a");
        let currentUrl = window.location.pathname;

        links.forEach((link) => {
            if (link.getAttribute("href") === currentUrl) {
            link.classList.add("active");
            }
        });
        });

    </script>
    <script>
        window.errors = @json($errors->all());
        window.successMessage = @json(session('success'));
        window.errorMessage = @json(session('error'));
    </script>
    @stack('scripts')
    <script src="{{ asset('js/toastr.js') }}"></script>
</body>

</html>
