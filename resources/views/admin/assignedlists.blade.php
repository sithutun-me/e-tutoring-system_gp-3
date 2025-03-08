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



            <form action="{{ route('admin.reallocation') }}" method="GET" enctype="multipart/form-data">
                @csrf
                <section class="p-3">
                    <h2 class="fs-2 fw-bold mb-4"> Assigned Lists</h2>


                    <div class=" form-group mb-4">
                        <input class="form-control me-2" type="search" placeholder="Search here" aria-label="Search" style="width: 320px;" id="assignedSearch">
                        <button type="button" class="btn btn-primary shadow-none" onclick="filterAssigned()" style="width: 150px;">Search</button>
                        <button type="submit" name="submit" class="btn btn-primary shadow-none" style="width: 150px;">Bulk Reallocate</button>
                    </div>

<<<<<<< HEAD
                    <div class="table-responsive" id="no-more-tables">
                        <table id="assignedTable" class="table bg-white table-bordered" style="height: 600px;">
                            <thead>
                                <tr class="custom-bg text-light">
                                    <th></th>
                                    <th class="text-center" style="color: white;">S No</th>
                                    <th class="text-center" style="color: white;">Allocation Date</th>
                                    <th class="text-center" style="color: white;">Student code</th>
                                    <th class="text-center" style="color: white;">Student Name</th>
                                    <th class="text-center" style="color: white;">Tutor Code</th>
                                    <th class="text-center" style="color: white;">Tutor Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="form-group-table">
=======
                  <div class="table-responsive" id="no-more-tables">
                    <table id="assignedTable" class="table bg-white table-bordered" >
                          <thead>
                              <tr class="custom-bg text-light">
                                  <th style="width: 63px;"></th>
                                  <th class="text-center" style="color: white; width: 70px;">S No</th>
                                  <th class="text-center" style="color: white;">Allocation Date</th>
                                  <th class="text-center"  style="color: white; width: 100px;">Student code</th>
                                  <th class="text-center"  style="color: white; width:200px;">Student Name</th>
                                  <th class="text-center"  style="color: white;">Tutor Code</th>
                                  <th class="text-center" style="color: white;">Tutor Name</th>
                                  <th style="width:248px;"></th>
                              </tr>
                          </thead>
                          <tbody class="form-group-table">
                            @foreach($allocations as $allocation)
                              <tr class="assigned-row">
                                  <td style="width: 50px;"><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No" style="width: 59px;">{{ $allocation->id }}</td>
                                  <td data-title="Allocation Date">{{__(@$allocation->allocation_date_time)}}</td>
                                  <td data-title="Student code" style="width: 94px;" >{{$allocation->student?->user_code ?? 'No user associated'}}</td>
                                  <td data-title="Student Name" style="width: 203px;" >{{__(@$allocation->student->first_name) }} {{__(@$allocation->student->last_name) }}</td>
                                  <td data-title="Tutor code">{{__(@$allocation->tutor->user_code) }}</td>
                                  <td data-title="Tutor Name">{{__(@$allocation->tutor->first_name) }} {{__(@$allocation->user->last_name) }}</td>
                                  <td style="width:260px;"><button type="button" class="btn btn-primary btn-sm shadow-none" style="background-color:#004AAD"><a href="/admin/reallocation" class="text-decoration-none " style="color: white;">Reallocate</a></button>
                                  <button type="button" class="btn btn-outline-secondary btn-sm shadow-none" style="width:100px; height:35px;">Delete</button></td>
>>>>>>> 23b92d8 (Table height fixed)

                                @php $count = 1; @endphp
                                @foreach($allocations as $allocation)
                                <tr class="assigned-row">
                                    <td>
                                        <span class="allocate-checkbox">
                                            <input type="checkbox"
                                                id="allocation_{{ $allocation->id }}"
                                                name="selected_allocations[]"
                                                value="{{ $allocation->id }}"
                                                {{ in_array($allocation->id, old('selected_allocations', [])) ? 'checked' : '' }}>
                                            <label for="allocation_{{ $allocation->id }}"></label>
                                        </span>
                                    </td>
                                    <td data-title="S No">{{ $count++;}}</td>
                                    <td data-title="Allocation Date">{{__(@$allocation->allocation_date_time)}}</td>
                                    <td data-title="Student code">{{$allocation->student?->user_code ?? 'No user associated'}}</td>
                                    <td data-title="Student Name">{{__(@$allocation->student->first_name) }} {{__(@$allocation->student->last_name) }}</td>
                                    <td data-title="Tutor code">{{__(@$allocation->tutor->user_code) }}</td>
                                    <td data-title="Tutor Name">{{__(@$allocation->tutor->first_name) }} {{__(@$allocation->tutor->last_name) }}</td>
                                    <td>
                                        <button
                                            type="button"
                                            data-route="{{ route('admin.reallocation') }}"
                                            data-allocation-id="{{ $allocation->id }}"
                                            class="btn btn-primary btn-sm shadow-none reallocate"
                                            style="background-color:#004AAD">
                                            Reallocate
                                        </button>
                                        <button
                                            type="button"
                                            data-id="{{$allocation->id}}"
                                            class="btn btn-outline-secondary btn-sm shadow-none delete"
                                            style="width:100px; height:35px;">
                                            Delete
                                        </button>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>


            </form>
        </div>




        </section>




    </div>


</div>
</div>



<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete confirmation!</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.allocation.delete')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Are you sure want to delete this allocation?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="submit" class="btn btn-primary">Confirm</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
            </form>
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
        $('#assignedTable').DataTable({
            paging: true,
            pageLength: 15,
            lengthChange: false,
            searching: false,
            ordering: false,
            "language": {
                "info": "Total Records: _TOTAL_",
            }
        });

        $('.reallocate').on('click', function() {
            // Extract the route URL and allocation ID from the button's data attributes
            const route = $(this).data('route');
            const allocationId = $(this).data('allocation-id');

            // Redirect to the route with selected_allocations[] as a single-item array
            window.location.href = `${route}?selected_allocations[]=${allocationId}`;
        });

        $('.delete').on('click', function() {
            var modal = $('#deleteModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.modal('show');
        });

    });

    function filterAssigned() {
        const searchInput = document.getElementById('assignedSearch').value.toLowerCase();
        const rows = document.querySelectorAll('.assigned-row');

        rows.forEach(row => {
            const userCode = row.cells[1].textContent.toLowerCase();
            const name = row.cells[2].textContent.toLowerCase();
            const email = row.cells[3].textContent.toLowerCase();
            const tutor = row.cells[4].textContent.toLowerCase();

            // If search term matches any field, show the row; otherwise, hide it
            if (userCode.includes(searchInput) || name.includes(searchInput) || email.includes(searchInput) || tutor.includes(searchInput)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

    }
    document.getElementById('assignedSearch').addEventListener('input', function() {
        if (this.value.trim() === '') {
            filterAssigned();
        }
    });
</script>

@endpush
