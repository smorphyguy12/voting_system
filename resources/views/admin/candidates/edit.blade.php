@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin-sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit Candidate</h1>
            </div>

            <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Profile Picture</div>
                            <div class="card-body">
                                <img src="{{ $candidate->profile_picture ? asset('storage/' . $candidate->profile_picture) : asset('default-avatar.png') }}" 
                                     class="img-fluid mb-3" 
                                     id="profile-preview">
                                <input type="file" 
                                       name="profile_picture" 
                                       class="form-control" 
                                       id="profile-upload"
                                       accept="image/*">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Student</label>
                                    <select name="user_id" class="form-control" required>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}"
                                                    {{ $candidate->user_id == $student->id ? 'selected' : '' }}>
                                                {{ $student->full_name }} ({{ $student->student_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Election</label>
                                    <select name="election_id" class="form-control" required>
                                        @foreach($elections as $election)
                                            <option value="{{ $election->id }}"
                                                    {{ $candidate->election_id == $election->id ? 'selected' : '' }}>
                                                {{ $election->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Candidate Role</label>
                                    <select name="candidate_role" class="form-control" required>
                                        @foreach(['President', 'Vice President', 'Secretary', 'Treasurer'] as $role)
                                            <option value="{{ $role }}"
                                                    {{ $candidate->candidate_role == $role ? 'selected' : '' }}>
                                                {{ $role }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Platform</label>
                                    <textarea name="platform" class="form-control" rows="4">{{ $candidate->platform }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Update Candidate</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileUpload = document.getElementById('profile-upload');
    const profilePreview = document.getElementById('profile-preview');

    profileUpload.addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            profilePreview.src = e.target.result;
        }

        reader.readAsDataURL(file);
    });
});
</script>
@endpush