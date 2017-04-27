<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bookings';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['date', 'customer_id', 'cleaner_id', 'time', 'hour'];

    public static function getBookingByCleanerAndDate($cleaner_id, $date, $time) {
        return self::where(['cleaner_id'=>$cleaner_id, 'date'=>$date, 'time'=>$time])->first();
    }

    public static function makeBooking($data, $cleaner_id, $customer_id) {
        $booking = new Booking();
        $booking->cleaner_id = $cleaner_id;
        $booking->customer_id = $customer_id;
        $booking->time = $data['time'];
        $booking->date = $data['date'];
        $booking->hour = $data['hour'];
        $booking->save();
        return $booking;
    }

    
}
