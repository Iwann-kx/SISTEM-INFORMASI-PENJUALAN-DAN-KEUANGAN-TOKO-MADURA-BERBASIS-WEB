<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    public const UPDATED_AT = null;

    protected $fillable = [
        'no_transaksi',
        'tanggal',
        'total',
        'bayar',
        'kembalian',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',
            'total' => 'integer',
            'bayar' => 'integer',
            'kembalian' => 'integer',
        ];
    }

    public function details(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}
