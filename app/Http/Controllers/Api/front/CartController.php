<?php

namespace App\Http\Controllers\Api\front;

use App\Models\Cart;
use App\Models\Estate;
use App\Repo\Cart\CartModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = new CartModel();
        $data = $cart->get();
        if (count($data) < 1) {
            return response()->json([
                'message' => 'Your Interest List Empty',
            ], 404);
        }
        return response()->json([
            //cart model that i created contain the function and i used the interface
            'data' => $cart->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'estate_id' => 'required|exists:estates,id',
        ]);

        $estate = Estate::findOrFail($request->post('estate_id'));

        if ($estate->status !== 'available') {
            return response()->json([
                'message' => 'This Estate is not available',
            ], 400);
        }

        $cart = new CartModel();
        $cart->add($estate);

        return response()->json([
            'message' => 'Estate added to interest list.',
        ]);
    }

    public function delete($id)
    {
        $cart = new CartModel();
        $cart->delete($id);

        return response()->json([
            'message' => 'Estate removed from Your interests list.',
        ]);
    }

    public function empty()
    {
        $cart = new CartModel();
        $cart->empty();

        return response()->json([
            'message' => 'All estates removed from Your interests list.',
        ]);
    }
}
