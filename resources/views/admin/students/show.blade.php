<?php
$title = "Student Details";
$sideBar = "partials/admin-sidebar";
?>
@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <div class="d-flex align-items-center mb-3">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-soft-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Students List
                    </a>
                </div>
            </div>
            <h4 class="page-title">Student Details</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Profile Picture</div>
                <div class="card-body text-center">
                    <img src="{{ $student->avatar ? asset('storage/' . $student->avatar) : asset('assets/images/users/profile.png') }}"
                        class="img-fluid rounded-circle mb-3"
                        style="width: 200px; height: 200px; object-fit: cover;">
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $student->full_name }}</h5>
                    <p class="card-text"><strong>Student ID:</strong> {{ $student->student_id }}</p>
                    <p class="card-text"><strong>Email:</strong> {{ $student->email }}</p>
                    <p class="card-text"><strong>Course:</strong> {{ $student->course }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection