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

        <div class="dashboard-content px-2 pt-2">
              


                
              <section class="p-3">
              <h2 class="fs-2 fw-bold mb-4"> Allocation</h2>

                  <div class=" form-group mb-4">
                    <input class="form-control" type="search" placeholder="Search here" aria-label="Search" style="width: 320px;">
                    <button  type="submit"  name="submit" class="btn btn-primary shadow-none">Search</button>
                  </div>
 
                  <div class="form-group mb-4">
                      <select class="form-select" id="floatingSelect" aria-label="Floating label select example" style="width: 320px;">
                      <option selected>Choose Tutor</option>
                          <option value="1">Tutor1</option>
                          <option value="2">Tutor2</option>
                          <option value="3">Tutor3</option>
                          <option value="4">Tutor4</option>
                          <option value="5">Tutor5</option>
                          <option value="6">Tutor6</option>
                          <option value="7">Tutor7</option>
                          <option value="8">Tutor8</option>
                          <option value="9">Tutor9</option>
                          <option value="10">Tutor10</option>
                      </select>
                      <button  type="submit"  name="submit" class="btn btn-primary shadow-none">Allocate</button>
                  </div>

                  
                  <div class="table-responsive" id="no-more-tables">
                    <table id="assignedTable" class="table bg-white table-bordered" style="height:400px;">
                          <thead>
                              <tr class="custom-bg text-light">
                                  <th></th>
                                  <th class="text-center" style="color: white;">S No</th>
                                  <th class="text-center"  style="color: white;">User code</th>
                                  <th class="text-center"  style="color: white;">Student Name</th>
                                  
                                  <th class="text-center"  style="color: white;">Email</th>
                                 
                                 
                              </tr>
                          </thead>
                          <tbody>
                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="User code">Std0001</td>
                                  <td data-title="Student Name">Karen Perez</td>
                                  <td data-title="Email">example@gmail.com</td>
                              
                              </tr>

                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="User code">Std0001</td>
                                  <td data-title="Student Name">Karen Perez</td>
                                  <td data-title="Email">example@gmail.com</td>
                              </tr>

                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="User code">Std0001</td>
                                  <td data-title="Student Name">Karen Perez</td>
                                  <td data-title="Email">example@gmail.com</td>
                              </tr>

                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="User code">Std0001</td>
                                  <td data-title="Student Name">Karen Perez</td>
                                  <td data-title="Email">example@gmail.com</td>
                              </tr>
                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="User code">Std0001</td>
                                  <td data-title="Student Name">Karen Perez</td>
                                  <td data-title="Email">example@gmail.com</td>
                              </tr>
                              <tr class="text-center">
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                                  <td data-title="S No">1</td>
                                  <td data-title="User code">Std0001</td>
                                  <td data-title="Student Name">Karen Perez</td>
                                  <td data-title="Email">example@gmail.com</td>
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
