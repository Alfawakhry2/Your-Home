<?php

namespace App\Http\Controllers;

use App\Models\Estate;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if(Auth::check() && !$request->user()->hasVerifiedEmail()){
            return redirect('/email/verify');
        }
        $categories = Category::all();
        $estates = Estate::where('status' , 'available')->latest()->take(3)->get();
        return view('front.index' , compact('categories' , 'estates'));
    }
}
