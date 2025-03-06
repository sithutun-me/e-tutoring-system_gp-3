@php
$user = auth()->user();
@endphp

@auth

<div class="header">
    <nav class="navbar navbar-light bg-light justify-content-between px-4">
     <h1 class=" header-title fs-4">
                <span class="text-dark fw-bold">TripleEDU</span>
        </h1>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-danger">Logout</button>
        </form>
    </nav>
    <div class="container">
        @if($errors->any())
            <div class="alert alert-warning">
                <ol>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ol>
            </div>
        @endif
    </div>
</div>
@endauth
