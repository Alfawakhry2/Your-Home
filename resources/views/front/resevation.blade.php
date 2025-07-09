@extends('front.layout.layout')

@section('title', 'Reservations')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('reservation.index') }}">Reservations</a></li>
@endsection


@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="mb-4">Your Reservations</h1>
                <!-- Success Message Example -->
                @include('alerts')

                <!-- Empty State -->
                <!-- <div class="alert alert-info">
                        You don't have any reservations yet.
                    </div> -->

                <!-- Reservations Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Estate</th>
                                <th>Reservation Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Payment Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- Reservation-->
                            @forelse ($reservations as $reservation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="#">{{ $reservation->estate->title }}</a>
                                    </td>
                                    <td>{{ $reservation->date }}</td>
                                    <td>{{ $reservation->start_date ?? '-' }}</td>
                                    <td>{{ $reservation->end_date ?? '-' }}</td>
                                    <td>-</td>
                                    <td>
                                        <span
                                            class="badge @if ($reservation->status === 'confirmed') bg-success @else bg-danger @endif">{{ $reservation->status }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge @if ($reservation->payment_status === 'paid') bg-success @else bg-danger @endif">{{ $reservation->payment_status }}</span>

                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('payments.show', $reservation->id) }}"
                                                class="btn btn-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <h2 class="text-center p-5">No Reservations yet ! </h2>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $reservations->withQueryString()->links('customPaginate') }}
            </div>
        </div>
    </div>
@endsection
