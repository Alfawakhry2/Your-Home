@extends('front.layout.layout')

@section('title', 'Estates')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ url()->current() }}">Estates</a></li>
@endsection


@section('content')
    @include('front.filter')
    <div class="section section-properties">
        <div class="container">
            <div class="row mb-5 align-items-center">
                <div class="col-lg-6">
                    @include('alerts')
                    <h2 class="font-weight-bold text-primary heading">
                        ALL Estates
                    </h2>
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

                                @if ($estate->status === 'rented')
                                    <div class="mt-1 small text-white bg-dark p-1 rounded">
                                        <i class="bi bi-clock me-1"></i>
                                        Available after:
                                        {{ $estate->reservations->last()->end_date ?? ''}}
                                    </div>
                                @endif
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
                                        {{-- <i class="bi bi-currency-dollar text-primary me-2"></i> --}}
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
        {{ $estates->withQueryString()->links('customPaginate') }}
    </div>

@endsection
