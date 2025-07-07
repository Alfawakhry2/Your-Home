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
                                    <td>{{$reservation->start_date ?? '-'}}</td>
                                    <td>{{$reservation->end_date ?? '-'}}</td>
                                    <td>-</td>
                                    <td>
                                        <span class="badge @if($reservation->status==='confirmed') bg-success @else bg-danger   @endif">{{ $reservation->status }}</span>
                                    </td>
                                    <td>
                                        <span class="badge @if($reservation->payment_status==='paid') bg-success @else bg-danger   @endif">{{ $reservation->payment_status }}</span>

                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('payments.show' , $reservation->id) }}" class="btn btn-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Reservations pagination">
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
