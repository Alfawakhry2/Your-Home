<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'estate_id',
        'date',
        'start_date',
        'end_date',
        'status',
        'price',
        'payment_status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class)
        ->withDefault('-')
        ;
    }

    //related to callback and response
    public static function handlePaymentStatus($merchantOrderId, array $data, bool $isSuccess)
    {

        $parts = explode('_', $merchantOrderId);
        $reservationId = $parts[0];

        //we used self , so is static
        $reservation = self::find($reservationId);
        if (! $reservation) {
            return null;
        }

        $reservation->status         = $isSuccess ? 'confirmed' : 'pending';
        $reservation->payment_status = $isSuccess ? 'paid' : 'pending';
        $reservation->payment_details = json_encode($data);
        $reservation->save();

        return $reservation;
    }
}
