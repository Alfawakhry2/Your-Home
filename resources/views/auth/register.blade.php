@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="card-title text-center text-dark mb-0">{{ __('Create Your Account') }}</h5>
                </div>

                <div class="card-body px-5 py-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-4">
                            <label for="name" class="col-md-4 col-form-label text-md-end text-secondary">
                                {{ __('Full Name') }}
                            </label>

                            <div class="col-md-6">
                                <input id="name" type="text"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}"
                                    required autocomplete="name" autofocus
                                    placeholder="John Doe">

                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="email" class="col-md-4 col-form-label text-md-end text-secondary">
                                {{ __('Email Address') }}
                            </label>

                            <div class="col-md-6">
                                <input id="email" type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}"
                                    required autocomplete="email"
                                    placeholder="your@email.com">

                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password" class="col-md-4 col-form-label text-md-end text-secondary">
                                {{ __('Password') }}
                            </label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    name="password" required autocomplete="new-password"
                                    placeholder="">

                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end text-secondary">
                                {{ __('Confirm Password') }}
                            </label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password"
                                    class="form-control form-control-lg"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-dark btn-lg px-4">
                                    {{ __('Register') }}
                                </button>

                                <div class="mt-3">
                                    <p class="text-secondary mb-0">Already have an account?
                                        <a href="{{ route('login') }}" class="text-decoration-none text-dark fw-semibold">Sign in</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
