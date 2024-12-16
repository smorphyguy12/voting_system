@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin-sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Admin Dashboard</h1>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Voter Turnout</div>
                        <div class="card-body">
                            <canvas id="voterTurnoutChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Election Status</div>
                        <div class="card-body">
                            <canvas id="electionStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Students</h5>
                            <p class="card-text display-4">{{ $stats['students']['total'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Elections</h5>
                            <p class="card-text display-4">{{ $stats['elections']['total'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Votes</h5>
                            <p class="card-text display-4">{{ $stats['votes']['total'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background-color: #f4f6f9;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure we have data before creating charts
    const voterTurnoutCtx = document.getElementById('voterTurnoutChart');
    const electionStatusCtx = document.getElementById('electionStatusChart');

    if (voterTurnoutCtx) {
        new Chart(voterTurnoutCtx, {
            type: 'pie',
            data: {
                labels: ['Voted', 'Not Voted'],
                datasets: [{
                    data: [
                        {{ $stats['votes']['total'] ?? 0 }}, 
                        {{ max(0, ($stats['students']['total'] ?? 0) - ($stats['votes']['total'] ?? 0)) }}
                    ],
                    backgroundColor: ['#36A2EB', '#FF6384']
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Voter Turnout'
                }
            }
        });
    }

    if (electionStatusCtx) {
        new Chart(electionStatusCtx, {
            type: 'bar',
            data: {
                labels: ['Active', 'Upcoming', 'Closed'],
                datasets: [{
                    label: 'Elections',
                    data: [
                        {{ $stats['elections']['active'] ?? 0 }},
                        {{ $stats['elections']['upcoming'] ?? 0 }},
                        {{ $stats['elections']['closed'] ?? 0 }}
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                title: {
                    display: true,
                    text: 'Election Status'
                }
            }
        });
    }
});
</script>
@endpush