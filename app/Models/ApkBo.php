<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApkBo extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['search'])) {
            return $query->where('nama', 'like', '%' . $filters['search'] .  '%')
                // ->orWhere('nama', 'like', '%' . $filters['search'] .  '%')
            ;
        }
    }
}
