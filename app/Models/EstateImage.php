<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstateImage extends Model
{
    protected $fillable = [
        'estate_id',
        'image',
        'is_main',
        'order',
    ];

    protected $hidden = [
        'image'
    ];

    protected $appends = [
        'image_url'
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('front/images/default.png');
        }
        return asset('storage/' . $this->image);
    }
    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }
}
