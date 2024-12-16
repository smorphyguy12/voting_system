@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin-sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Candidates Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.candidates.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus"></i> Add New Candidate
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Election</th>
                            <th>Role</th>
                            <th>Course</th>
                            <th>Votes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($candidates as $candidate)
                        <tr>
                            <td>
                                <img src="{{ $candidate->avatar ?? 'default-avatar.png' }}" 
                                     class="rounded-circle mr-2" 
                                     style="width: 40px; height: 40px;">
                                {{ $candidate->user->full_name }}
                            </td>
                            <td>{{ $candidate->election->name }}</td>
                            <td>{{ $candidate->candidate_role }}</td>
                            <td>{{ $candidate->user->course }}</td>
                            <td>{{ $candidate->votes_count }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.candidates.show', $candidate->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.candidates.edit', $candidate->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger delete-candidate" data-id="{{ $candidate->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete Candidate Handler
    document.querySelectorAll('.delete-candidate').forEach(button => {
        button.addEventListener('click', function() {
            const candidateId = this.dataset.id;
            if(confirm('Are you sure you want to delete this candidate?')) {
                fetch(`/admin/candidates/${candidateId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => {
                    if(response.ok) {
                        this.closest('tr').remove();
                    }
                });
            }
        });
    });
});
</script>
@endpush