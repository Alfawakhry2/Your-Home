<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstateImage extends Model
{
    protected $fillable =[
        'estate_id',
        'image',
        'is_main' ,
        'order',

    ];

    public function estate(){
        return $this->belongsTo(Estate::class);
    }
}
