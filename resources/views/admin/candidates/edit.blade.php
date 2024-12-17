<?php
$title = "Edit Candidate Profile";
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
                    <i class="fas fa-user-edit me-2"></i>Edit Candidate Profile
                </h4>
                <div class="page-actions">
                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-soft-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Candidates
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data" id="candidateEditForm">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Profile Picture Section -->
            <div class="col-lg-4">
                <div class="card profile-upload-card">
                    <div class="card-header bg-soft-primary">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-image me-2"></i>Profile Picture
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="profile-image-container mb-3">
                            <img
                                src="{{ $candidate->profile_picture ? asset('storage/student_avatars/' . $candidate->profile_picture) : asset('storage/student_avatars/default.png') }}"
                                class="img-fluid rounded-circle profile-preview"
                                id="profile-preview"
                                alt="Profile Picture">
                            <div class="profile-image-overlay">
                                <label for="profile-upload" class="btn btn-icon btn-soft-primary">
                                    <i class="fas fa-camera"></i>
                                </label>
                            </div>
                        </div>
                        <input
                            type="file"
                            name="profile_picture"
                            id="profile-upload"
                            class="form-control d-none"
                            accept="image/png,image/jpeg,image/gif">

                        @error('profile_picture')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">Recommended: PNG or JPG, max 5MB</small>
                    </div>
                </div>
            </div>

            <!-- Candidate Details Section -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-soft-primary">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-tie me-2"></i>Candidate Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Student <span class="text-danger">*</span></label>
                                <select
                                    name="user_id"
                                    class="form-select select2"
                                    required>
                                    @foreach($students as $student)
                                    <option
                                        value="{{ $student->id }}"
                                        {{ $candidate->user_id == $student->id ? 'selected' : '' }}>
                                        {{ $student->full_name }} ({{ $student->student_id }})
                                    </option>
                                    @endforeach
                                </select>

                                @error('user_id')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Election <span class="text-danger">*</span></label>
                                <select
                                    name="election_id"
                                    class="form-select select2"
                                    required>
                                    @foreach($elections as $election)
                                    <option
                                        value="{{ $election->id }}"
                                        {{ $candidate->election_id == $election->id ? 'selected' : '' }}>
                                        {{ $election->name }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('election_id')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Candidate Role <span class="text-danger">*</span></label>
                                <select
                                    name="candidate_role"
                                    class="form-select"
                                    required>
                                    @foreach(['President', 'Vice President', 'Secretary', 'Treasurer'] as $role)
                                    <option
                                        value="{{ $role }}"
                                        {{ $candidate->candidate_role == $role ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('candidate_role')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Platform Statement</label>
                                <textarea
                                    name="platform"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Describe your campaign platform...">{{ old('platform', $candidate->platform) }}</textarea>

                                @error('platform')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Candidate
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection

@push('styles')
<style>
    .profile-image-container {
        position: relative;
        max-width: 200px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .profile-image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .profile-image-container:hover .profile-image-overlay {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileUpload = document.getElementById('profile-upload');
        const profilePreview = document.getElementById('profile-preview');

        profileUpload.addEventListener('change', function(event) {
            const file = event.target.files[0];
            const maxSizeInBytes = 2 * 1024 * 1024; // 2MB

            if (file.size > maxSizeInBytes) {
                alert('File is too large. Maximum size is 2MB.');
                profileUpload.value = ''; // Clear the file input
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        });

        // Optional: Form validation
        document.getElementById('candidateEditForm').addEventListener('submit', function(event) {
            event.preventDefault();
            // Perform additional validation if necessary
            this.submit();
        });
    });
</script>
@endpush