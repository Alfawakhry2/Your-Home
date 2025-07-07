<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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


    protected $hidden = [
        'image'
    ];

    protected $appends = [
        'image_url'
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

    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }

    public function scopeFilter(Builder $builer , $filters){
        $options = array_merge([
            'category_id'=>null ,
            'user_id' =>null ,
            'status' =>'available',
        ] , $filters);

        $builer->when($options['category_id'] , function($builder , $value){
            $builder->where('category_id', $value);
        });

        $builer->when($options['user_id'] , function($builder , $value){
            $builder->where('user_id', $value);
        });
    }
}
