<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
    ];


    ##Relationships
    public function Estates(){
        return $this->hasMany(Estate::class);
    }


}
