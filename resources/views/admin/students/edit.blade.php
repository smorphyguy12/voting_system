@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin-sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit Student</h1>
            </div>

            <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Profile Picture</div>
                            <div class="card-body text-center">
                                <img src="{{ $student->avatar ? asset('storage/' . $student->avatar) : asset('assets/images/users/profile.png') }}" 
                                     class="img-fluid rounded-circle mb-3" 
                                     id="avatar-preview"
                                     style="width: 200px; height: 200px; object-fit: cover;">
                                <input type="file" 
                                       name="avatar" 
                                       class="form-control" 
                                       id="avatar-upload"
                                       accept="image/*">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" 
                                               name="full_name" 
                                               class="form-control" 
                                               value="{{ old('full_name', $student->full_name) }}" 
                                               required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Student ID</label>
                                        <input type="text" 
                                               class="form-control" 
                                               value="{{ $student->student_id }}" 
                                               readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" 
                                               name="email" 
                                               class="form-control" 
                                               value="{{ old('email', $student->email) }}" 
                                               required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Course</label>
                                        <input type="text" 
                                               name="course" 
                                               class="form-control" 
                                               value="{{ old('course', $student->course) }}" 
                                               required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" 
                                           name="password" 
                                           class="form-control">
                                    <small class="form-text text-muted">Leave blank if you don't want to change the password</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary">Update Student</button>
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
    const avatarUpload = document.getElementById('avatar-upload');
    const avatarPreview = document.getElementById('avatar-preview');

    avatarUpload.addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            avatarPreview.src = e.target.result;
        }

        reader.readAsDataURL(file);
    });
});
</script>
@endpush