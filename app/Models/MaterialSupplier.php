<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialSupplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'supplier_id',
    ];

    protected $table = 'material_supplier';
}
