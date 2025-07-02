<?php

namespace App\Http\Controllers\Front;

use App\Models\Estate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EstateController extends Controller
{
    public function index(){
        $estates = Estate::paginate(6);
        return view('front.estates' , compact('estates'));
    }


    public function show($id){
        $estate = Estate::findOrFail($id);
        return view('front.estateDetails', compact('estate'));
    }
}
