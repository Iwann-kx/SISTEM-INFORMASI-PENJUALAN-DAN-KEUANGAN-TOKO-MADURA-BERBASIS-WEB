<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WarungController extends Controller
{
    public function dashboard(): View
    {
        $today = today();
        $salesToday = Penjualan::whereDate('tanggal', $today)->sum('total');
        $purchasesToday = Pembelian::whereDate('tanggal', $today)->sum('total');

        $recentSales = Penjualan::latest('tanggal')->limit(5)->get()->map(fn (Penjualan $sale) => [
            'type' => 'Penjualan',
            'number' => $sale->no_transaksi,
            'date' => $sale->tanggal,
            'total' => $sale->total,
        ]);
        $recentPurchases = Pembelian::latest('tanggal')->limit(5)->get()->map(fn (Pembelian $purchase) => [
            'type' => 'Pembelian',
            'number' => $purchase->no_faktur,
            'date' => $purchase->tanggal,
            'total' => $purchase->total,
        ]);

        $summary = collect(range(6, 0))->map(function (int $daysAgo): array {
            $date = today()->subDays($daysAgo);

            return [
                'label' => $date->format('d/m'),
                'sales' => (int) Penjualan::whereDate('tanggal', $date)->sum('total'),
                'purchases' => (int) Pembelian::whereDate('tanggal', $date)->sum('total'),
            ];
        });

        return view('dashboard', [
            'salesToday' => (int) $salesToday,
            'purchasesToday' => (int) $purchasesToday,
            'profitToday' => (int) $salesToday - (int) $purchasesToday,
            'productCount' => Barang::count(),
            'lowStockCount' => Barang::where('stok_barang', '<=', 5)->count(),
            'lowStockItems' => Barang::where('stok_barang', '<=', 10)->orderBy('stok_barang')->limit(8)->get(),
            'recentTransactions' => $recentSales->concat($recentPurchases)->sortByDesc('date')->take(8),
            'summary' => $summary,
            'maxSummary' => max(1, (int) $summary->max(fn (array $day) => max($day['sales'], $day['purchases']))),
        ]);
    }

    public function stok(): View
    {
        return view('stok', ['barangList' => Barang::orderBy('nama_barang')->get()]);
    }

    public function storeBarang(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_barang' => ['required', 'string', 'max:50', 'unique:barang,kode_barang'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'harga_beli' => ['required', 'integer', 'min:0'],
            'harga_jual' => ['required', 'integer', 'min:0'],
            'stok_barang' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
        ]);

        Barang::create($data);

        return back()->with('success', 'Barang berhasil ditambahkan.');
    }

    public function updateBarang(Request $request, Barang $barang): RedirectResponse
    {
        $data = $request->validate([
            'kode_barang' => ['required', 'string', 'max:50', 'unique:barang,kode_barang,'.$barang->id],
            'nama_barang' => ['required', 'string', 'max:255'],
            'harga_beli' => ['required', 'integer', 'min:0'],
            'harga_jual' => ['required', 'integer', 'min:0'],
            'stok_barang' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
        ]);

        $barang->update($data);

        return back()->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroyBarang(Barang $barang): RedirectResponse
    {
        if ($barang->penjualanDetails()->exists() || $barang->pembelianDetails()->exists()) {
            return back()->withErrors(['barang' => 'Barang yang sudah tercatat dalam transaksi tidak dapat dihapus.']);
        }

        $barang->delete();

        return back()->with('success', 'Barang berhasil dihapus.');
    }

    public function penjualan(): View
    {
        return view('penjualan', ['barangList' => Barang::where('stok_barang', '>', 0)->orderBy('nama_barang')->get()]);
    }

    public function storePenjualan(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bayar' => ['required', 'integer', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.barang_id' => ['required', 'integer', 'distinct', 'exists:barang,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $sale = DB::transaction(function () use ($data): Penjualan {
            $items = collect($data['items'])->keyBy('barang_id');
            $products = Barang::whereIn('id', $items->keys())->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $total = 0;

            foreach ($items as $productId => $item) {
                $product = $products->get($productId);

                if (! $product || $product->stok_barang < $item['qty']) {
                    throw ValidationException::withMessages([
                        'items' => 'Stok '.$product?->nama_barang.' tidak mencukupi untuk jumlah yang diminta.',
                    ]);
                }

                $total += $product->harga_jual * $item['qty'];
            }

            if ($data['bayar'] < $total) {
                throw ValidationException::withMessages(['bayar' => 'Jumlah pembayaran kurang dari total transaksi.']);
            }

            $sale = Penjualan::create([
                'no_transaksi' => $this->transactionNumber('TRX'),
                'tanggal' => now(),
                'total' => $total,
                'bayar' => $data['bayar'],
                'kembalian' => $data['bayar'] - $total,
            ]);

            foreach ($items as $productId => $item) {
                $product = $products->get($productId);
                $subtotal = $product->harga_jual * $item['qty'];
                $sale->details()->create([
                    'barang_id' => $product->id,
                    'qty' => $item['qty'],
                    'harga' => $product->harga_jual,
                    'subtotal' => $subtotal,
                ]);
                $product->decrement('stok_barang', $item['qty']);
            }

            return $sale;
        });

        return redirect()->route('penjualan')->with('success', 'Transaksi '.$sale->no_transaksi.' tersimpan. Kembalian: Rp '.number_format($sale->kembalian, 0, ',', '.'));
    }

    public function pembelian(): View
    {
        return view('pembelian', [
            'barangList' => Barang::orderBy('nama_barang')->get(),
            'supplierList' => Supplier::orderBy('nama_supplier')->get(),
        ]);
    }

    public function storeSupplier(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_supplier' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
        ]);
        $data['kode_supplier'] = $this->transactionNumber('SUP');

        Supplier::create($data);

        return back()->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function storePembelian(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'integer', 'exists:supplier,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.barang_id' => ['required', 'integer', 'distinct', 'exists:barang,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $purchase = DB::transaction(function () use ($data): Pembelian {
            $items = collect($data['items'])->keyBy('barang_id');
            $products = Barang::whereIn('id', $items->keys())->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $total = 0;

            foreach ($items as $productId => $item) {
                $product = $products->get($productId);

                if (! $product) {
                    throw ValidationException::withMessages(['items' => 'Barang tidak ditemukan.']);
                }

                $total += $product->harga_beli * $item['qty'];
            }

            $purchase = Pembelian::create([
                'no_faktur' => $this->transactionNumber('FK'),
                'supplier_id' => $data['supplier_id'],
                'tanggal' => now(),
                'total' => $total,
            ]);

            foreach ($items as $productId => $item) {
                $product = $products->get($productId);
                $subtotal = $product->harga_beli * $item['qty'];
                $purchase->details()->create([
                    'barang_id' => $product->id,
                    'qty' => $item['qty'],
                    'harga' => $product->harga_beli,
                    'subtotal' => $subtotal,
                ]);
                $product->increment('stok_barang', $item['qty']);
            }

            return $purchase;
        });

        return redirect()->route('pembelian')->with('success', 'Faktur '.$purchase->no_faktur.' berhasil disimpan.');
    }

    public function laporan(Request $request): View
    {
        $data = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);
        $startDate = $data['start_date'] ?? today()->subDays(29)->toDateString();
        $endDate = $data['end_date'] ?? today()->toDateString();

        $sales = Penjualan::whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->latest('tanggal')->get();
        $purchases = Pembelian::with('supplier')
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->latest('tanggal')->get();

        return view('laporan', [
            'sales' => $sales,
            'purchases' => $purchases,
            'salesTotal' => (int) $sales->sum('total'),
            'purchasesTotal' => (int) $purchases->sum('total'),
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    private function transactionNumber(string $prefix): string
    {
        return $prefix.'-'.Carbon::now()->format('Ymd-His').'-'.strtoupper(bin2hex(random_bytes(2)));
    }
}
