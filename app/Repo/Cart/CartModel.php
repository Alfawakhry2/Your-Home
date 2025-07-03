<?php

namespace App\Repo\Cart;

use App\Models\Cart;
use App\Models\Estate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class CartModel
{
    public function get(): Collection
    {
        return Cart::with('estate')->where('user_id', Auth::id())->get();
    }


    public function add(Estate $estate)
    {
        //search if is already exist
        $cart = Cart::where('estate_id', $estate->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'estate_id' => $estate->id,
            ]);
            return $cart;
        }
        return $cart;
    }

    public function delete($id)
    {
        Cart::where('estate_id', $id)
            ->where('user_id', Auth::id())
            ->delete();
    }
    public function empty()
    {
        Cart::where('user_id', Auth::id())->delete();
    }
}
