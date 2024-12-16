@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin-sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit Election: {{ $election->name }}</h1>
            </div>

            <form action="{{ route('admin.elections.update', $election->id) }}" method="POST">
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
                                value="{{ old('name', $election->name) }}" 
                                required
                            >
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
                                    value="{{ old('start_date', $election->start_date ? $election->start_date->format('Y-m-d\TH:i') : '') }}" 
                                    required
                                >
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
                                    value="{{ old('end_date', $election->end_date ? $election->end_date->format('Y-m-d\TH:i') : '') }}" 
                                    required
                                >
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="upcoming" {{ $election->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="active" {{ $election->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="closed" {{ $election->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Election</button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>
@endsection