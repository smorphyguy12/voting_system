@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('partials.admin-sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">
                    Election Details: {{ $election->name }}
                    <span class="badge bg-{{ $election->status_color }}">
                        {{ ucfirst($election->status) }}
                    </span>
                </h1>
                
                <div class="btn-group">
                    <a href="{{ route('admin.elections.edit', $election->id) }}" 
                       class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button class="btn btn-danger delete-election" 
                            data-id="{{ $election->id }}">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Election Details</div>
                        <div class="card-body">
                            <p><strong>Start Date:</strong> {{ $election->start_date->format('d M Y H:i') }}</p>
                            <p><strong>End Date:</strong> {{ $election->end_date->format('d M Y H:i') }}</p>
                            <p><strong>Total Candidates:</strong> {{ $candidates->count() }}</p>
                            <p><strong>Total Votes:</strong> {{ $results->sum('votes') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Election Results</div>
                        <div class="card-body">
                            <canvas id="electionResultsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">Detailed Results</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Candidate</th>
                                <th>Votes</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                            <tr>
                                <td>{{ $result['candidate'] }}</td>
                                <td>{{ $result['votes'] }}</td>
                                <td>{{ $result['percentage'] }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('electionResultsChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Votes',
                data: {!! json_encode($chartData['votes']) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Delete Election Handler
    document.querySelector('.delete-election').addEventListener('click', function() {
        const electionId = this.dataset.id;
        if(confirm('Are you sure you want to delete this election?')) {
            fetch(`/admin/elections/${electionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => {
                if(response.ok) {
                    window.location.href = "{{ route('admin.elections.index') }}";
                }
            });
        }
    });
});
</script>
@endpush