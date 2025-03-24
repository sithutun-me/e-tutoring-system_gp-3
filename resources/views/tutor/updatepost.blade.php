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

                <li class=""><a href="tutor/report" class="text-decoration-none px-3 py-2 d-block">
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


                <span onclick="history.back()" style="cursor: pointer;" class="header-text ms-3">
                    <i class="fa-solid fa-chevron-left"></i> <u>Back</u>
                </span>


                <div class="edit-container">


                    <div class="d-flex align-items-center mb-3">
                        <div class="profile-img"><i class="fa-solid fa-circle-user"></i></div>

                        <input type="hidden" name="create_by" value="{{ $tutor->id }}">
                        <strong class="ms-2">{{ $tutor->first_name }} {{ $tutor->last_name }}</strong>
                    </div>

                    <form action="{{ route('tutor.updatepost',$post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- <div class="student-select mb-3">
                            <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                <option  selected disabled>Choose Student</option>
                                    <option value="1">Student1</option>
                                    <option value="2">Student2</option>
                                    <option value="3">Student3</option>
                                    <option value="4">Student4</option>
                                    <option value="5">Student5</option>
                                    <option value="6">Student6</option>
                                    <option value="7">Student7</option>
                                    <option value="8">Student8</option>
                                    <option value="9">Student9</option>
                                    <option value="10">Student10</option>
                                </select>
                            </div> -->

                        <div class="mb-3">
                            <input type="text" class="form-control" name="update_title" placeholder="Add title" value="{{ $post->post_title }}">
                        </div>



                        <div class="mb-3">
                            <textarea class="form-control" rows="4" name="update_desc" placeholder="Add Description">{{ $post->post_description }}</textarea>
                        </div>

                        @foreach ($post->documents as $document)

                        <div class="file-attachment w-100 position-relative" id="file-attachment-{{ $document->id }}">
                            <img src="/icon images/word.png" width="30" alt="File">
                            <a href="" style="text-decoration: none; color:black;" target="_blank">{{ $document->doc_name }}</a>

                            <!-- Note:: this is for the file remove used with javascript for now -->
                            <button class="remove-file btn btn-danger btn-sm ms-3 float-right position-absolute end-0 me-2" onclick="removeAttachment({{ $document->id }}, this)"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        @endforeach
                        <!-- Hidden input field to store removed documents -->
                        <input type="hidden" name="removed_documents" id="removed-documents" value="[]">

                        <div class="mb-3 mt-4">
                            <input type="file" id="file-input" name="post_files_upload[]" class="form-control mb-3" multiple>
                            <small id="file-count">No file chosen</small>
                        </div>

                        <!--Selected/Chosen File List Display -->
                        <ul id="file-list" class="file-list"></ul>

                        <button type="submit" class="btn btn-primary w-100 mt-2" style="background-color: #004AAD;">Update</button>
                    </form>
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

    // Selected File List Display and Remove

    document.getElementById("file-input").addEventListener("change", function(event) {
        const fileList = document.getElementById("file-list");
        const fileCount = document.getElementById("file-count");
        fileList.innerHTML = ""; // Clear previous file list

        const files = Array.from(event.target.files);
        if (files.length === 0) {
            fileCount.textContent = "No file chosen";
        } else {
            fileCount.textContent = `${files.length} file(s) selected`;
        }

        files.forEach((file, index) => {
            const li = document.createElement("li");
            li.textContent = file.name;

            // Remove button
            const removeBtn = document.createElement("button");
            removeBtn.textContent = "X";
            removeBtn.classList.add("remove-file");
            removeBtn.addEventListener("click", function() {
                removeFile(index);
            });

            li.appendChild(removeBtn);
            fileList.appendChild(li);
        });
    });

    function removeFile(index) {
        const fileInput = document.getElementById("file-input");
        let files = Array.from(fileInput.files);

        files.splice(index, 1);

        // Create a new DataTransfer object to update the input files
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;

        // Trigger change event to update UI
        fileInput.dispatchEvent(new Event("change"));
    }


    // Attached File  Display and Remove

    function removeAttachment(docId, element) {
        //console.log("Doc id" + docId);
        let removedDocsInput = document.getElementById("removed-documents");
        let removedDocs = removedDocsInput.value ? JSON.parse(removedDocsInput.value) : [];

        removedDocs.push(docId);  // Add the document ID to the list
        removedDocsInput.value = JSON.stringify(removedDocs);
        
        //console.log("removed" + removedDocsInput.value);
        element.closest(".file-attachment").remove();
        // const attachmentDiv = document.getElementById("file-attachment");
        // if (attachmentDiv) {
        //     attachmentDiv.remove(); // Removes the file attachment div
        // }
    }
    let removedDocuments = new Set();
    $(document).ready(function() {

        let documentId = button.getAttribute("data-id");

    $('#allocationForm').on('submit', function() {
            $('#selectedStudentsContainer').empty(); // Clear existing inputs
            selectedStudents.forEach(studentId => {
                $('#selectedStudentsContainer').append(
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'selected_students[]',
                        value: studentId
                    })
                );
            });
        });
    });
</script>
@endpush
