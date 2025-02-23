@php
$user = auth()->user();
@endphp

@auth
<div class="header">
    <nav class="navbar navbar-light bg-light justify-content-between px-4">
        <a class="navbar-brand">Navbar</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-danger">Logout</button>
        </form>
    </nav>
</div>
@endauth
