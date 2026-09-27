<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $table = 'pembelian_detail';

    public $timestamps = false;

    protected $fillable = ['pembelian_id', 'barang_id', 'qty', 'harga', 'subtotal'];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'harga' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
