@extends('front.layout.layout')

@section('title' , 'Booking Page')

@section('breadcrumb')
@parent
    <li class="breadcrumb-item"><a href="{{ route('reservation.index') }}">Reservations</a></li>

@endsection


@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- ✅ عنوان الصفحة --}}
            <h2 class="mb-4 text-center">تأكيد حجز العقار</h2>

            {{-- ✅ كارت العقار --}}
            <div class="card mb-4 shadow">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{ asset('storage/' . $estate->main_image) }}" class="img-fluid rounded-start" alt="صورة العقار">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $estate->title }}</h5>
                            <p class="card-text">{{ $estate->description }}</p>
                            <p class="card-text">
                                <strong>السعر:</strong> {{ number_format($estate->price) }} ج.م
                            </p>
                            <p class="card-text">
                                <strong>النوع:</strong>
                                @if ($estate->type === 'rent') إيجار @else بيع @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card shadow">
                <div class="card-body">
                    <form action="#" method="POST">
                        {{-- csrf لو هتستخدم باك إند --}}
                        {{-- @csrf --}}

                        @if ($estate->type === 'rent')
                            <div class="mb-3">
                                <label for="start_date" class="form-label">تاريخ البداية</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="end_date" class="form-label">تاريخ النهاية</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required>
                            </div>
                        @endif

                        {{-- زر الدفع --}}
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-credit-card"></i> ادفع واحجز الآن
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
