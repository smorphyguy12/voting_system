@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin-sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Candidate Details</h1>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="{{ $candidate->profile_picture ? asset('storage/' . $candidate->profile_picture) : asset('default-avatar.png') }}" 
                             class="card-img-top" 
                             alt="{{ $candidate->user->full_name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $candidate->user->full_name }}</h5>
                            <p class="card-text">
                                <strong>Role:</strong> {{ $candidate->candidate_role }}<br>
                                <strong>Election:</strong> {{ $candidate->election->name }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Platform</div>
                        <div class="card-body">
                            {{ $candidate->platform ?? 'No platform statement provided' }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection