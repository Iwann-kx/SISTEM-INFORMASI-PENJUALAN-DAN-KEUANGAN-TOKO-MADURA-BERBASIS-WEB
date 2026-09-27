<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_detail';

    public $timestamps = false;

    protected $fillable = ['penjualan_id', 'barang_id', 'qty', 'harga', 'subtotal'];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'harga' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
