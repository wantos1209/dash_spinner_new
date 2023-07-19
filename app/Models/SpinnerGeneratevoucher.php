<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinnerGeneratevoucher extends Model
{
    use HasFactory;
    protected $table = 'spinner_generatevoucher';
    protected $fillable = [
        'jenis_voucher', 'tgl_exp', 'jumlah', 'userid' , 'bo'
    ];

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['search'])) {
            return $query->where('jenis_voucher', 'like', '%' . $filters['search'] .  '%')
                // ->orWhere('nama', 'like', '%' . $filters['search'] .  '%')
            ;
        }
    }
}
