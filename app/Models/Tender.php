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
        'packing',
        'origin',
        'delivery_condition',
        'payment_condition',
        'certificate',
        'other_term',
        'freight_charge',
        'tender_end_time',
        'creator_id',
        'status',
        'checker_id',
        'tender_in_progress_time',
        'tender_closed_time',
        'is_competitive_bids',
        'approve_result',
        'audit_result',
        'auditor_id',
        'close_reason',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class);
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

    public function quantity_and_delivery_times()
    {
        return $this->hasMany(QuantityAndDeliveryTime::class);
    }

    public function comments()
    {
        return $this->hasMany(TenderApproveComment::class);
    }

    public function manager()
    {
        return $this->belongsTo(Admin::class, 'manager_id');
    }

    public function auditor()
    {
        return $this->belongsTo(Admin::class, 'auditor_id');
    }
}
