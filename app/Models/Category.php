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

    // this use only when json 
    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    ##Relationships
    public function estates(){
        return $this->hasMany(Estate::class);
    }




}
