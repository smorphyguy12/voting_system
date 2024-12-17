<?php
$title = "Candidates Management";
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
                    <a href="{{ route('admin.candidates.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus"></i> Add New Candidate
                    </a>
                </div>
            </div>
            <h4 class="page-title">Candidates Management</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="candidateTable" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Profile</th>
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
                                    <img src="{{ Storage::url('student_avatars/' . ($candidate->avatar ?? 'default.png')) }}"
                                        class="rounded-circle mr-2"
                                        style="width: 40px; height: 40px;"
                                        alt="{{ $candidate->user->full_name }}">
                                </td>
                                <td>
                                    {{ $candidate->user->full_name }}
                                </td>
                                <td>{{ $candidate->election->name }}</td>
                                <td>{{ $candidate->candidate_role }}</td>
                                <td>{{ $candidate->user->course }}</td>
                                <td>{{ $candidate->votes_count ?? 0 }}</td>
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- third party css -->
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<!-- third party css end -->
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#candidateTable').DataTable();

        $(document).on('click', '.delete-candidate', function() {
    const candidateId = $(this).data('id');
    const $row = $(this).closest('tr');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/candidates/${candidateId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Delete request failed');
                }
                return response.json();
            })
            .then(() => {
                $row.remove();
                Swal.fire(
                    'Deleted!', 
                    'The candidate has been deleted.', 
                    'success'
                );
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Error!', 
                    'Unable to delete the candidate.', 
                    'error'
                );
            });
        }
    });
});
    });
</script>
@endpush