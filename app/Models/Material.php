<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'quality',
    ];

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'material_supplier');
    }
}
