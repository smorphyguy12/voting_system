@extends('layouts.auth')

<?php
$title = "Login"; ?>

<style>
    body {
        background-image: url('{{ asset("assets/images/bg-auth.jpg") }}');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        min-height: 100vh;
    }
</style>
</head>

@section('content')
<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card bg-pattern">

                    <div class="card-body p-4">
                        <div class="text-center w-75 m-auto">
                            <div class="auth-brand">
                                <a href="index.php" class="logo logo-dark text-center">
                                    <span class="logo-lg">
                                        <img src="{{ asset('assets/images/logo.png') }}" alt="" height="100">
                                    </span>
                                </a>

                                <a href="index.php" class="logo logo-light text-center">
                                    <span class="logo-lg">
                                        <img src="{{ asset('assets/images/logo.png') }}" alt="" height="100">
                                    </span>
                                </a>
                            </div>
                            <p class="text-muted mb-4 mt-3">Enter your email address and password to access admin panel.</p>
                        </div>
                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input
                                    id="student_id"
                                    type="text"
                                    class="form-control @error('student_id') is-invalid @enderror"
                                    name="student_id"
                                    value="{{ old('student_id') }}"
                                    required
                                    autocomplete="student_id"
                                    autofocus>
                                @error('student_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <input
                                        id="password"
                                        type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password"
                                        required
                                        autocomplete="current-password">
                                    <div class="input-group-text toggle-password" data-target="password">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="remember"
                                        id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                            </div>

                            <div class="text-center d-grid">
                                <button class="btn btn-primary" type="submit">Log In</button>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const passwordToggles = document.querySelectorAll('.toggle-password');

                                passwordToggles.forEach(toggle => {
                                    toggle.addEventListener('click', function() {
                                        const targetId = this.getAttribute('data-target');
                                        const passwordInput = document.getElementById(targetId);

                                        if (passwordInput.type === 'password') {
                                            passwordInput.type = 'text';
                                            this.querySelector('.password-eye').classList.add('active');
                                        } else {
                                            passwordInput.type = 'password';
                                            this.querySelector('.password-eye').classList.remove('active');
                                        }
                                    });
                                });
                            });
                        </script>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p> <a href="{{ route('password.request') }}" class="text-white-50 ms-1">Forgot your password?</a></p>
                        <p class="text-white-50">Don't have an account? <a href="{{ route('register') }}" class="text-white ms-1"><b>Sign Up</b></a></p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->
@endsection