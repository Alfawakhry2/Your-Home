@extends('front.layout.layout')

@section('title', 'Your Home')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Interest List</a></li>
@endsection


@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Cart Content -->
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                @include('alerts')
                <div class="d-flex justify-content-between card-header bg-white border-bottom">
                    <h4 class="mb-0"><i class="bi bi-star me-2"></i>Your Selected Estates</h4>
                    <form action="{{ route('cart.empty') }}" method="POST">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger">Delete All From List</button>
                    </form>
                </div>
                <div class="card-body">
                    <!-- Property Item 1 -->
                    @forelse($cart as $c )
                    <div class="row g-3 mb-4 border-bottom pb-4">
                        <div class="col-md-4">
                            <img src="{{ $c->estate->image_url }}"
                                 class="img-fluid rounded-3"
                                 alt="Property Image">
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex justify-content-between">
                                <h5>{{ $c->estate->title }}</h5>
                                {{-- <form action="" method="POST"> --}}
                                    {{-- @csrf --}}
                                    <a href="{{ route('checkout.estate' , $c->estate->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-book"></i> Book Now !
                                    </a>
                                {{-- </form> --}}
                                <form action="{{ route('cart.delete' , $c->estate->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <p class="text-muted">{{ $c->estate->location }}</p>

                            <div class="property-details mb-3">
                                <span class="badge bg-primary me-2">-</span>
                                <span class="badge bg-success me-2">For {{ $c->estate->type }}</span>
                                <span class="me-2"><i class="bi bi-door-open"></i> {{ $c->estate->bedrooms }} Bed</span>
                                <span class="me-2"><i class="bi bi-bucket"></i> {{ $c->estate->bathrooms }} Bath</span>
                                <span><i class="bi bi-arrows-angle-expand"></i>  {{ $c->estate->area }}m²</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="price">
                                    <h5 class="text-primary mb-0">EGP {{ $c->estate->price }}</h5>
                                    {{-- <small class="text-muted">Total: EGP 5,250,000</small> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <h2 class="text-center p-5">No Interested Estate Found</h2>
                    <div class="text-center">
                        <a class="btn btn-outline-secondary" href="{{ route('estates.index') }}">Show Real Estate Now </a>
                    </div>
                    @endforelse

                </div>
            </div>

            <!-- Booking Details -->
            {{-- <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Booking Details</h4>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Check-in Date</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check-out Date</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Special Requests</label>
                                <textarea class="form-control" rows="3" placeholder="Any special requirements..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div> --}}
        </div>

        <!-- Order Summary -->
        {{-- <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0"><i class="bi bi-receipt me-2"></i>Order Summary</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Luxury Villa (Purchase)</span>
                        <span>EGP 5,250,000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Apartment (2 months rent)</span>
                        <span>EGP 24,000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Service Fee</span>
                        <span>EGP 2,500</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5>Total</h5>
                        <h5 class="text-primary">EGP 5,276,500</h5>
                    </div>

                    <!-- Payment Options -->
                    <div class="mb-4">
                        <h6 class="mb-3"><i class="bi bi-credit-card me-2"></i>Payment Method</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment" id="creditCard" checked>
                            <label class="form-check-label" for="creditCard">
                                <i class="bi bi-credit-card-2-front"></i> Credit/Debit Card
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment" id="vodafoneCash">
                            <label class="form-check-label" for="vodafoneCash">
                                <i class="bi bi-phone"></i> Vodafone Cash
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="bankTransfer">
                            <label class="form-check-label" for="bankTransfer">
                                <i class="bi bi-bank"></i> Bank Transfer
                            </label>
                        </div>
                    </div>

                    <!-- Booking Button -->
                    <button class="btn btn-primary w-100 py-3 fw-bold">
                        <i class="bi bi-lock-fill me-2"></i> Complete Booking & Pay Now
                    </button>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="bi bi-shield-lock"></i> Your payment is secure and encrypted
                        </small>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
    .card {
        border-radius: 10px;
        border: none;
    }
    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,.1);
    }
    .sticky-top {
        z-index: 1;
    }
    .form-check-label {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary {
        background-color: #0d6efd;
        border: none;
        padding: 10px 20px;
        transition: all 0.3s;
    }
    .btn-primary:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
    }
</style>
@endpush
