<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['name', 'type', 'price_per_hour', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'price_per_hour' => 'decimal:2'];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
