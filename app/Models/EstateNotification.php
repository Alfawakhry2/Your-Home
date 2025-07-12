<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstateNotification extends Model
{
    protected $fillable = [
        'user_id' , 'estate_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }
}
