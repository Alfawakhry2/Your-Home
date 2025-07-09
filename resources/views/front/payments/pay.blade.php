@extends('front.layout.layout')

@section('title' , 'payment')

@section('content')

<div class="p-4">
    <h2 class="text-center">Complete Payment</h2>
    <iframe src="{{ $iframeUrl }}" width="100%" height="600" frameborder="0"></iframe>
</div>

@endsection
