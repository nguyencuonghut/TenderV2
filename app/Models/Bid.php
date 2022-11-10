<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'user_id',
        'supplier_id',
        'quantity_id',
        'price',
        'price_unit',
        'note',
        'seller',
        'pack',
        'delivery_time',
        'tender_quantity',
        'tender_quantity_unit',
        'bid_quantity',
        'bid_quantity_unit',
        'is_selected',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function quantity()
    {
        return $this->belongsTo(QuantityAndDeliveryTime::class);
    }

}
