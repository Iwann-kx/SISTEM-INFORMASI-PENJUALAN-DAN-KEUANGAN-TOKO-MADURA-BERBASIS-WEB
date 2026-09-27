<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class WarungTransactionTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_sale_uses_database_price_and_decreases_stock(): void
    {
        $barang = $this->createBarang(stok: 5);

        $response = $this->post(route('penjualan.store'), [
            'bayar' => 50000,
            'total' => 1,
            'items' => [['barang_id' => $barang->id, 'qty' => 2]],
        ]);

        $response->assertRedirect(route('penjualan'));
        $this->assertDatabaseHas('penjualan', ['total' => 30000, 'bayar' => 50000, 'kembalian' => 20000]);
        $this->assertDatabaseHas('penjualan_detail', ['barang_id' => $barang->id, 'qty' => 2, 'harga' => 15000, 'subtotal' => 30000]);
        $this->assertSame(3, $barang->fresh()->stok_barang);
    }

    public function test_sale_with_insufficient_stock_is_not_saved(): void
    {
        $barang = $this->createBarang(stok: 1);

        $response = $this->from(route('penjualan'))->post(route('penjualan.store'), [
            'bayar' => 100000,
            'items' => [['barang_id' => $barang->id, 'qty' => 2]],
        ]);

        $response->assertRedirect(route('penjualan'))->assertSessionHasErrors('items');
        $this->assertDatabaseCount('penjualan', 0);
        $this->assertSame(1, $barang->fresh()->stok_barang);
    }

    public function test_sale_with_insufficient_payment_is_not_saved(): void
    {
        $barang = $this->createBarang(stok: 5);

        $response = $this->from(route('penjualan'))->post(route('penjualan.store'), [
            'bayar' => 10000,
            'items' => [['barang_id' => $barang->id, 'qty' => 1]],
        ]);

        $response->assertRedirect(route('penjualan'))->assertSessionHasErrors('bayar');
        $this->assertDatabaseCount('penjualan', 0);
        $this->assertSame(5, $barang->fresh()->stok_barang);
    }

    public function test_purchase_uses_database_cost_and_increases_stock(): void
    {
        $barang = $this->createBarang(stok: 4);
        $supplier = Supplier::factory()->create();

        $response = $this->post(route('pembelian.store'), [
            'supplier_id' => $supplier->id,
            'total' => 1,
            'items' => [['barang_id' => $barang->id, 'qty' => 3]],
        ]);

        $response->assertRedirect(route('pembelian'));
        $this->assertDatabaseHas('pembelian', ['supplier_id' => $supplier->id, 'total' => 30000]);
        $this->assertDatabaseHas('pembelian_detail', ['barang_id' => $barang->id, 'qty' => 3, 'harga' => 10000, 'subtotal' => 30000]);
        $this->assertSame(7, $barang->fresh()->stok_barang);
    }

    public function test_stock_page_escapes_product_names(): void
    {
        $unsafeName = '<script>alert(1)</script>';
        Barang::factory()->create(['nama_barang' => $unsafeName]);

        $response = $this->get(route('stok'));

        $response->assertSee($unsafeName);
        $response->assertDontSee($unsafeName, false);
    }

    private function createBarang(int $stok): Barang
    {
        return Barang::factory()->create([
            'harga_beli' => 10000,
            'harga_jual' => 15000,
            'stok_barang' => $stok,
        ]);
    }
}
