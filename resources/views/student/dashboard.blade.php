<?php
$title = "Dashboard";
$sideBar = "partials/menu";
?>
@extends('layouts.app')

@section('content')
<div class="container-fluid dashboard">
    <div class="row">
        <div class="col-12">
            <div class="dashboard-header d-flex justify-content-between align-items-center mb-4 mt-2">
                <div>
                    <h1 class="mb-2">Welcome, {{ auth()->user()->full_name }}</h1>
                    <p class="text-muted">Your personalized election dashboard</p>
                </div>
                <div class="dashboard-stats d-flex">
                    <div class="stat-card mx-2 text-center">
                        <h4>{{ count($activeElections) }}</h4>
                        <small>Active Elections</small>
                    </div>
                    <div class="stat-card mx-2 text-center">
                        <h4>{{ count($votedElectionIds) }}</h4>
                        <small>Elections Voted</small>
                    </div>
                </div>
            </div>

            @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">Active Elections</h3>
                            <span class="badge bg-light text-primary">{{ count($activeElections) }} Available</span>
                        </div>
                        <div class="card-body p-0">
                            @forelse($activeElections as $election)
                            <div class="election-item p-3 border-bottom d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-2">{{ $election->name }}</h5>
                                    <div class="text-muted small">
                                        <i class="bi bi-calendar me-2"></i>
                                        {{ $election->start_date->format('d M Y') }} -
                                        {{ $election->end_date->format('d M Y') }}
                                    </div>
                                </div>
                                <div class="election-actions">
                                    @if(!in_array($election->id, $votedElectionIds))
                                    <a href="{{ route('student.election.candidates', $election->id) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-person-ballot me-2"></i>View Candidates
                                    </a>
                                    @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-2"></i>Voted
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="text-center p-4">
                                <img src="{{ asset('assets/images/svg/features-1.svg') }}" alt="No Elections" class="mb-3" style="max-width: 200px;">
                                <p class="text-muted">No active elections at the moment.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">Upcoming Elections</h3>
                            <span class="badge bg-light text-primary">{{ count($upcomingElections) }} Available</span>
                        </div>
                        <div class="card-body p-0">
                            @forelse($upcomingElections as $election)
                            <div class="election-item p-3 border-bottom d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-2">{{ $election->name }}</h5>
                                    <div class="text-muted small">
                                        <i class="bi bi-calendar me-2"></i>
                                        {{ $election->start_date->format('d M Y') }} -
                                        {{ $election->end_date->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center p-4">
                                <img src="{{ asset('assets/images/svg/features-1.svg') }}" alt="No Elections" class="mb-3" style="max-width: 200px;">
                                <p class="text-muted">No upcoming elections at the moment.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dashboard-header {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }

    .stat-card {
        background-color: #e9ecef;
        padding: 10px 15px;
        border-radius: 6px;
    }

    .election-item:hover {
        background-color: #f8f9fa;
        transition: background-color 0.3s ease;
    }
</style>
@endpush