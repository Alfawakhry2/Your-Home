<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id' , 'estate_id'
    ];

    public function estate(){
        return $this->belongsTo(Estate::class);
    }
}
