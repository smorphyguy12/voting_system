<?php
$title = "Election Candidates";
$sideBar = "partials/menu";
?>
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="election-header mb-4 p-4 bg-primary mt-2 text-white rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-5 mb-2">{{ $election->name }}</h1>
                        <p class="lead mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ $election->start_date->format('d M Y') }} - {{ $election->end_date->format('d M Y') }}
                        </p>
                    </div>
                    @if($hasVoted)
                    <div class="badge bg-success p-3">
                        <i class="fas fa-check-circle me-2"></i>
                        You have voted
                    </div>
                    @endif
                </div>
            </div>

            @if(!$hasVoted)
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                Select your preferred candidate carefully. You can only vote once.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row g-4">
                @foreach($candidates as $candidate)
                <div class="col-md-4">
                    <div class="card candidate-card h-100 shadow-sm {{ $hasVoted ? 'opacity-75' : '' }}">
                        <div class="candidate-avatar position-relative">
                            <img src="{{ $candidate->profile_picture ? asset('storage/student_avatars/' . $candidate->profile_picture) : asset('storage/student_avatars/default.png') }}"
                                class="card-img-top candidate-image"
                                alt="{{ $candidate->user->full_name }}">
                            <div class="candidate-overlay">
                                <span class="badge bg-primary">{{ $candidate->candidate_role }}</span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h4 class="card-title mb-3">{{ $candidate->user->full_name }}</h4>
                            
                            <div class="candidate-details mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">
                                        <i class="fas fa-graduation-cap me-2"></i>
                                        {{ $candidate->user->course }}
                                    </span>
                                </div>
                            </div>

                            <div class="candidate-platform">
                                <h6 class="text-muted mb-2">Platform Statement</h6>
                                <p class="card-text text-muted">
                                    {{ $candidate->platform ?? 'No platform statement provided' }}
                                </p>
                            </div>
                        </div>

                        <div class="card-footer">
                            @if(!$hasVoted)
                            <button class="btn btn-primary vote-btn w-100 py-2 vote-{{ $candidate->id }}"
                                data-candidate-id="{{ $candidate->id }}"
                                data-election-id="{{ $election->id }}">
                                <i class="fas fa-vote-yea me-2"></i>
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

@push('styles')
<style>
    .candidate-avatar {
        position: relative;
        overflow: hidden;
    }
    .candidate-image {
        height: 300px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .candidate-card:hover .candidate-image {
        transform: scale(1.05);
    }
    .candidate-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>
@endpush
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