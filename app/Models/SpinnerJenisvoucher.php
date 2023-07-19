<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinnerJenisvoucher extends Model
{
    use HasFactory;
    protected $table = 'spinner_jenisvoucher';
    protected $fillable = [
        'nama', 'index', 'saldo_point', 'bo'
    ];

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['search'])) {
            return $query->where('nama', 'like', '%' . $filters['search'] .  '%')
                // ->orWhere('nama', 'like', '%' . $filters['search'] .  '%')
            ;
        }
    }
}
