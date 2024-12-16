@extends('layouts.auth')

<?php
$title = "Register"; ?>

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
                            <p class="text-muted mb-4 mt-3">Don't have an account? Create your account, it takes less than a minute</p>
                        </div>
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class=" mb-3">
                                <label for="student_id" class="form-label">Student ID</label>
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control @error('student_id') is-invalid @enderror"
                                        id="student_id"
                                        name="student_id"
                                        value="{{ old('student_id') }}"
                                        required
                                        placeholder="Enter your Student ID">
                                    @error('student_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control @error('full_name') is-invalid @enderror"
                                        id="full_name"
                                        name="full_name"
                                        value="{{ old('full_name') }}"
                                        required
                                        placeholder="Enter your full name">
                                    @error('full_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <input
                                        type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        placeholder="Enter your email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="course" class="form-label">Course</label>
                                <div class="input-group">
                                    <select
                                        class="form-control @error('course') is-invalid @enderror"
                                        id="course"
                                        name="course"
                                        class="form-select"
                                        required>
                                        <option value="">Select Course</option>
                                        <option value="BSIT">BSIT</option>
                                        <option value="BSMB">BSMB</option>
                                        <option value="BSA">BSA</option>
                                        <option value="BSFI">BSFI</option>
                                    </select>
                                    @error('course')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        required
                                        placeholder="Create a strong password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    At least 8 characters, with uppercase, lowercase, number, and symbol
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        required
                                        placeholder="Confirm your password">
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="checkbox-signup" required>
                                    <label class="form-check-label" for="checkbox-signup">I accept <a href="javascript: void(0);" class="text-dark">Terms and Conditions</a></label>
                                </div>
                            </div>

                            <div class="text-center d-grid">
                                <button class="btn btn-success" type="submit"> Sign Up </button>
                            </div>
                        </form>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-white-50">Already have account? <a href="{{ route('login') }}" class="text-white ms-1"><b>Sign In</b></a></p>
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