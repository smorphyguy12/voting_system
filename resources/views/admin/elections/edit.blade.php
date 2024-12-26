<?php
$title = "Edit Election";
$sideBar = "partials/admin-sidebar";
?>

@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <h4 class="page-title">{{ $election->name ?? 'Election' }}</h4>
            <a href="{{ route('admin.elections.index') }}" class="btn btn-soft-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Elections List
            </a>
        </div>
    </div>
</div>

<div class="row">
    <form action="{{ route('admin.elections.update', $election->id ?? '') }}" method="POST">
        @csrf
        @method('PUT')

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
                    <label for="name" class="form-label">Election Name</label>
                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        id="name"
                        name="name"
                        value="{{ old('name', $election->name ?? '') }}"
                        required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input
                            type="datetime-local"
                            class="form-control @error('start_date') is-invalid @enderror"
                            id="start_date"
                            name="start_date"
                            value="{{ old('start_date', isset($election->start_date) ? $election->start_date->format('Y-m-d\TH:i') : '') }}"
                            required>
                        @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input
                            type="datetime-local"
                            class="form-control @error('end_date') is-invalid @enderror"
                            id="end_date"
                            name="end_date"
                            value="{{ old('end_date', isset($election->end_date) ? $election->end_date->format('Y-m-d\TH:i') : '') }}"
                            required>
                        @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select select2" required>
                        <option value="upcoming" {{ (old('status')?? $election->status) == 'upcoming' ?'selected' : '' }}>Upcoming</option>
                        <option value="active" {{ (old('status')?? $election->status) == 'active' ?'selected' : '' }}>Active</option>
                        <option value="closed" {{ (old('status')?? $election->status) == 'closed' ?'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Update Election</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush