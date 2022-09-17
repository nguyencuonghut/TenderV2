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
        'material_id',
        'tender_id',
    ];

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
