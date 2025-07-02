<?php

namespace App\Http\Controllers\Front;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Estate;

class CategoryController extends Controller
{
    public function categoryEstates($id){
        $category = Category::with('estates')->findOrFail($id);
        return view('front.estatesCategory' , compact('category'));
    }
}
