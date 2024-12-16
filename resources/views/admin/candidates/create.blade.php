@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin-sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Create New Candidate</h1>
            </div>

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
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">Select Student</option>
                                @foreach($eligibleStudents as $eligibleStudents)
                                    <option value="{{ $eligibleStudents->id }}">
                                        {{ $eligibleStudents->full_name }} ({{ $eligibleStudents->student_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="election_id" class="form-label">Election</label>
                            <select name="election_id" id="election_id" class="form-control" required>
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
                            <select name="candidate_role" id="candidate_role" class="form-control" required>
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
        </main>
    </div>
</div>
@endsection