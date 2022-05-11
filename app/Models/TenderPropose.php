<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class TenderPropose extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'propose',
        'tender_id',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
}
