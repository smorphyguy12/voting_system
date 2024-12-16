@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Welcome, {{ auth()->user()->full_name }}</h1>
            
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <h2>Active Elections</h2>
                    @forelse($activeElections as $election)
                        <div class="card mb-3">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">{{ $election->name }}</h5>
                                    <p class="card-text">
                                        Voting Period: 
                                        {{ $election->start_date->format('d M Y') }} - 
                                        {{ $election->end_date->format('d M Y') }}
                                    </p>
                                </div>
                                
                                @if(!in_array($election->id, $votedElectionIds))
                                    <a href="{{ route('student.election.candidates', $election->id) }}" 
                                       class="btn btn-primary">
                                        View Candidates
                                    </a>
                                @else
                                    <span class="badge bg-success">Voted</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            No active elections at the moment.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection