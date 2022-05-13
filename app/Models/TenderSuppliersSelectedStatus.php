<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class TenderSuppliersSelectedStatus extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'reason',
        'tender_id',
        'is_selected',
        'supplier_id',
    ];
}
