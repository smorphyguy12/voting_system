@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">
                Candidates for {{ $election->name }}
                <small class="text-muted">{{ $election->start_date->format('d M Y') }} - {{ $election->end_date->format('d M Y') }}</small>
            </h1>

            @if($hasVoted)
                <div class="alert alert-info">
                    You have already voted in this election.
                </div>
            @endif

            <div class="row">
                @foreach($candidates as $candidate)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ $candidate->profile_picture ? asset('storage/' . $candidate->profile_picture) : asset('default-avatar.png') }}" 
                             class="card-img-top" 
                             alt="{{ $candidate->user->full_name }}"
                             style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $candidate->user->full_name }}</h5>
                            <p class="card-text">
                                <strong>Role:</strong> {{ $candidate->candidate_role }}<br>
                                <strong>Course:</strong> {{ $candidate->user->course }}
                            </p>
                            <p class="card-text">{{ $candidate->platform ?? 'No platform statement provided' }}</p>
                        </div>
                        <div class="card-footer">
                            @if(!$hasVoted)
                                <button class="btn btn-primary vote-btn w-100" 
                                        data-candidate-id="{{ $candidate->id }}"
                                        data-election-id="{{ $election->id }}">
                                    Vote for {{ $candidate->user->full_name }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const voteButtons = document.querySelectorAll('.vote-btn');
    
    voteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const candidateId = this.dataset.candidateId;
            const electionId = this.dataset.electionId;

            fetch('{{ route('student.vote') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    candidate_id: candidateId,
                    election_id: electionId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Disable all vote buttons and show message
                    voteButtons.forEach(btn => {
                        btn.disabled = true;
                        btn.textContent = 'Voted';
                    });
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while voting');
            });
        });
    });
});
</script>
@endpush