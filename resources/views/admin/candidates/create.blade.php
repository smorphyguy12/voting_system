<?php
$title = "Create Candidate";
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
                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-soft-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Candidates List 
                    </a>
                </div>
            </div>
            <h4 class="page-title">Create New Candidate</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <form action="{{ route('admin.candidates.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="user_id" class="form-label">Student</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">Select Student</option>
                            @foreach($eligibleStudents as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->full_name }} ({{ $student->student_id }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="election_id" class="form-label">Election</label>
                        <select name="election_id" id="election_id" class="form-select" required>
                            <option value="">Select Election</option>
                            @foreach($elections as $election)
                            <option value="{{ $election->id }}">
                                {{ $election->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="candidate_role" class="form-label">Candidate Role</label>
                        <select name="candidate_role" id="candidate_role" class="form-select" required>
                            <option value="">Select Role</option>
                            <option value="President">President</option>
                            <option value="Vice President">Vice President</option>
                            <option value="Secretary">Secretary</option>
                            <option value="Treasurer">Treasurer</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="platform" class="form-label">Platform (Optional)</label>
                        <textarea name="platform" id="platform" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Create Candidate</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection