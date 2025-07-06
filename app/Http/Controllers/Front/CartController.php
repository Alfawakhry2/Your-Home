<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Estate;
use Illuminate\Http\Request;
use App\Repo\Cart\CartModel;

class CartController extends Controller
{
    public function index()
    {
        $cart = new CartModel();
        return view('front.cart', [
            'cart' => $cart->get(),
        ]);
    }

    public function store(Estate $estate, Request $request)
    {
        $cart = new CartModel();
        $request->validate([
            'estate_id' => 'required|exists:estates,id',
        ]);

        $estate = Estate::findOrFail($request->post('estate_id'));
        if ($estate->status !== 'available') {
            return redirect()->back()->with('error', 'This Estate Not Available');
        }
        $cart->add($estate);
        return redirect()->route('cart.index')->with('success', 'Estate Added To Your Interest');
    }

    public function delete($id)
    {
        $cart = new CartModel();
        $cart->delete($id);
        return redirect()->route('cart.index')->with('success', 'Estate Removed From Your Interest');
    }


    public function empty()
    {
        $cart = new CartModel();
        $cart->empty();
        return redirect()->route('cart.index')->with('success', 'All Removed From Interest List !');
    }
}
