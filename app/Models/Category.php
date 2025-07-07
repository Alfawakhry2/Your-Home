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
        'created_at',
        'updated_at',
    ];

    // this use only when use json
    protected $appends = [
        'image_url'
    ];

    ##Relationships
    public function estates()
    {
        return $this->hasMany(Estate::class);
    }

    //this is accessores will return the abs path
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('front/images/default.png');
        }
        return asset('storage/' . $this->image);
    }
}
