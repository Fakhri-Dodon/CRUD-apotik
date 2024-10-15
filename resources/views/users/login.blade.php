@extends('layouts.layout_login')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5"> <!-- Increased column width -->
                <div class="card shadow border-0 rounded-3">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Login</h2>



                        <form action="{{ route('login.auth') }}" method="POST">
                            @csrf
                            @if (Session::get('failed'))
                                <div class="alert alert-danger text-center">{{ Session::get('failed') }}</div>
                            @endif
                            @if (Session::get('logout'))
                                <div class="alert alert-primary text-center">{{ Session::get('logout') }}</div>
                            @endif
                            @if (Session::get('canAccess'))
                                <div class="alert alert-danger text-center">{{ Session::get('canAccess') }}</div>
                            @endif
                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="you@example.com" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Your password" required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" id="remember" name="remember" class="form-check-input">
                                <label for="remember" class="form-check-label">Remember Me</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>

                        <div class="text-center mt-2">
                            <small><a href="#" class="text-secondary">Forgot Password?</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
