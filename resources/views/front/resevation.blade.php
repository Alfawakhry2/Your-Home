@extends('front.layout.layout')

@section('title' , 'Booking Page')

@section('breadcrumb')
@parent
    <li class="breadcrumb-item"><a href="{{ route('reservation.index') }}">Reservations</a></li>

@endsection

{{-- contain the list of estate that already reserved --}}
@section('content')


@endsection
