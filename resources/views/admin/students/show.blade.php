@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin-sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Student Details</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-sm btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>

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
        </main>
    </div>
</div>
@endsection