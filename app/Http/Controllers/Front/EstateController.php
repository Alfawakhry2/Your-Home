<?php

namespace App\Http\Controllers\Front;

use App\Models\Estate;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EstateController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check() && !auth()->user()->hasVerifiedEmail()) {
            return redirect('/email/verify');
        }
        $locations = Estate::pluck('location');
        $categories = Category::all();

        //filters
        $estates = Estate::with('category')
        ->location($request->query('location'))
        ->category($request->query('category'))
        ->status($request->query('status'))
        ->type($request->query('type'))
        ->bedrooms($request->query('bedrooms'))
        ->bathrooms($request->query('bathrooms'))
        ->paginate(6);
        return view('front.estates', compact('estates' , 'locations' , 'categories'));
    }


    public function show($id)
    {
        if (Auth::check() && !auth()->user()->hasVerifiedEmail()) {
            return redirect('/email/verify');
        }
        $estate = Estate::findOrFail($id);
        return view('front.estateDetails', compact('estate'));
    }
}
