<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * The roles that belong to the user.
     */
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'material_supplier');
    }
}
