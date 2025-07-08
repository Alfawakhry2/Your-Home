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

    // this used with api
    public function scopeFilter(Builder $builer , $filters){
        $options = array_merge([
            'category_id'=>null ,
            'user_id' =>null ,
            'status' =>null,
        ] , $filters);

        $builer->when($options['category_id'] , function($builder , $value){
            $builder->where('category_id', $value);
        });

        $builer->when($options['user_id'] , function($builder , $value){
            $builder->where('user_id', $value);
        });
    }


    //Filter


    /**
     * Filter by location (city/area)
     */
    public function scopeLocation(Builder $query,  $location)
    {
        if ($location) {
            return $query->where('location', 'like', "%{$location}%");
        }
        return $query;
    }

    /**
     * Filter by category ID
     */
    public function scopeCategory(Builder $query,  $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    /**
     * Filter by status (available/rented/sold)
     */
    public function scopeStatus(Builder $query,  $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Filter by type (rent/sale)
     */
    public function scopeType(Builder $query,  $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }

    /**
     * Filter by minimum bedrooms
     */
    public function scopeBedrooms(Builder $query,  $bedrooms)
    {
        if ($bedrooms) {
            return $query->where('bedrooms', '>=', $bedrooms);
        }
        return $query;
    }

    /**
     * Filter by minimum bathrooms
     */
    public function scopeBathrooms(Builder $query,  $bathrooms)
    {
        if ($bathrooms) {
            return $query->where('bathrooms', '>=', $bathrooms);
        }
        return $query;
    }
}
