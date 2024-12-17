<?php
$title = "Election Details";
$sideBar = "partials/admin-sidebar";
?>
@extends('layouts.app')

@section('content')
<!-- Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <h4 class="page-title">{{ $election->name ?? 'Election' }}
                <span class="badge bg-{{ $election->status_color ?? 'secondary' }}">
                    {{ ucfirst($election->status ?? 'unknown') }}
                </span>
            </h4>
            <a href="{{ route('admin.elections.index') }}" class="btn btn-soft-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Elections List
            </a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Election Details -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Election Details</h5>
            </div>
            <div class="card-body">
                <p><strong>Start Date:</strong> {{ optional($election->start_date)->format('d M Y H:i') ?? 'N/A' }}</p>
                <p><strong>End Date:</strong> {{ optional($election->end_date)->format('d M Y H:i') ?? 'N/A' }}</p>
                <p><strong>Total Candidates:</strong> {{ $candidates->count() ?? 0 }}</p>
                <p><strong>Total Votes:</strong> {{ $results->sum('votes') ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Election Results Chart -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Election Results</h5>
            </div>
            <div class="card-body">
                <canvas id="electionResultsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Results -->
<div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Detailed Results</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th>Candidate</th>
                    <th>Votes</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $result)
                <tr>
                    <td>{{ $result['candidate'] ?? 'N/A' }}</td>
                    <td>{{ $result['votes'] ?? 0 }}</td>
                    <td>{{ $result['percentage'] ?? 0 }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">No results available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<!-- Plugins CSS -->
<link rel="stylesheet" href="{{ asset('assets/libs/chart.js/chart.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/chart.js/chart.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('electionResultsChart').getContext('2d');
        const chartData = @json($chartData);

        if (chartData && chartData.labels && chartData.votes) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Votes',
                        data: chartData.votes,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>
@endpush