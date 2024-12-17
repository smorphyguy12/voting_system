<?php
$title = "Candidate Profile";
$sideBar = "partials/admin-sidebar";
?>

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">
                    <i class="fas fa-user-tie me-2"></i>Candidate Profile
                </h4>
                <div class="page-actions">
                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-soft-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Candidates List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Candidate Details -->
    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card profile-card">
                <div class="card-body text-center">
                    <div class="profile-image-container mb-3">
                        <img 
                            src="{{ $candidate->profile_picture ? asset('storage/student_avatars/' . $candidate->profile_picture) : asset('storage/student_avatars/default.png') }}"
                            class="img-fluid rounded-circle profile-image"
                            alt="{{ $candidate->user->full_name }}"
                        >
                    </div>
                    <h4 class="card-title mb-2">{{ $candidate->user->full_name }}</h4>
                    <div class="text-muted mb-3">
                        <span class="badge bg-soft-primary">
                            <i class="fas fa-briefcase me-1"></i>{{ $candidate->candidate_role }}
                        </span>
                    </div>
                    <div class="candidate-meta">
                        <div class="mb-2">
                            <strong>Election:</strong> 
                            <span class="text-primary">{{ $candidate->election->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Platform Details -->
        <div class="col-lg-8">
            <div class="card platform-card">
                <div class="card-header bg-soft-primary">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-scroll me-2"></i>Campaign Platform
                    </h5>
                </div>
                <div class="card-body">
                    @if($candidate->platform)
                        <div class="platform-content">
                            {{ $candidate->platform }}
                        </div>
                    @else
                        <div class="alert alert-light text-center" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            No platform statement provided
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .profile-image-container {
        max-width: 200px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .profile-image {
        width: 100%;
        height: auto;
        object-fit: cover;
    }
    .platform-card .platform-content {
        line-height: 1.6;
    }
</style>
@endpush