<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuantityAndDeliveryTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'quantity_unit',
        'delivery_time',
        'tender_id',
    ];

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
}
