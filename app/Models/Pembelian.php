<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    public const UPDATED_AT = null;

    protected $fillable = [
        'no_faktur',
        'supplier_id',
        'tanggal',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',
            'total' => 'integer',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(PembelianDetail::class);
    }
}
