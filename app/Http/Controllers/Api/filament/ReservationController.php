<?php

namespace App\Http\Controllers\Api\filament;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Filament\Resources\ReservationResource;

class ReservationController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('viewAny' , Reservation::class);
        
        if ($request->user()->role === 'admin') {
            $reservations = Reservation::paginate(10);
        } else {
            $reservations = Reservation::whereHas('estate', function ($query) use ($request) {
                //this where will be in estate , so we used whereHas
                $query->where('user_id', $request->user()->id);
            })->paginate(10);
        }
        return ReservationResource::collection($reservations);
    }
}
