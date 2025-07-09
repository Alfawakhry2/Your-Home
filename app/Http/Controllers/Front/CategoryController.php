<?php

namespace App\Http\Controllers\Front;

use App\Models\Estate;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function categoryEstates($id)
    {
        if (Auth::check() && !auth()->user()->hasVerifiedEmail()) {
            return redirect('/email/verify');
        }
        $category = Category::with('estates')->findOrFail($id);
        $estates =  Estate::where('category_id' , $category->id)->paginate(6);
        return view('front.estatesCategory', compact( 'category', 'estates'));
    }
}
