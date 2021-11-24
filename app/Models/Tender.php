<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'material_id',
        'quantity_and_delivery_time',
        'quality',
        'delivery_condition',
        'payment_condition',
        'certificate',
        'other_term',
        'tender_start_time',
        'tender_end_time',
        'creator_id',
        'status',
        'supplier_ids',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Tender::class, 'tender_supplier');
    }
}
