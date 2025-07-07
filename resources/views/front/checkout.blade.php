@extends('front.layout.layout')

@section('title', 'Checkout Page')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('reservation.index') }}">Checkout</a></li>

@endsection
@section('content')

    <div class="real-estate-reservation">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Header Section -->
                    <div class="reservation-header text-center mb-5">
                        <h2 class="fw-bold text-primary mb-3">Confirm Estate Reservation</h2>
                        <div class="progress-steps">
                            <div class="d-flex justify-content-between position-relative">
                                <div class="step completed">
                                    <div class="step-number">1</div>
                                    <div class="step-label">Choose Estaet</div>
                                </div>
                                <div class="step active">
                                    <div class="step-number">2</div>
                                    <div class="step-label">Reservation Details</div>
                                </div>
                                <div class="step">
                                    <div class="step-number">3</div>
                                    <div class="step-label">Pay</div>
                                </div>
                                <div class="progress-bar position-absolute w-100"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Property Card -->
                    <div class="card property-card mb-4 border-0 shadow-lg overflow-hidden">
                        <div class="row g-0">
                            <div class="col-md-5 position-relative">
                                <img src="{{ asset('storage/' . $estate->image) }}" class="img-fluid h-100 w-100"
                                    style="object-fit: cover; min-height: 250px;" alt="صورة العقار">
                                <span
                                    class="badge bg-{{ $estate->type === 'rent' ? 'info' : 'success' }} position-absolute top-0 end-0 m-2">
                                    {{ $estate->type === 'rent' ? 'For Rent' : 'For Sale' }}
                                </span>
                            </div>
                            <div class="col-md-7">
                                <div class="card-body p-4">
                                    <h3 class="card-title fw-bold mb-3">{{ $estate->title }}</h3>

                                    <div class="property-meta mb-4">
                                        <div class="d-flex flex-wrap gap-3">
                                            <span class="d-flex align-items-center">
                                                <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                                {{ $estate->location }}
                                            </span>
                                            <span class="d-flex align-items-center">
                                                <i class="bi bi-arrows-angle-expand text-primary me-2"></i>
                                                {{ $estate->area }} م²
                                            </span>
                                            <span class="d-flex align-items-center">
                                                <i class="bi bi-door-open text-primary me-2"></i>
                                                {{ $estate->bedrooms }} bedrooms
                                            </span>
                                            <span class="d-flex align-items-center">
                                                <i class="bi bi-bucket text-primary me-2"></i>
                                                {{ $estate->bathrooms }} bathrooms
                                            </span>
                                        </div>
                                    </div>

                                    <p class="card-text text-secondary mb-4">{{ $estate->description }}</p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="price-display">
                                            <span class="text-muted d-block">Price</span>
                                            <h4 class="text-primary fw-bold mb-0">
                                                {{ number_format($estate->price) }} EGP
                                                @if ($estate->type === 'rent')
                                                    <small class="text-muted fs-6">/ Monthly</small>
                                                @endif
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <div class="card payment-form border-0 shadow-lg">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4 text-center">
                                <i class="bi bi-credit-card-2-back-fill text-primary me-2"></i>
                                Payment Information
                            </h4>

                            <form action="{{ route('checkout.pay', $estate->id) }}" method="POST">
                                <input type="hidden" name="estate_id" value="{{ $estate->id }}">
                                @csrf
                                @if ($estate->type === 'rent')
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label for="start_date" class="form-label fw-bold">
                                                <i class="bi bi-calendar-check me-2"></i>Start Date
                                            </label>

                                            <input type="date" name="start_date" id="start_date"
                                                class="form-control form-control-lg">
                                            @error('start_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="end_date" class="form-label fw-bold">
                                                <i class="bi bi-calendar-x me-2"></i>Duration(Month)
                                            </label>
                                            <input type="number" name="duration" id="duration" min="1" max="12"
                                                class="form-control form-control-lg">
                                            @error('duration')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-credit-card me-2"></i>Payment Method
                                    </label>
                                    <div class="payment-methods">
                                        <div class="row g-3">
                                            {{-- <div class="col-md-4">
                                                <input type="radio" name="payment_method" id="vodafone-cash"
                                                    class="d-none">
                                                <label for="vodafone-cash" class="payment-method-card">
                                                    <i class="bi bi-phone"></i>
                                                    <span>Vodafone Cach</span>
                                                </label>
                                            </div> --}}
                                            <div class="col-md-4">
                                                <input type="radio" name="payment_method" id="bank-transfer"
                                                    class="d-none">
                                                <label for="bank-transfer" class="payment-method-card">
                                                    <i class="bi bi-bank"></i>
                                                    <span>Bank Transfer</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                                        <i class="bi bi-lock-fill me-2"></i>Reservation
                                        </a>
                                </div>

                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-shield-lock me-2"></i>
                                        Your data is fully protected and encrypted.
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        .real-estate-reservation {
            background-color: #f8f9fa;
        }

        .progress-steps {
            max-width: 600px;
            margin: 0 auto;
        }

        .progress-steps .step {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .progress-steps .step-number {
            width: 40px;
            height: 40px;
            background-color: #e9ecef;
            color: #6c757d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: bold;
        }

        .progress-steps .step-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .progress-steps .step.active .step-number {
            background-color: #0d6efd;
            color: white;
        }

        .progress-steps .step.completed .step-number {
            background-color: #198754;
            color: white;
        }

        .progress-steps .progress-bar {
            height: 4px;
            background-color: #e9ecef;
            top: 20px;
            z-index: 1;
        }

        .property-card {
            border-radius: 12px;
        }

        .payment-form {
            border-radius: 12px;
        }

        .payment-method-card {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .payment-method-card i {
            font-size: 1.5rem;
            margin-bottom: 8px;
            color: #6c757d;
        }

        .payment-method-card span {
            font-weight: 500;
        }

        input[type="radio"]:checked+.payment-method-card {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.05);
        }

        input[type="radio"]:checked+.payment-method-card i {
            color: #0d6efd;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush
