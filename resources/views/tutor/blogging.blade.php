@extends('layouts.app')
@section('content')
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
            <li class=""><a href="/tutor/report" class="text-decoration-none px-3 py-2 d-block">
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
            <div class="post mt-4">
                <div class="text-center fit mb-2">
                    <a href="{{ route('tutor.createpost') }}" class="post-btn btn btn-primary shadow-none mb-3" style="background-color: #004AAD;">+ Start a post</a>
                </div>


                <div class="form-group mb-4 row">
                    <form action="{{ route('tutor.blogging') }}" method="GET" enctype="multipart/form-data">
                        <div class="row">

                            <div class="col-md-3 mb-2 d-flex justify-content-center align-items-center">
                                <select class="form-select" id="postBy" name="post_by" aria-label="Floating label select example">
                                    <option value="All" {{ request('post_by') == '' ? 'selected' : '' }}>All</option>
                                    <option value="MyPosts" {{ request('post_by') == 'MyPosts' ? 'selected' : '' }}>My Posts</option>
                                    <option value="StudentPosts" {{ request('post_by') == 'StudentPosts' ? 'selected' : '' }}>Student Posts</option>
                                </select>
                            </div>


                            <div class="col-md-3 mb-2 d-flex justify-content-center align-items-center">
                                <select class="form-select form--control" name="student_filter" id="studentFilter" aria-label="Floating label select example">
                                    <option value="" {{ request('student_filter') == '' ? 'selected' : '' }}>-- Choose Student --</option>
                                    @foreach ($students as $student)
                                    <option value="{{ $student->id }}" {{ request('student_filter') == $student->id ? 'selected' : '' }}>{{ $student->first_name }} {{ $student->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="postSearch col-md-3 mb-2 d-flex justify-content-center align-items-center">
                                <input id="postSearch" name="search_post" class="form-control" type="search" placeholder="Search here" aria-label="Search" value="{{ request()->input('search_post') }}">
                            </div>
                            <div class="col-md-3 mb-2 d-flex flex-column align-items-start">
                                <div class=" text-center">
                                    <button type="submit" class=" btn btn-primary shadow-none " >Search</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                @forelse ($posts as $post)
                <div class="post-container">
                    <!-- post header -->
                    @php
                    $user = auth()->user();
                    @endphp
                    @if($post->creator->id == $user->id)
                    <div class="edit-btn text-center fit">
                        <a href="{{  route('tutor.editpost',$post->id) }}" class="edit-btn btn btn-primary shadow-none" style=" width: 100px; background-color: #004AAD;">Edit</a>
                        <a href="{{  route('tutor.editpost',$post->id) }}" class="delete-btn btn  shadow-none" style=" width:50px; background-color:#d9534f "><i class="fa-solid fa-trash"></i></a>

                    </div>
                    @endif
                    <p>
                        <i class="fa-solid fa-circle-user me-3" style="font-size: 35px; color:#808080; vertical-align: middle;"></i>
                        <strong class="name me-4" style="vertical-align: middle; font-size: 1rem; font-family:'Poppins';">{{ $post->creator?->first_name }} {{ $post->creator?->last_name }}</strong>
                        <span class="date me-1" style="vertical-align: middle; font-family:'Poppins';">{{ \Carbon\Carbon::parse($post->updated_at)->format('d M Y') }}</span>
                        <span class="time me-4" style="vertical-align: middle; font-family:'Poppins';">{{ \Carbon\Carbon::parse($post->updated_at)->format('h:m A') }}</span>
                        <span class="status me-0" style="vertical-align: middle; font-family:'Poppins';">
                            @if ($post->created_at != $post->updated_at)
                            Updated
                            @else
                            {{ $post->post_status }}
                            @endif
                        </span>
                    </p>
                    <!-- Post body -->
                    <div class="post-title-desc mt-2">
                        <h5 class="mb-3 mt-2">{{ $post->post_title }}</h5>
                        <p>{{ $post->post_description }}</p>
                    </div>
                    @foreach ($post->documents as $document)
                    <div class="file-attachment d-flex row mx-1" id="docFile">
                        <div class="d-flex  align-items-center">
                            <img src="/icon images/document.png" width="30" alt="File">
                            <a href="{{ asset($document->doc_file_path) }}" style="text-decoration: none; color: black;" target="_blank">
                                @if(strlen(__(@$document->doc_name)) > 30)
                                {{substr(__(@$document->doc_name), 0,30).'...' }}
                                @else
                                {{__(@$document->doc_name) }}
                                @endif
                            </a>
                        </div>
                    </div>
                    @endforeach
                    <!-- Note:: this comment section has javascript interaction added for now, when reply button is clicked this will appear -->
                    <div class="comments pb-0" id="commentsSection">
                            <hr>
                            <p class="mb-3" style="font-size: 0.875rem; color:  #004AAD;">Comments</p>
                        @foreach ($post->comments as $comment)
                        <div class="comment-item">

                            <p>
                                <i class="fa-solid fa-circle-user me-2" style="font-size: 20px; color:#808080; vertical-align: middle;"></i>
                                <strong class="name me-4" style="vertical-align: middle; font-size: 1rem">{{ $comment->user->first_name }} {{ $comment->user->last_name }}</strong>
                                <span class="date me-1" style="vertical-align: middle;">{{ \Carbon\Carbon::parse($post->updated_at)->format('d M Y') }}</span>
                                <span class="time me-4" style="vertical-align: middle;">{{ \Carbon\Carbon::parse($post->updated_at)->format('h:m A') }}</span>

                                    <!-- Three-dot Menu for comment edit and delete next to Time -->
                                    <span class="three-dots" onclick="toggleMenu(this)" style="cursor: pointer; vertical-align: middle;">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </span>

                                    <!-- Hidden Edit & Delete Options -->
                                    <span class="ms-2 options-menu" style="display: none;">
                                        <button class="edit-comment btn btn-primary">Edit</button>
                                        <button class="delete-comment btn btn-danger" style="background-color: #d9534f;" >Delete</button>
                                    </span>


                            </p>
                            <p class="comments-body" style="margin-left: 30px;">{{ $comment->text }}</p>

                        </div>

                        @endforeach
                            <!-- See More & See less Button -->
                            <!-- Unique Show More & Show Less Buttons for each post -->
                            <button class="btn btn-primary show-more-btn mt-2" data-post-id="{{ $post->id }}" style="display: none;">See More</button>
                            <button class="btn btn-primary show-less-btn mt-2" data-post-id="{{ $post->id }}" style="display: none;">See Less</button>
                        </div>


                    <form action="{{ route('tutor.postcomment', $post->id) }}" method="POST" enctype="multipart/form-data" id="commentForm_{{ $post->id }}" class="comment-form">
                        @csrf
                        <div class="d-flex align-items-center gap-2 mt-4">
                            <input type="text" id="replyInput" name="comment" class="form-control" placeholder="Reply" style="max-width: 1100px;">
                            <button type="submit" class="btn btn-primary ms-3" style="width: 110px;" onclick="checkReply('{{ $post->id }}')">Send</button>
                        </div>
                    </form>
                </div>
                @empty
                <div class="post-container">
                    <div class="d-flex justify-content-center items-align-center">
                        Post not found!
                    </div>
                </div>
                @endforelse

            </div>
        </div>
    </div>
</div>
<!-- </div> -->
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

        // $('#postBy').change(function() {
        //     if ($(this).val() == 'student') {
        //         $('#studentFilter').prop('disabled', false);
        //     } else {
        //         $('#studentFilter').prop('disabled', true);
        //     }
        // });
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


    // Comment section reply script for now

    function checkReply(postId) {
        // event.preventDefault(); // Prevent the default form submission
        const replyInput = document.getElementById('replyInput').value.trim();
        const commentsSection = document.getElementById('commentsSection');


        if (replyInput !== "") {
            commentsSection.style.display = 'block'; // Show comments section if reply is entered
        } else {
            commentsSection.style.display = 'none'; // Hide comments section if no reply is entered
        }
        console.log("Post id : " + postId);


    }

    // For see more and see less comment section

//     document.addEventListener("DOMContentLoaded", function () {
//     let comments = document.querySelectorAll('#commentsSection .comment-item');
//     let showMoreBtn = document.getElementById('showMoreBtn');
//     let showLessBtn = document.getElementById('showLessBtn');

//     // Hide comments beyond the first 3
//     if (comments.length > 3) {
//         comments.forEach((comment, index) => {
//             if (index >= 3) {
//                 comment.style.display = "none";
//             }
//         });

//         // Show "Show More" button if more than 3 comments exist
//         showMoreBtn.style.display = "block";

//         // "Show More" Button Click Event
//         showMoreBtn.addEventListener("click", function () {
//             comments.forEach(comment => comment.style.display = "block");
//             showMoreBtn.style.display = "none"; // Hide Show More button
//             showLessBtn.style.display = "block"; // Show Show Less button
//         });

//         // "Show Less" Button Click Event
//         showLessBtn.addEventListener("click", function () {
//             comments.forEach((comment, index) => {
//                 if (index >= 3) {
//                     comment.style.display = "none"; // Hide comments beyond the first 3
//                 }
//             });
//             showMoreBtn.style.display = "block"; // Show Show More button
//             showLessBtn.style.display = "none"; // Hide Show Less button
//         });
//     }
// });


   // For see more and see less comment section

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.comments').forEach(commentsSection => {
        let comments = commentsSection.querySelectorAll('.comment-item');
        let showMoreBtn = commentsSection.querySelector('.show-more-btn');
        let showLessBtn = commentsSection.querySelector('.show-less-btn');

        // Ensure at least 3 comments exist before hiding
        if (comments.length > 3) {
            comments.forEach((comment, index) => {
                if (index >= 3) {
                    comment.style.display = "none"; // Hide extra comments
                }
            });

            // Show "See More" button
            showMoreBtn.style.display = "block";

            // Show More Click Event (Scoped to Current Post)
            showMoreBtn.addEventListener("click", function () {
                comments.forEach(comment => comment.style.display = "block"); // Show all comments
                showMoreBtn.style.display = "none";  // Hide "See More" button
                showLessBtn.style.display = "block"; // Show "See Less" button
            });

            // Show Less Click Event (Scoped to Current Post)
            showLessBtn.addEventListener("click", function () {
                comments.forEach((comment, index) => {
                    if (index >= 3) {
                        comment.style.display = "none"; // Hide comments beyond the first 3
                    }
                });
                showMoreBtn.style.display = "block";  // Show "See More" button
                showLessBtn.style.display = "none";   // Hide "See Less" button
            });
        }
    });
});



// Three dot menu for edit and delete btn

function toggleMenu(dotIcon) {
    // Find the closest <p> tag
    let parentP = dotIcon.closest('p');

    // Find the options menu within the same <p>
    let menu = parentP.querySelector('.options-menu');

    // Toggle the display of the menu
    menu.style.display = (menu.style.display === "none" || menu.style.display === "") ? "inline-block" : "none";
}



</script>
@endpush
