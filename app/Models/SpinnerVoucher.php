<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinnerVoucher extends Model
{
    use HasFactory;
    protected $table = 'spinner_voucher';
    protected $fillable = [
        'userid', 'jenis_voucher', 'kode_voucher', 'balance_kredit', 'username', 'bo', 'saldo', 'userklaim', 'tgl_klaim', 'tgl_exp', 'genvoucherid', 'status_transfer'
    ];

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['search'])) {
            $query->where('bo', getDataBo2());
            $query->where('userid', 'like', '%' . $filters['search'] .  '%')
                ->orWhere('jenis_voucher', 'like', '%' . $filters['search'] .  '%')
                ->orWhere('kode_voucher', 'like', '%' . $filters['search'] .  '%')
                ->orWhere('balance_kredit', 'like', '%' . $filters['search'] .  '%')
                ->orWhere('username', 'like', '%' . $filters['search'] .  '%')
                ->orWhere('bo', 'like', '%' . $filters['search'] .  '%')
                ->orWhere('userklaim', 'like', '%' . $filters['search'] .  '%');
        } else {
            $query->where('bo', getDataBo2());
        }
        return $query;
    }
}
