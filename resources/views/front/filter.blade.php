{{-- Filtering  --}}

<div class="estate-filter-section py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="mb-4 text-center"><i class="bi bi-funnel me-2"></i>Filter Properties</h5>

                        <form action="{{ route('estates.index') }}" method="GET">
                            <div class="row g-3">
                                <!-- Location -->
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="location" name="location"
                                               placeholder="Enter location" value="{{ request('location') }}"
                                               list="locationSuggestions">
                                        <datalist id="locationSuggestions">
                                            @foreach($locations as $city)
                                                <option value="{{ $city }}">
                                            @endforeach
                                        </datalist>
                                        <label for="location"><i class="bi bi-geo-alt me-2"></i>Location</label>
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="category" name="category">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="category"><i class="bi bi-house me-2"></i>Category</label>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="status" name="status">
                                            <option value="">All Statuses</option>
                                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Rented</option>
                                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                                        </select>
                                        <label for="status"><i class="bi bi-tag me-2"></i>Status</label>
                                    </div>
                                </div>

                                <!-- Type -->
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="type" name="type">
                                            <option value="">All Types</option>
                                            <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>For Rent</option>
                                            <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>For Sale</option>
                                        </select>
                                        <label for="type"><i class="bi bi-tags me-2"></i>Type</label>
                                    </div>
                                </div>

                                <!-- Bedrooms -->
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="bedrooms" name="bedrooms">
                                            <option value="">Beds</option>
                                            @foreach(range(1, 5) as $i)
                                                <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                                            @endforeach
                                        </select>
                                        <label for="bedrooms"><i class="bi bi-door-open"></i></label>
                                    </div>
                                </div>

                                <!-- Bathrooms -->
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="bathrooms" name="bathrooms">
                                            <option value="">Baths</option>
                                            @foreach(range(1, 4) as $i)
                                                <option value="{{ $i }}" {{ request('bathrooms') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                                            @endforeach
                                        </select>
                                        <label for="bathrooms"><i class="bi bi-bucket"></i></label>
                                    </div>
                                </div>
                                <!-- Action Buttons -->
                                <div class="col-md-4 d-flex align-items-center ">
                                    <div class="d-flex gap-2 w-100 justify-content-center">
                                        <button type="submit" class="btn btn-primary flex-grow-1 py-2" title="Apply Filters">
                                            <i class="bi bi-funnel-fill"></i> Apply Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end filtering --}}
