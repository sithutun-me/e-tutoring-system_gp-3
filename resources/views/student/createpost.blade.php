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
            <li class=""><a href="/student/dashboard" class="text-decoration-none px-3 py-2 d-block">
                    <img src="/icon images/dashboard.png" style="width:20px; margin-right: 10px;"> Dashboard
                </a>
            </li>
            <li class=""><a href="/student/meetinglists" class="text-decoration-none px-3 py-2 d-block">
                    <img src="/icon images/meeting.png" style="width:20px; margin-right: 10px;"> Meetings
                </a>
            </li>
            <li class=""><a href="/student/blogging" class="text-decoration-none px-3 py-2 d-block ">
                    <img src="/icon images/blogging.png" style="width:20px; margin-right: 10px;"> Blogging

                </a>
            </li>
            <!-- <li class=""><a href="#" class="text-decoration-none px-3 py-2 d-block">
                    <img src="/icon images/notification.png" style="width:20px; margin-right: 10px;"> Notifications
                </a>
            </li> -->

            <li class=""><a href="/student/report" class="text-decoration-none px-3 py-2 d-block">
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

        <div class="dashboard-content px-2 pt-4">


            <span onclick="history.back()" style="cursor: pointer;" class="header-text ms-3">
                <i class="fa-solid fa-chevron-left"></i> <u>Back</u>
            </span>

            <form id="postForm"
                action="{{ route('student.savepost') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="create_by" value="{{ $student->id }}">
                <input type="hidden" name="received_by" value="{{ $tutor->id }}">
                <div class="create-container" style="margin-left: 20px;">


                    <div class="d-flex align-items-center mb-3">
                        <div class="profile-img"><i class="fa-solid fa-circle-user"></i></div>
                        <strong class="ms-2">{{ $student->first_name }} {{ $student->last_name }}</strong>
                    </div>

                    <div class="mb-3">
                        <input type="text" id="studentAddTitle" class="form-control" name="post_title" value="{{ old('post_title') }}" placeholder="Add title *">
                    </div>



                    <div class="mb-3">
                        <textarea class="form-control" rows="4" id="studentDescription" placeholder="Add Description" name="post_desc">{{ old('post_desc') }}</textarea>
                    </div>


                    <div class="mb-3 mt-4">
                        <input type="file" id="studentFileInput" name="post_files[]" class="form-control mb-3" multiple>
                        <small id="studentFileCount">No file chosen</small>
                    </div>

                    <!--Selected/Chosen File List Display -->
                    <ul id="fileList" class="file-list" name="doc_files"></ul>

                    <button type="submit" id="upload-btn" class="btn btn-primary w-100 mt-2" style="background-color: #004AAD;">Post</button>
                </div>
            </form>


        </div>



    </div>
</div>

</div>


<div id="studentErrorModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 500;">Validation Errors!</h5>
                <button type="button" class="confirm-btn close btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id">
                <div class="modal-body">
                    @foreach ($errors->all() as $error)
                    <li style="font-family: 'Poppins'; font-size:1rem;margin-bottom:10px;">
                        {{ $error }}
                    </li>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer ">
                <button onclick="closeModal()" type="button" class="btn btn-danger" data-bs-dismiss="modal"
                    aria-label="Close" style=" width: 90px;">Close</i></button>
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

    let fileArray = [];

    document.getElementById("studentFileInput").addEventListener("change", function(event) {
        const fileList = document.getElementById("fileList");
        const fileCount = document.getElementById("studentFileCount");

        const files = Array.from(event.target.files);
        if (files.length === 0) {
            fileCount.textContent = "No file chosen";
            return;
        }

        // Add new files while preventing duplicates
        files.forEach(file => {
            if (!fileArray.some(f => f.name === file.name && f.size === file.size)) {
                fileArray.push(file);
            }
        });

        updateFileList();
    });

    function updateFileList() {
        const fileList = document.getElementById("fileList");
        const fileCount = document.getElementById("studentFileCount");

        fileList.innerHTML = ""; // Clear UI before updating

        if (fileArray.length === 0) {
            fileCount.textContent = "No file chosen";
        } else {
            fileCount.textContent = `${fileArray.length} file(s) selected`;
        }

        fileArray.forEach((file, index) => {
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

        updateFileInput(); // Sync input with selected files
    }

    function updateFileInput() {
        const fileInput = document.getElementById("studentFileInput");
        const dataTransfer = new DataTransfer();

        fileArray.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }
    document.getElementById("upload-btn").addEventListener("click", function() {
        if (fileArray.length === 0) {
            return;
        }

        const formData = new FormData();
        fileArray.forEach(file => {
            formData.append("files[]", file);
        });

        // Example: send formData to the server
        console.log("Uploading files...", formData);
    });



    function removeFile(index) {
        const fileInput = document.getElementById("studentFileInput");
        let files = Array.from(fileInput.files);

        files.splice(index, 1);

        // Create a new DataTransfer object to update the input files
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;

        // Trigger change event to update UI
        fileInput.dispatchEvent(new Event("change"));
    }
</script>
@if ($errors->any())
<script>
    // window.errors = @json($errors->all());
    var myModal = new bootstrap.Modal(document.getElementById('studentErrorModal'));
    myModal.show();
</script>
@endif
@endpush
