<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class TenderApproveComment extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'comment',
        'tender_id',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
}
