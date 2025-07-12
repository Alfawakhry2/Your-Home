<?php

namespace App\Http\Controllers\front;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index(){
        $reservations = Reservation::with('estate' , 'user')
        ->where('user_id' , Auth::id())
        ->paginate(10);
        return view('front.resevation', compact('reservations'));
    }


}
