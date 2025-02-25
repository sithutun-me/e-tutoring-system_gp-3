@extends('layouts.app')

@section('content')

<div class="main-container d-flex">
    <div class="sidebar" id="side_nav">
        <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
            
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
            <li><a href="#" class="text-decoration-none px-3 py-2 d-block">
                <img src="/icon images/tutor.png" style="width:20px; margin-right: 10px;"> Tutor
            </a></li>
            <li><a href="#" class="text-decoration-none px-3 py-2 d-block">
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
              <h2 class="fs-2 fw-bold mb-4"> Allocation</h2>

                  <div class=" form-group mb-4">
                    <input class="form-control me-2" type="search" placeholder="Search here" aria-label="Search" style="width: 320px;">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
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
                      <button  type="submit"  name="submit" class="btn btn-primary shadow-none" style="width: 100px;">Allocate</button>
                  </div>

                  
                 

                  <div class="table-responsive" id="no-more-tables">
                      <table class="table bg-white table-bordered">
                          <thead>
                              <tr class="custom-bg text-light">
                                  <th class="text-center" style="color: white;">S No</th>
                                  <th class="text-center"  style="color: white;">User code</th>
                                  <th class="text-center"  style="color: white;">Student Name</th>
                                  
                                  <th class="text-center"  style="color: white;">Email</th>
                                 
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr class="text-center">
                                  <td data-title="S No">1</td>
                                  <td data-title="User code">Std0001</td>
                                  <td data-title="Student Name">Karen Perez</td>
                                  <td data-title="Email">example@gmail.com</td>
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                              </tr>

                              <tr class="text-center">
                                  <td data-title="S No">1</td>
                                  <td data-title="User code">Std0001</td>
                                  <td data-title="Student Name">Karen Perez</td>
                                  <td data-title="Email">example@gmail.com</td>
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                              </tr>

                              <tr class="text-center">
                                  <td data-title="S No">1</td>
                                  <td data-title="User code">Std0001</td>
                                  <td data-title="Student Name">Karen Perez</td>
                                  <td data-title="Email">example@gmail.com</td>
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                              </tr>

                              <tr class="text-center">
                                  <td data-title="S No">1</td>
                                  <td data-title="User code">Std0001</td>
                                  <td data-title="Student Name">Karen Perez</td>
                                  <td data-title="Email">example@gmail.com</td>
                                  <td><span class="allocate-checkbox"><input type="checkbox" id="checkbox1" name="option[]" value="1">
                                  <label for="checkbox1"></label></span></td>
                              </tr>

                             
                          </tbody>
                      </table>
                  </div>

                  <!-- Can't remove this p tag as if it is removed then all the main contents will went outisde of the nav -->
                  <p class="mb-0" style="color: white;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum, totam? Sequi alias eveniet ut quas
                  ullam delectus et quasi incidunt rem deserunt asperiores reiciendis assumenda doloremque provident,
                  dolores aspernatur neque.</p>

              </section>




          </div>


    </div>
</div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

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

        })

</script>

@endpush
