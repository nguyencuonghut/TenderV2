<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'code',
        'title',
        'material_id',
        'packing',
        'origin',
        'delivery_condition',
        'payment_condition',
        'certificate',
        'other_term',
        'tender_start_time',
        'tender_end_time',
        'creator_id',
        'status',
        'approver_id',
        'tender_in_progress_time',
        'tender_closed_time',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function routeNotificationForMail()
    {
        return 'kiemsoatmuahang@honghafeed.com.vn';
    }

    public function propose()
    {
        return $this->hasOne(TenderPropose::class);
    }
}
