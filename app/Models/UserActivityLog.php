<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tender_id',
        'quantity_id',
        'activity_type',
        'old_price',
        'old_price_unit',
        'new_price',
        'new_price_unit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
    public function quantity()
    {
        return $this->belongsTo(QuantityAndDeliveryTime::class);
    }

}
