<?php
namespace App\Repo\Cart;

use App\Models\Estate;
use Illuminate\Database\Eloquent\Collection;

interface CartRepo
{
    public function get(): Collection;
    public function add(Estate $estate);
    public function delete($id);
    public function empty();
}
