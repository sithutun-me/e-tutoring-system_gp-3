@extends('layouts.app')

@section('content')

<div class="main-container d-flex">
    <div class="sidebar" id="side_nav">
        <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
            <h1 class=" header-title fs-2 mt-3">
                <span class="text-white fw-bold" style="margin-left:10px;">TripleEDU</span>
            </h1>

            <button class="btn d-md-none d-block close-btn px-1 py-0 text-white">
                <i class="fa-solid fa-square-xmark"></i>
            </button>
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
            <li><a href="#" class="text-decoration-none px-3 py-2 d-block">
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

        <div class="dashboard-content px-2 pt-2">
            <section class="p-3">
                <h2 class="fs-2 fw-bold mb-4"> Allocation</h2>

                <form action="{{ route('admin.students.filter') }}" method="get">
                    @csrf
                    <div class=" form-group mb-4">
                        <input id="allocationSearch" name="search_student" class="form-control" type="search" placeholder="Search here" aria-label="Search" style="width: 320px;" value="{{ old('search_student', $searchKeyword ?? '') }}">
                        <button type="submit" name="submit" class="btn btn-primary shadow-none">Search</button>
                    </div>
                </form>

                <form action="{{ route('admin.allocate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4">
                        <select class="form-select form--control" name="tutor_id" id="floatingSelect" aria-label="Floating label select example" style="width: 320px;">
                            <option value="" {{ old('tutor_id') ? '' : 'selected' }}>Choose Tutor</option>
                            @foreach($tutors as $tutor)
                            <option value="{{ $tutor->id }}" {{ old('tutor_id') == $tutor->id ? 'selected' : '' }}>
                                {{ $tutor->first_name }} {{ $tutor->last_name }}
                            </option>
                            @endforeach
                        </select>
                        <button type="submit" name="submit" class="btn btn-primary shadow-none">Allocate</button>
                    </div>


                    <div class="table-responsive" id="no-more-tables">
                        <table id="allocationTable" class="table bg-white">
                            <thead>
                                <tr class="custom-bg text-light">
                                    <th class="text-center" style="width: 74px;"><input type="checkbox" id="select_all_students"></th>
                                    <th class="text-center" style="color: white; width: 90px;">No.</th>
                                    <th class="text-center" style="color: white; width: 365px;">User Code</th>
                                    <th class="text-center" style="color: white; width: 365px;">Student Name</th>

                                    <th class="text-center" style="color: white;">Email</th>


                                </tr>
                            </thead>
                            <tbody>

                                @php $count = 1; @endphp
                                @forelse($students as $student)
                                <tr class="allocation-row">
                                    <td style="width: 60px;">
                                        <span class="allocate-checkbox">
                                            <input type="checkbox"
                                                id="student_{{ $student->id }}"
                                                name="selected_students[]"
                                                value="{{ $student->id }}"
                                                {{ in_array($student->id, old('selected_students', [])) ? 'checked' : '' }}>
                                            <label for="student_{{ $student->id }}"></label>
                                        </span>
                                    </td>
                                    <td data-title="No." style="width: 79px;">{{$count++;}}</td>
                                    <td data-title="User Code">{{__($student->user_code)}}</td>
                                    <td data-title="Student Name">{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td data-title="Email" style="  overflow-x: auto; ">{{__(@$student->email)}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">No records found</td>
                                </tr>
                                @endforelse


                            </tbody>
                        </table>

                    </div>


                </form>


            </section>




        </div>


    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- Bootstrap JS connection in public file -->
<script src="/bootstrap-5.0.2-dist/js/bootstrap.js"></script>

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

    $(document).ready(function() {
        'use strict';

        const $input = $('#allocationSearch'); // Select the input field
        if ($input.val().trim() === '') { // Check if the input is empty
            $input.focus(); // Focus on the input field
        }

        $('#allocationTable').DataTable({
            paging: true,
            pageLength: 15,
            lengthChange: false,
            searching: false,
            ordering: false,
            "language": {
                "info": "Total Records: _TOTAL_",
            }
        });

        $('#allocationSearch').on('input', function() {
            if (this.value.trim() === '') {
                window.location.href = "{{ route('admin.allocation') }}";
            }
        });

        var table = $('#allocationTable').DataTable();
        $('#select_all_students').on('change', function() {
            var checked = $(this).prop('checked');

            table.rows({ page: 'current' }).every(function() {
                var row = this.node();
                $(row).find('input[type="checkbox"][name="selected_students[]"]').prop('checked', checked);
            });
        });
    });
</script>

@endpush
