@extends('layouts.app')

@section('content')

<div class="main-container d-flex">
    <div class="sidebar" id="side_nav">
        <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
            <h1 class=" header-title fs-4 mt-3">
                <span class="text-white fw-bold" style="margin-left:20px;">TripleEDU</span>
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
                <img src="/icon images/allocation.png" style="width:20px; margin-right: 10px;">  Allocation
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

        <div class="dashboard-content px-2 pt-4">
              


                
              <section class="p-5">
              <h2 class="fs-2 fw-bold mb-4"> Assigned Lists</h2>

                  
                  <div class=" form-group mb-4">
                     <input class="form-control me-2" type="search" placeholder="Search here" aria-label="Search" style="width: 320px;">
                      <button  type="submit"  name="submit" class="btn btn-primary shadow-none" style="width: 150px;">Search</button>
                      <button  type="submit"  name="submit" class="btn btn-primary shadow-none" style="width: 150px;">Bulk Reallocate</button>
                  </div>

                  <div class="table-responsive" id="no-more-tables">
                    <table id="assignedTable" class="table bg-white table-bordered" style="height:400px;">
                          <thead>
                              <tr class="custom-bg text-light">
                                  <th></th>
                                  <th class="text-center" style="color: white;">S No</th>
                                  <th class="text-center" style="color: white;">Allocation Date</th>
                                  <th class="text-center"  style="color: white;">Student code</th>
                                  <th class="text-center"  style="color: white;">Student Name</th>
                                  <th class="text-center"  style="color: white;">Tutor Code</th>
                                  <th class="text-center" style="color: white;">Tutor Name</th>
                                  <th class="text-center" style="color: white;">Action</th>
                              </tr>
                          </thead>
                          <tbody class="form-group-table">
                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="Allocation Date">22/2/2025</td>
                                  <td data-title="Student code">Std0005</td>
                                  <td data-title="Student Name">Isabella Swift</td>
                                  <td data-title="Tutor Code">tur0012</td>
                                  <td data-title="Tutor Name">Cornor McGregor</td>
                                  <td><button type="button" class="btn btn-primary btn-sm shadow-none" style="background-color:#004AAD"><a href="/admin/reallocation" class="text-decoration-none " style="color: white;">Reallocate</a></button> 
                                  <button type="button" class="btn btn-outline-secondary btn-sm shadow-none" style="width:100px; height:35px;">Delete</button></td>

                              </tr>

                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="Allocation Date">22/2/2025</td>
                                  <td data-title="Student code">Std0005</td>
                                  <td data-title="Student Name">Isabella Swift</td>
                                  <td data-title="Tutor Code">tur0012</td>
                                  <td data-title="Tutor Name">Cornor McGregor</td>
                                  <td><button type="button" class="btn btn-primary btn-sm shadow-none" style="background-color:#004AAD"><a href="/admin/reallocation" class="text-decoration-none " style="color: white;">Reallocate</a></button> 
                                  <button type="button" class="btn btn-outline-secondary btn-sm shadow-none" style="width:100px; height:35px;">Delete</button></td>


                              </tr>

                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="Allocation Date">22/2/2025</td>
                                  <td data-title="Student code">Std0005</td>
                                  <td data-title="Student Name">Isabella Swift</td>
                                  <td data-title="Tutor Code">tur0012</td>
                                  <td data-title="Tutor Name">Cornor McGregor</td>
                                  <td><button type="button" class="btn btn-primary btn-sm shadow-none" style="background-color:#004AAD"><a href="/admin/reallocation" class="text-decoration-none " style="color: white;">Reallocate</a></button> 
                                  <button type="button" class="btn btn-outline-secondary btn-sm shadow-none" style="width:100px; height:35px;">Delete</button></td>


                              </tr>

                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="Allocation Date">22/2/2025</td>
                                  <td data-title="Student code">Std0005</td>
                                  <td data-title="Student Name">Isabella Swift</td>
                                  <td data-title="Tutor Code">tur0012</td>
                                  <td data-title="Tutor Name">Cornor McGregor</td>
                                  <td><button type="button" class="btn btn-primary btn-sm shadow-none" style="background-color:#004AAD"><a href="/admin/reallocation" class="text-decoration-none " style="color: white;">Reallocate</a></button> 
                                  <button type="button" class="btn btn-outline-secondary btn-sm shadow-none" style="width:100px; height:35px;">Delete</button></td>

                              </tr>

                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="Allocation Date">22/2/2025</td>
                                  <td data-title="Student code">Std0005</td>
                                  <td data-title="Student Name">Isabella Swift</td>
                                  <td data-title="Tutor Code">tur0012</td>
                                  <td data-title="Tutor Name">Cornor McGregor</td>
                                  <td><button type="button" class="btn btn-primary btn-sm shadow-none" style="background-color:#004AAD"><a href="/admin/reallocation" class="text-decoration-none " style="color: white;">Reallocate</a></button> 
                                  <button type="button" class="btn btn-outline-secondary btn-sm shadow-none" style="width:100px; height:35px;">Delete</button></td>

                              </tr>
                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="Allocation Date">22/2/2025</td>
                                  <td data-title="Student code">Std0005</td>
                                  <td data-title="Student Name">Isabella Swift</td>
                                  <td data-title="Tutor Code">tur0012</td>
                                  <td data-title="Tutor Name">Cornor McGregor</td>
                                  <td><button type="button" class="btn btn-primary btn-sm shadow-none" style="background-color:#004AAD"><a href="/admin/reallocation" class="text-decoration-none " style="color: white;">Reallocate</a></button> 
                                  <button type="button" class="btn btn-outline-secondary btn-sm shadow-none" style="width:100px; height:35px;">Delete</button></td>

                              </tr>
                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="Allocation Date">22/2/2025</td>
                                  <td data-title="Student code">Std0005</td>
                                  <td data-title="Student Name">Isabella Swift</td>
                                  <td data-title="Tutor Code">tur0012</td>
                                  <td data-title="Tutor Name">Cornor McGregor</td>
                                  <td><button type="button" class="btn btn-primary btn-sm shadow-none" style="background-color:#004AAD"><a href="/admin/reallocation" class="text-decoration-none " style="color: white;">Reallocate</a></button> 
                                  <button type="button" class="btn btn-outline-secondary btn-sm shadow-none" style="width:100px; height:35px;">Delete</button></td>

                              </tr>

                             
                          </tbody>
                      </table>

                      
                  </div>
                    

                   

              </section>




          </div>


    </div>
</div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- Bootstrap JS connection in public file -->
<script src="/bootstrap-5.0.2-dist/js/bootstrap.js"></script>

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

        });

        $(document).ready(function () {
        $('#assignedTable').DataTable({
            paging: true,
            pageLength: 5,
            lengthChange: false,
            searching: false,
            ordering: false,
            "language": {
                "info": "Total Records: _TOTAL_",
            }
        });
    });



</script>

@endpush
