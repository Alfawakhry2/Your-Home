@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="card-title text-center text-dark mb-0">{{ __('Verify Your Email Address') }}</h5>
                </div>

                <div class="card-body px-5 py-4 text-center">
                    @if (session('resent'))
                        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <i class="bi bi-envelope-fill text-primary" style="font-size: 2.5rem;"></i>
                    </div>

                    <p class="text-secondary mb-4">
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                    </p>

                    <p class="text-secondary mb-4">
                        {{ __('If you did not receive the email') }},
                    </p>

                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-dark px-4">
                            {{ __('Click here to request another') }}
                        </button>
                    </form>

                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('login') }}" class="text-decoration-none text-secondary">
                            <i class="bi bi-arrow-left me-1"></i> {{ __('Back to Login') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
