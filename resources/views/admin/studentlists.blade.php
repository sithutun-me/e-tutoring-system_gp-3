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
              <h2 class="fs-2 fw-bold mb-4"> Student Lists</h2>

              
                  <div class=" form-group mb-4">
                     <input class="form-control me-2" id="studentSearch" type="search" placeholder="Search here" 
                     aria-label="Search" style="width: 320px;">
                      <button  type="submit"  name="submit" onclick="filterStudents()" class="btn btn-primary shadow-none" >Search</button>
                     
                  </div>

                  <div class="table-responsive" id="no-more-tables" >
                     <table id="studentTable" class="table bg-white table-bordered" style="height: 600px; ">
                          <thead>
                              <tr class="custom-bg text-light">
                                  <th class="text-center" style="color: white;">No.</th>
                                  <th class="text-center" style="color: white;">Student Code</th>
                                  <th class="text-center"  style="color: white;">Student Name</th>
                                  <th class="text-center"  style="color: white;">Email</th>
                                  <th class="text-center"  style="color: white;">Assigned Tutor</th>
                                  <th></th>
                                  
                              </tr>
                          </thead>
                          <tbody class="form-group-table" id="studentsBody">
                            @php $count = 1; @endphp
                            @foreach ($students as $student)
                                <tr class="student-row">
                                    <td data-title="No">{{ $count++;}}</td>
                                    <td data-title="Code">{{ $student->user_code }}</td>
                                    <td data-title="Name">{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td data-title="Email">{{ $student->email }}</td>
                                    <td data-title="Assigned Tutor">{{ $student->tutor_name }}</td>
                                    <td style="text-align: center;"><button type="button" class="btn btn-primary btn-sm shadow-none" style="background-color:#004AAD; width:190px;">
                                        <a href="student/dashboard" class="text-decoration-none " style="color: white; ">View Dashboard >></a></button> 
                                    </td>      
                                </tr>
                            @endforeach    
                            @if ($students->isEmpty())
        <tr class="no-results">
            <td colspan="5" class="text-center">No students found!</td>
        </tr>
        @endif
                          </tbody>
                      </table>

                      
                  </div>
                 
                   

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
            
        $('#studentTable').DataTable({
            paging: true,
            pageLength: 15,
            lengthChange: false,
            searching: false,
            ordering: false,
            "language": {
                "info": "Total Records: _TOTAL_",
            }
        });
    });
        function filterStudents() {
            const searchInput = document.getElementById('studentSearch').value.toLowerCase();
            const rows = document.querySelectorAll('.student-row');
            

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
        // Automatically clear filter and show all rows when input is empty
        document.getElementById('studentSearch').addEventListener('input', function() {
            if (this.value.trim() === '') {
                filterStudents();
            }
        });     


</script>

@endpush
