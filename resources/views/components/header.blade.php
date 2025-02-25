@php
$user = auth()->user();
@endphp

@auth

<div class="header">
    <nav class="navbar navbar-light bg-light justify-content-between px-4">
    <h1 class="fs-4 ms-2">
                <span class="text-dark fw-bold">TripleEDU</span>
            </h1>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-danger">Logout</button>
        </form>
    </nav>
</div>
@endauth
