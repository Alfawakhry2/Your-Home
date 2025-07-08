@extends('front.layout.layout')

@section('title', 'Home')


@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Main Page</a></li>
@endsection



@section('content')
@include('front.filter')
    <div class="section">
        <div class="container">
            <div class="row mb-5 align-items-center">
                <div class="">
                    <h2 class="font-weight-bold text-primary heading text-center">
                        Our Real Estate Category
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="property-slider-wrap">
                        <div class="property-slider">

                            @foreach ($categories as $category)
                                <div class="col-md-4 mb-4">
                                    <div class="property-item h-100 d-flex flex-column shadow-sm rounded overflow-hidden">
                                        <a href="property-single.html" class="img d-block w-100">
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="Image"
                                                class="img-fluid" style="height: 250px; width: 100%; object-fit: cover;" />
                                        </a>

                                        <div class="property-content d-flex flex-column flex-grow-1 p-3">
                                            <div class="price mb-2">
                                                <span class="fw-bold">{{ $category->title }}</span>
                                            </div>

                                            <span class="d-block mb-2 text-black-50">
                                                {{ Str::limit($category->description, 100) }}
                                            </span>

                                            <span class="city d-block mb-3 text-muted">Egypt</span>


                                            <!-- Push the button to bottom -->
                                            <div class="mt-auto">
                                                <a href="{{ route('category.estates', $category->id) }}"
                                                    class="btn btn-primary py-2 px-3 w-100">View Related Real Estate</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                            <!-- .item -->
                        </div>
                        {{-- <div id="property-nav" class="controls" tabindex="0" aria-label="Carousel Navigation">
                            <span class="prev" data-controls="prev" aria-controls="property" tabindex="-1">Prev</span>
                            <span class="next" data-controls="next" aria-controls="property" tabindex="-1">Next</span>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <section class="features-1"> --}}
    <div class="section section-properties">
        <div class="container">
            <div class="row mb-5 align-items-center">
                <div class="col-lg-6">
                    <h2 class="font-weight-bold text-primary heading">
                        Recent Real Estates
                    </h2>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <p>
                        <a href="{{ route('estates.index') }}" class="btn btn-primary text-white py-3 px-4">View More
                            Real Estate</a>
                    </p>
                </div>
            </div>

            <div class="row">
                @foreach ($estates as $estate)
                    <div class="col-md-4 mb-4">
                        <div class="property-card card h-100 shadow-sm border-0 overflow-hidden position-relative">
                            <!-- Status Badge -->
                            <div class="position-absolute top-0 end-0 m-2 z-2">
                                <span
                                    class="badge rounded-pill
                @if ($estate->status == 'rented') bg-success
                @elseif($estate->status == 'Sold') bg-danger
                @else bg-secondary @endif text-white">
                                    <i class="bi bi-tag-fill me-1"></i> {{ $estate->status }}
                                </span>
                            </div>

                            <!-- Type Badge -->
                            <div class="position-absolute top-0 start-0 m-2 z-2">
                                <span class="badge rounded-pill bg-primary text-white">
                                    <i class="bi bi-house-fill me-1"></i> {{ $estate->type }}
                                </span>
                            </div>

                            <!-- Image -->
                            <a href="{{ route('estate.show', $estate->id) }}" class="text-decoration-none">
                                <img src="{{ $estate->image_url }}" class="card-img-top object-fit-cover"
                                    style="height: 250px;" alt="{{ $estate->title }}"
                                    onerror="this.src='https://via.placeholder.com/400x250?text=Property+Image'">
                            </a>

                            <!-- Card Body -->
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-truncate">{{ $estate->title }}</h5>
                                <p class="card-text text-muted small">{{ Str::limit($estate->description, 100) }}</p>

                                <div class="mt-auto">
                                    <!-- Price -->
                                    <div class="d-flex align-items-center mb-2">
                                        {{-- <i class="bi bi-currency-EGP text-primary me-2"></i> --}}
                                        <span class="fw-bold">EGP {{ number_format($estate->price) }}</span>
                                    </div>

                                    <!-- Features -->
                                    <div class="d-flex justify-content-between text-muted small">
                                        <span><i class="bi bi-door-open me-1"></i> {{ $estate->bedrooms }} beds</span>
                                        <span><i class="bi bi-bucket me-1"></i> {{ $estate->bathrooms }} baths</span>
                                        <span><i class="bi bi-arrows-angle-expand me-1"></i> {{ $estate->area }} m²</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="card-footer bg-white border-0">
                                <a href="{{ route('estate.show', $estate->id) }}"
                                    class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-eye-fill me-2"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </div>
    {{-- </section> --}}


@endsection
