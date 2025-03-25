@php
$user = auth()->user();
@endphp

@auth

<div class="header">
    <nav class="navbar navbar-light bg-light justify-content-between px-4">
        <h1 class="header-title fs-4">
            <span class="text-dark fw-bold">TripleEDU</span>
        </h1>

        <div class="d-flex align-items-center">
            <!-- <div class="last-login me-3">
                <p class="text-dark mb-0">Last login</p>
            </div> -->

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-danger">Logout</button>
            </form>
        </div>
    </nav>
</div>

@endauth
