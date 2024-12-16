@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header bg-info text-white text-center">
                    <h3 class="my-2">Verify Your Email</h3>
                </div>
                <div class="card-body text-center">
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success">
                            A new verification link has been sent to your email address.
                        </div>
                    @endif

                    <p class="mb-4">
                        Thanks for signing up! Before getting started, could you verify your email address 
                        by clicking on the link we just emailed to you?
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                Resend Verification Email
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background-color: #f4f6f9;
    }
    .card {
        border-top: 4px solid #17a2b8;
    }
</style>
@endpush