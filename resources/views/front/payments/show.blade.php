@extends('front.layout.layout')

@section('title', 'Payment Details')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('payments.show', $reservation->id) }}">Payment Details</a></li>

@endsection

@section('content')
    <div class="container p-5">
        <h2>Payment # {{ $reservation->id }}</h2>

        @if ($details)
            <ul class="list-group mb-4">
                <li class="list-group-item">Price : {{ number_format(($details['amount_cents'] ?? 0) / 100) }} EGP</li>
                <li class="list-group-item">Currency: {{ $details['currency'] ?? '-' }}</li>
                <li class="list-group-item">Card: {{ $details['card_type'] ?? '' }} ****{{ $details['card_ending'] ?? '' }}
                </li>
                <li class="list-group-item"> Transaction Number : {{ $details['transaction_id'] ?? ($details['id'] ?? '-') }}
                </li>
                <li class="list-group-item">Status:
                    {{ ($details['status'] ?? $details['success']) == 'true' ? 'Paid ' : 'Failed' }}</li>
                <li class="list-group-item"> Response Code : {{ $details['txn_response_code'] ?? '-' }}</li>
                <li class="list-group-item">Payment Date : {{ $reservation->updated_at->format('Y-m-d H:i') }}</li>
            </ul>
        @else
            <p class="text-danger">There Is No Payment Details.</p>
        @endif

        <div class='d-flex align-items-center justify-content-between'>
            <a href="{{ route('reservation.index') }}" class="btn btn-secondary">Back</a>
            @if ($details['success'] == 'false')
                <form action="{{ route('payments.repay' , $reservation->id) }}" method="get">

                    <button class="btn btn-danger">Try To Pay Again</button>
                </form>
        </div>
        @endif
    </div>
@endsection
