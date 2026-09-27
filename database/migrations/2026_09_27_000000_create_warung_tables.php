<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $addId = static function (Blueprint $table): void {
            if (Schema::getConnection()->getDriverName() === 'sqlite') {
                $table->increments('id');
            } else {
                $table->integer('id')->autoIncrement();
                $table->primary('id');
            }
        };

        Schema::create('barang', function (Blueprint $table) use ($addId) {
            $addId($table);
            $table->string('kode_barang', 50)->unique();
            $table->string('nama_barang', 255);
            $table->integer('harga_beli');
            $table->integer('harga_jual');
            $table->integer('stok_barang')->default(0);
            $table->string('satuan', 50);
            $table->string('gambar', 255)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->index('nama_barang', 'idx_barang_nama');
        });

        Schema::create('supplier', function (Blueprint $table) use ($addId) {
            $addId($table);
            $table->string('kode_supplier', 50)->unique();
            $table->string('nama_supplier', 255);
            $table->text('alamat')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
        });

        Schema::create('penjualan', function (Blueprint $table) use ($addId) {
            $addId($table);
            $table->string('no_transaksi', 50)->unique();
            $table->timestamp('tanggal')->nullable()->useCurrent();
            $table->integer('total')->default(0);
            $table->integer('bayar')->default(0);
            $table->integer('kembalian')->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->index('tanggal', 'idx_penjualan_tanggal');
        });

        Schema::create('penjualan_detail', function (Blueprint $table) use ($addId) {
            $addId($table);
            $table->integer('penjualan_id');
            $table->integer('barang_id');
            $table->integer('qty');
            $table->integer('harga');
            $table->integer('subtotal');
            $table->foreign('penjualan_id')->references('id')->on('penjualan')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('barang_id')->references('id')->on('barang')->restrictOnDelete()->cascadeOnUpdate();
        });

        Schema::create('pembelian', function (Blueprint $table) use ($addId) {
            $addId($table);
            $table->string('no_faktur', 50)->unique();
            $table->integer('supplier_id');
            $table->timestamp('tanggal')->nullable()->useCurrent();
            $table->integer('total')->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->foreign('supplier_id')->references('id')->on('supplier')->restrictOnDelete()->cascadeOnUpdate();
            $table->index('tanggal', 'idx_pembelian_tanggal');
        });

        Schema::create('pembelian_detail', function (Blueprint $table) use ($addId) {
            $addId($table);
            $table->integer('pembelian_id');
            $table->integer('barang_id');
            $table->integer('qty');
            $table->integer('harga');
            $table->integer('subtotal');
            $table->foreign('pembelian_id')->references('id')->on('pembelian')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('barang_id')->references('id')->on('barang')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_detail');
        Schema::dropIfExists('pembelian');
        Schema::dropIfExists('penjualan_detail');
        Schema::dropIfExists('penjualan');
        Schema::dropIfExists('supplier');
        Schema::dropIfExists('barang');
    }
};
