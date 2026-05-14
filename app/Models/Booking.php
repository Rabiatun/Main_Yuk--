<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id', 'field_id', 'booking_date',
        'start_time', 'end_time', 'duration_hours',
        'total_price', 'status', 'payment_status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'total_price'  => 'decimal:2',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
