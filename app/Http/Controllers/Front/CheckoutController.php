<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Estate;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout($id){
        $estate = Estate::findOrFail($id);
        if($estate->status !== 'available'){
            return redirect()->back()->with('error' , 'This Estate Not Available');
        }
        return view('front.checkout' , compact('estate'));
    }
}
