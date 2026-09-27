<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    public const UPDATED_AT = null;

    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'alamat',
        'telepon',
    ];

    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class);
    }
}
