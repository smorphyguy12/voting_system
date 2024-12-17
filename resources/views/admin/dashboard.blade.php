<?php
$title = "Dashboard";
$sideBar = "partials/admin-sidebar";
?>
@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row justify-content-center">
    <div class="col-md-6 col-xl-4">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-primary border-primary border shadow">
                            <i class="fe-users font-22 avatar-title text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $stats['students']['total'] ?? 0 }}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Students</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-4">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-success border-success border shadow">
                            <i class="fe-bookmark font-22 avatar-title text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $stats['elections']['total'] ?? 0 }}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Elections</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-4">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-info border-info border shadow">
                            <i class="fe-bar-chart-line font-22 avatar-title text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $stats['votes']['total'] ?? 0 }}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Votes</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
</div>
<!-- end row-->

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
@endsection

@push('styles')
<!-- Plugins css -->
<link rel="stylesheet" href="{{ asset('assets/libs/chart.js/chart.min.css') }}">
@endpush


@push('scripts')
<script src="{{ asset('assets/libs/chart.js/chart.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const voterTurnoutCtx = document.getElementById('voterTurnoutChart');
        const electionStatusCtx = document.getElementById('electionStatusChart');

        // Safely convert PHP data to JavaScript
        const stats = @json($stats);

        if (voterTurnoutCtx) {
            new Chart(voterTurnoutCtx, {
                type: 'pie',
                data: {
                    labels: ['Voted', 'Not Voted'],
                    datasets: [{
                        data: [
                            stats.votes?.total || 0,
                            Math.max(0, (stats.students?.total || 0) - (stats.votes?.total || 0))
                        ],
                        backgroundColor: ['#36A2EB', '#FF6384']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Voter Turnout'
                        }
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
                            stats.elections?.active || 0,
                            stats.elections?.upcoming || 0,
                            stats.elections?.closed || 0
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
                    plugins: {
                        title: {
                            display: true,
                            text: 'Election Status'
                        }
                    }
                }
            });
        }
    });
</script>

@endpush