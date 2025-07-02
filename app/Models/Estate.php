<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estate extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'price',
        'type',
        'bedrooms',
        'bathrooms',
        'area',
        'location',
        'status',
        'title',
        'slug',
        'description',
        'image',
    ];



    ## Relationship
    //here we meant the seller
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function images(){
        return $this->hasMany(EstateImage::class);
    }

    //make accessors for image
    public function getImageUrlAttribute(){
        if(!$this->image){
            return asset('front/images/default.png');
        }
        return asset('storage/'.$this->image);
    }
}
