@extends('layouts.app')
@section('content')
<div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                    
                   <div class="login-card">
                    <div class="card mb-3">

                        <div class="card-body">

                            <div class="pt-4 pb-2">
                                <div class="login-h5">
                                    <h5 class="card-title text-center pb-0 " style="color: #004AAD;">TripleEDU </h5>
                                    <p class="card-title text-center"> E-tutoring system Login</p>
                                </div>
                            </div>

                            <form class="row g-3" action="{{ route('login') }}" method="POST">

                                @csrf
                                <div class="col-12">
                                    <label for="youremail" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}">
                                    <div class="invalid-feedback">@error('email') {{ $message }} @enderror</div>
                                </div>

                                <div class="col-12 mb-2">
                                    <label for="yourPassword" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="yourPassword" required>
                                    <div class="invalid-feedback">@error('password') {{ $message }} @enderror</div>
                                </div>
                                <div class="col-12 my-4">
                                    <button class="btn btn-primary w-100" type="submit">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                   </div>



                </div>
            </div>
        </div>

    </section>

</div>

@endsection
