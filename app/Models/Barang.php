<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    public const UPDATED_AT = null;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'harga_beli',
        'harga_jual',
        'stok_barang',
        'satuan',
        'gambar',
    ];

    protected function casts(): array
    {
        return [
            'harga_beli' => 'integer',
            'harga_jual' => 'integer',
            'stok_barang' => 'integer',
        ];
    }

    public function penjualanDetails(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class);
    }

    public function pembelianDetails(): HasMany
    {
        return $this->hasMany(PembelianDetail::class);
    }
}
