@extends('layouts.app')
@section('content')
<div class="main-container d-flex">



    <div class="main-container d-flex">
        <div class="sidebar" id="side_nav">
            <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
                <h1 class=" header-title fs-2 mt-3">
                    <span class="text-white fw-bold" style="margin-left:10px;">TripleEDU</span>
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
                <li class=""><a href="/tutor/blogging" class="text-decoration-none px-3 py-2 d-block ">
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
            <h2 class="fs-2 fw-bold">Posts</h2>

                <div class="post mt-4" >
                       
                       <div class="text-center fit mb-2">
                        <a href="/tutor/createposts" class="post-btn btn btn-primary shadow-none mb-3" style="background-color: #004AAD;">+ Start a post</a>
                       </div>

                       <div class="form-group mb-4 row">
                            <div class="row">
                            
                                <div class="col-md-3 mb-2 d-flex justify-content-center align-items-center">
                                    <select class="form-select" id="selectDateMeeting" aria-label="Floating label select example">
                                        <option selected>All</option>
                                        <option value="Real">My Posts</option>
                                        <option value="Virtual">Student Posts</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-2 d-flex justify-content-center align-items-center">

                                    <select class="form-select form--control" name="student_id" id="selectStudentMeeting" aria-label="Floating label select example">
                                        <option value="" selected disabled>-- Choose Student --</option>
                                        <option value="1">Student One</option>
                                        <option value="2">Student Two</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 d-flex flex-column align-items-start">

                                    <div class=" text-center">
                                        <button type="button"  class=" btn btn-primary shadow-none " style="width: 130px;">Search</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="post-container">

                           <!-- post header -->

                            <div class="edit-btn text-center fit">
                          
                            <a href="/tutor/updateposts" class="edit-btn btn btn-primary shadow-none" style=" width: 100px; background-color: #004AAD;">Edit</a>
                            </div>

                            <p>
                                <i class="fa-solid fa-circle-user me-3" style="font-size: 35px; color:#808080; vertical-align: middle;"></i>
                                <strong class="name me-4" style="vertical-align: middle; font-size: 1rem">Name</strong>
                                <span class="date me-1" style="vertical-align: middle;">4 March 2025</span> 
                                <span class="time me-4" style="vertical-align: middle;">9:00 PM</span> 
                                <span class="status me-0" style="vertical-align: middle;">Updated</span>
                            </p>

                            <!-- Post body -->

                            <div class="post-title-desc mt-2">

                            <h5 class="mb-3 mt-2">Project sample file</h5>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet.</p>
                            
                            </div>


                            <div class="file-attachment">
                                <img src="https://cdn-icons-png.flaticon.com/512/732/732220.png" width="30" alt="File">
                                <a href="" style="text-decoration: none; color: black;" target="_blank">project_sample.docx</a>
                            
                            
                            
                                
                            </div>

                            <!-- Note:: this comment section has javascript interaction added for now, when reply button is clicked this will appear -->

                            <div class="comments" id="commentsSection" style="display:none;">
                                <hr>
                                <p class="mb-3" style="font-size: 0.875rem; color:	#004AAD;">Comments</p>
                                <p>
                                    <i class="fa-solid fa-circle-user me-2" style="font-size: 20px; color:#808080; vertical-align: middle;"></i>
                                    <strong class="name me-4" style="vertical-align: middle; font-size: 1rem">Name</strong>
                                    <span class="date me-1" style="vertical-align: middle;">4 March 2025</span> 
                                    <span class="time me-4" style="vertical-align: middle;">9:00 PM</span> 
                                    
                                </p>
                                <p>Yes sir, well noted.<br>We will be missing you during the holidays sir.</p>
                            </div>

                            


                            <form action="" method="" enctype="multipart/form-data">
                                <div class="d-flex align-items-center gap-2 mt-4">
                                    <input type="text" id="replyInput" class="form-control" placeholder="Reply" style="max-width: 1100px;">
                                    <button type="button" class="btn btn-primary ms-3" style="width: 110px;" onclick="checkReply()">Send</button>
                                </div>
                            </form>

                        </div>
                        
                        <div class="post-container">
                            
                            <div class="edit-btn text-center fit">

                            <!-- post header -->
                          
                            <a href="/tutor/updateposts" class="edit-btn btn btn-primary shadow-none" style=" width: 100px; background-color: #004AAD;">Edit</a>
                            </div>

                            <p>
                                <i class="fa-solid fa-circle-user me-3" style="font-size: 35px; color:#808080; vertical-align: middle;"></i>
                                <strong class="name me-4" style="vertical-align: middle; font-size: 1rem">Name</strong>
                                <span class="date me-1" style="vertical-align: middle;">4 March 2025</span> 
                                <span class="time me-4" style="vertical-align: middle;">9:00 PM</span> 
                                <span class="status me-0" style="vertical-align: middle;"></span>
                            </p>

                            <!-- post body -->

                           
                            <div class="post-title-desc mt-2">

                                <h5 class="mb-3 mt-2">Thingyan Holidays Announcement</h5>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet.</p>
                            
                            </div>

                            <!-- <div class="file-attachment">
                                <img src="https://cdn-icons-png.flaticon.com/512/732/732220.png" width="30" alt="File">
                                <a href="" style="text-decoration: none; color: black;" target="_blank">project_sample.docx</a>
                            
                            
                            
                                
                            </div> -->

                            <div class="comments">
                                <hr>
                                <p class="mb-3" style="font-size: 0.875rem; color:	#004AAD;">Comments</p>
                                <p>
                                    <i class="fa-solid fa-circle-user me-2" style="font-size: 20px; color:#808080; vertical-align: middle;"></i>
                                    <strong class="name me-4" style="vertical-align: middle; font-size: 1rem">Name</strong>
                                    <span class="date me-1" style="vertical-align: middle;">4 March 2025</span> 
                                    <span class="time me-4" style="vertical-align: middle;">9:00 PM</span> 
                                    
                                </p>
                                <p>Yes sir, well noted.<br>We will be missing you during the holidays sir.</p>
                            </div>

                            <form action="" method="" enctype="multipart/form-data">
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <input type="text" class="form-control" placeholder="Reply" style="max-width: 1100px;">
                                <button  type="button" class="btn btn-primary ms-3" style="width: 110px;">Send</button>
                            </div>
                            </form>
                            
                        </div>

                    </div>
            </div>



        </div>
    </div>

</div>
@endsection
@push('scripts')
<!-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    console.log("Script is loaded!");
    $(document).ready(function() {
        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });

    // $(document).ready(function(){
    //     console.log("Data table is loading..");
    //     $('#table-meeting').DataTable({
    //         paging: true,
    //         pageLength: 5,
    //         lengthChange: false,
    //         searching: false,
    //         ordering: true,
    //         "language": {
    //             "info": "Total Records: _TOTAL_",
    //         }
    //     });
    // });


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


    // Comment section reply script

    function checkReply() {
        const replyInput = document.getElementById('replyInput').value.trim();
        const commentsSection = document.getElementById('commentsSection');

        // If replyInput is not empty, show the comments section
        if (replyInput !== "") {
            commentsSection.style.display = 'block'; // Show comments section if reply is entered
        } else {
            commentsSection.style.display = 'none'; // Hide comments section if no reply is entered
        }
    }
</script>
@endpush