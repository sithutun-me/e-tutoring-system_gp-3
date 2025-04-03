@php
    $user = auth()->user();
@endphp

@auth

    <div class="header">
        <nav class="navbar navbar-light bg-light justify-content-between px-4">
            <h1 class="header-title fs-4">
                <span class="text-dark fw-bold">TripleEDU</span>
            </h1>

<<<<<<< HEAD
        <div class="d-flex align-items-center">
            <!-- <div class="last-login me-3">
                <p class="text-dark mb-0">Last logged in: <span class="text-dark"> 24 Mar 2025 9:55 PM</span></p>
            </div> -->
=======
            <div class="d-flex align-items-center">
                <div class="last-login me-3">
                    <p class="text-dark mb-0">
                        @if (Auth::check() && Auth::user()->last_login_at)
                            Last login: {{ Auth::user()->last_login_at }}
                        @else
                            Welcome, {{ Auth::user()->first_name }}
                        @endif
                    </p>
                </div>
>>>>>>> 9302e19f44f0a7be94be20389f93a7386d74e514

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger">Logout</button>
                </form>
            </div>
        </nav>
    </div>

@endauth
