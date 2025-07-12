@extends('front.layout.layout')

@section('title', 'Estate Details')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Estats</a></li>
    <li class="breadcrumb-item"><a href="{{ url()->current() }}">{{ $estate->title }}</a></li>
@endsection


@section('content')
    <div class="real-estate-page">
        <div class="container py-5">
            <div class="row">
                <!-- Main Content Column -->
                <h2 class="text-center text-gray mb-5">{{ 'Estate Details' }}</h2>
                @include('alerts')
                <div class="col-lg-8">
                    <!-- Property Gallery -->
                    <div class="property-gallery mb-5">
                        <div class="gallery-main shadow-lg rounded-4 overflow-hidden">
                            <img src="{{ $estate->image_url }}" class="w-100" style="height: 500px; object-fit: cover;"
                                alt="Main Property Image" id="mainGalleryImage">
                        </div>
                        <div class="gallery-thumbnails mt-3">
                            <div class="row g-2">
                                <div class="col-3 col-md-2">
                                    <img src="{{ asset('storage/' . $estate->image) }}" class="img-thumbnail cursor-pointer"
                                        style="height: 80px; object-fit: cover;"
                                        onclick="document.getElementById('mainGalleryImage').src = this.src">
                                </div>
                                @foreach ($estate->images as $index => $image)
                                    <div class="col-3 col-md-2">
                                        <img src="{{ asset('storage/' . $image->image) }}"
                                            class="img-thumbnail cursor-pointer" style="height: 80px; object-fit: cover;"
                                            onclick="document.getElementById('mainGalleryImage').src = this.src"
                                            alt="Thumbnail {{ $index + 1 }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Property Details -->
                    <div class="property-details card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h1 class="text-primary fw-bold mb-0">{{ $estate->title }}</h1>
                                <span class="badge bg-success fs-6">{{ $estate->status }}</span>
                            </div>
                            @if ($estate->status == 'rented')
                                <div class="mt-1 text-white bg-dark p-1 rounded text-center">
                                    <i class="bi bi-clock me-1"></i>
                                    Available after:
                                    {{ $estate->reservations->last()->end_date ?? 'UnKnown Date' }}
                                </div>
                            @endif
                            <div class="d-flex align-items-center mb-4">
                                <i class="bi bi-geo-alt-fill text-danger fs-5 me-2"></i>
                                <span class="text-muted fs-5">{{ $estate->location }}</span>
                            </div>

                            <div class="property-highlights mb-4">
                                <div class="row g-3">
                                    <div class="col-6 col-md-3">
                                        <div class="highlight-box text-center p-2 rounded-3 bg-light">
                                            <i class="bi bi-house-door text-primary fs-4"></i>
                                            <p class="mb-0 fw-bold">{{ $estate->type }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="highlight-box text-center p-2 rounded-3 bg-light">
                                            <i class="bi bi-arrows-angle-expand text-primary fs-4"></i>
                                            <p class="mb-0 fw-bold">{{ $estate->area }} m²</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="highlight-box text-center p-2 rounded-3 bg-light">
                                            <i class="bi bi-door-open text-primary fs-4"></i>
                                            <p class="mb-0 fw-bold">{{ $estate->bedrooms }} bedrooms</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="highlight-box text-center p-2 rounded-3 bg-light">
                                            <i class="bi bi-bucket text-primary fs-4"></i>
                                            <p class="mb-0 fw-bold">{{ $estate->bathrooms }} bathrooms</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="property-description mb-5">
                                <h4 class="fw-bold mb-3">Description</h4>
                                <p class="text-secondary lh-lg">{{ $estate->description }}</p>
                            </div>
                            @if ($estate->status === 'rented')
                                <form action="{{ route('estate.notifyMe', $estate->id) }}" method="POST">
                                    @csrf
                                    <div class="d-flex justify-content-center">
                                        <button type="submit"
                                            class="btn btn-secondary w-50 py-2 fw-bold rounded-pill mb-5">
                                            <i class="bi bi-bill me-2"></i> Send Notification When Available
                                        </button>
                                    </div>
                                </form>
                            @elseif($estate->status == 'sold')
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('estates.index') }}" class="btn btn-dark w-50 py-2 fw-bold rounded-pill mb-5">
                                        <i class="bi  me-2"></i> Not Available , Explore More
                                    </a>
                                </div>
                            @else
                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="estate_id" value="{{ $estate->id }}">
                                    <div class="d-flex justify-content-center">
                                        <button type="submit"
                                            class="btn btn-secondary w-50 py-2 fw-bold rounded-pill mb-5">
                                            <i class="bi bi-star me-2"></i> Add To Interested List
                                        </button>
                                    </div>
                                </form>
                            @endif

                            {{-- <div class="property-features mb-5">
                            <h4 class="fw-bold mb-3"></h4>
                            <div class="row">
                                @foreach ($estate->features as $feature)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span>{{ $feature->name }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div> --}}

                            <!-- Map Section -->
                            <div class="property-map mb-5">
                                <h4 class="fw-bold mb-3">Location On Map</h4>
                                <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm">
                                    <iframe
                                        src="https://maps.google.com/maps?q={{ urlencode($estate->location) }}&output=embed"
                                        frameborder="0" style="border:0" allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="col-lg-4">
                    <!-- Owner Card -->
                    <div class="owner-card card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-body p-4 align-items-center">
                            <div class="text-center mb-4 align-items-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <img src="{{ $estate->user->image_url }}" class="rounded-circle border"
                                    width="100" height="100" alt="Owner Photo">
                                </div>
                                <h5 class="mt-3 mb-1">{{ $estate->user->name }}</h5>
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    {{-- <i class="bi bi-star-fill text-warning me-1"></i> --}}
                                    {{-- <span class="fw-bold me-2">-</span> --}}
                                    <small class="text-muted"></small>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <span class="badge bg-light text-success me-2">Verfied</span>
                                    {{-- <span class="badge bg-light text-dark">متصل الآن</span> --}}
                                </div>
                            </div>

                            <div class="owner-info mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                        <i class="bi bi-telephone-fill"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Phone Number</small>
                                        <span class="fw-bold">{{ $estate->user->phone }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                        <i class="bi bi-envelope-fill"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Email</small>
                                        <span class="fw-bold">{{ $estate->user->email }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Availabel Time</small>
                                        <span class="fw-bold">24 hours</span>
                                    </div>
                                </div>
                            </div>

                            <div class="owner-actions">
                                <button class="btn btn-primary w-100 py-2 mb-3 fw-bold rounded-pill">
                                    <i class="bi bi-chat-left-text me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Price Card -->
                    <div class="price-card card shadow-sm border-0 mt-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Estate Price <span
                                    class="badge @if ($estate->type == 'rent') bg-success @else bg-danger @endif fs-6">
                                    For {{ Str::ucfirst($estate->type) }}</span></h5>


                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-tags text-primary fs-4 me-3"></i>
                                <div>
                                    <small class="text-success d-block">Price</small>
                                    <span class="fw-bold fs-4">EGP {{ number_format($estate->price) }}</span>
                                </div>
                            </div>
                            @if ($estate->status === 'rented')
                                <form action="{{ route('estate.notifyMe', $estate->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary w-100 py-2 fw-bold rounded-pill">
                                        <i class="bi bi-bill-card me-2"></i>Send Notification When Available

                                    </button>
                                </form>
                            @elseif ($estate->status === 'sold')
                                <p class="text-danger">This Estate Sold!</p>
                                <a class="btn btn-dark" href="{{ route('estates.index') }}">Explore More</a>
                            @else
                                <a href="{{ route('checkout.estate', $estate->id) }}"
                                    class="btn btn-danger w-100 py-2 fw-bold rounded-pill">
                                    <i class="bi bi-credit-card me-2"></i>Reserve Estate Now

                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .real-estate-page {
            background-color: #f8f9fa;
        }

        .gallery-main {
            transition: all 0.3s ease;
        }

        .img-thumbnail {
            transition: all 0.2s ease;
        }

        .img-thumbnail:hover {
            transform: scale(1.05);
            border-color: var(--bs-primary) !important;
        }

        .highlight-box {
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .highlight-box:hover {
            background-color: var(--bs-primary) !important;
            color: white;
        }

        .highlight-box:hover i {
            color: white !important;
        }

        .owner-card {
            border-top: 4px solid var(--bs-primary);
        }

        .icon-box {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .price-card {
            border-top: 4px solid var(--bs-danger);
        }

        .sticky-top {
            z-index: 1;
        }
    </style>
@endpush
