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
                <div class="last-login me-3">
                    <p class="text-dark mb-0">
                        @if (Auth::check() && Auth::user()->last_login_at)
                            {{ Auth::user()->first_name }}, Last login: {{ Auth::user()->last_login_at }}
                        @else
                            Welcome, {{ Auth::user()->first_name }}
                        @endif
                    </p>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger">Logout</button>
                </form>
            </div>
        </nav>
    </div>

@endauth
