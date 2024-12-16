@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin-sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Elections Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createElectionModal">
                        <i class="fas fa-plus"></i> Create New Election
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($elections as $election)
                        <tr>
                            <td>{{ $election->name }}</td>
                            <td>{{ $election->start_date->format('d M Y') }}</td>
                            <td>{{ $election->end_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge 
                                    @if($election->status == 'active') bg-success
                                    @elseif($election->status == 'upcoming') bg-warning
                                    @else bg-secondary
                                    @endif
                                ">
                                    {{ ucfirst($election->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.elections.show', $election->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-warning edit-election" data-id="{{ $election->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-election" data-id="{{ $election->id }}">
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

<!-- Create Election Modal -->
<div class="modal fade" id="createElectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.elections.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create New Election</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Election Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" class="form-control" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" class="form-control" name="end_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Election</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete Election Handler
    document.querySelectorAll('.delete-election').forEach(button => {
        button.addEventListener('click', function() {
            const electionId = this.dataset.id;
            if(confirm('Are you sure you want to delete this election?')) {
                fetch(`/admin/elections/${electionId}`, {
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