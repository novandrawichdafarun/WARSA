<?php

namespace Database\Seeders;

use App\Models\CommissionLedger;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\Warung;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    private const COMMISSION_RATE = 0.0050; // 0.5%
    private const DAYS = 30;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Membuat riwayat transaksi 30 hari...');

        $warungSlugs = [
            'warung-makan-otong',
            'warung-sembako-pak-bejo',
            'kedai-kopi-mbak-yuni',
        ];

        foreach ($warungSlugs as $slug) {
            $warung = Warung::where('slug', $slug)->first();
            if (!$warung)
                continue;

            $this->command->line("  → {$warung->nama_warung}");
            $this->seedWarung($warung);
        }

        $this->command->info('Selesai! Riwayat transaksi berhasil dibuat.');
    }

    private function seedWarung(Warung $warung): void
    {
        $userIds = User::where('warung_id', $warung->id)
            ->whereIn('role', ['owner', 'kasir'])
            ->pluck('id')
            ->toArray();

        if (empty($userIds))
            return;

        $products = Product::where('warung_id', $warung->id)
            ->where('is_active', true)
            ->get();

        if ($products->isEmpty())
            return;

        $stokTracker = $products->pluck('stok', 'id')->toArray();

        $initialDate = now()->subDays(self::DAYS + 1);
        $ownerUserId = User::where('warung_id', $warung->id)
            ->where('role', 'owner')
            ->value('id') ?? $userIds[0];

        foreach ($products as $product) {
            StockMovement::create([
                'warung_id' => $warung->id,
                'product_id' => $product->id,
                'user_id' => $ownerUserId,
                'transaction_id' => null,
                'type' => 'in',
                'quantity' => $product->stok,
                'stok_sebelum' => 0,
                'stok_sesudah' => $product->stok,
                'keterangan' => 'Stok awal',
                'created_at' => $initialDate,
                'updated_at' => $initialDate,
            ]);
        }

        for ($dayAgo = self::DAYS; $dayAgo >= 0; $dayAgo--) {
            $date = now()->subDays($dayAgo)->startOfDay();
            $txCount = $this->getTxCount($warung->slug, $date);

            for ($i = 0; $i < $txCount; $i++) {
                $this->createTransaction($warung, $userIds, $products, $stokTracker, $date);
            }
        }

        foreach ($products as $product) {
            $finalStok = max(0, $stokTracker[$product->id] ?? 0);
            Product::where('id', $product->id)->update(['stok' => $finalStok]);
        }
    }

    private function getTxCount(string $slug, Carbon $date): int
    {
        $isWeekend = in_array($date->dayOfWeek, [0, 6]);
        $isSenin = $date->dayOfWeek === 1;

        return match (true) {
            str_contains($slug, 'bu-sari') => match (true) {
                    $isWeekend => rand(12, 18),
                    $isSenin => rand(5, 8),
                    default => rand(8, 13),
                },
            str_contains($slug, 'pak-bejo') => match (true) {
                    $isWeekend => rand(8, 12),
                    $isSenin => rand(4, 6),
                    default => rand(6, 10),
                },
            str_contains($slug, 'mbak-yuni') => match (true) {
                    $isWeekend => rand(15, 22),
                    $isSenin => rand(6, 9),
                    default => rand(9, 14),
                },
            default => rand(6, 10),
        };
    }

    private function createTransaction(
        Warung $warung,
        array $userIds,
        $products,
        array &$stokTracker,
        Carbon $date
    ): void {
        $tersedia = $products->filter(fn($p) => ($stokTracker[$p->id] ?? 0) > 0);
        if ($tersedia->isEmpty())
            return;

        $jumlahItem = rand(1, min(4, $tersedia->count()));
        $produkDipilih = $tersedia->shuffle()->take($jumlahItem);

        $totalGross = 0;
        $itemsData = [];

        foreach ($produkDipilih as $product) {
            $maxQty = min(3, $stokTracker[$product->id] ?? 1);
            if ($maxQty <= 0)
                continue;

            $qty = rand(1, $maxQty);
            $subtotal = $product->harga_jual * $qty;
            $totalGross += $subtotal;

            $itemsData[] = [
                'product' => $product,
                'quantity' => $qty,
                'subtotal' => $subtotal,
            ];
        }

        if (empty($itemsData))
            return;

        $qrisPercentage = str_contains($warung->slug, 'mbak-yuni') ? 65 : 45;
        $paymentMethod = rand(1, 100) <= $qrisPercentage ? 'qris' : 'cash';

        $roll = rand(1, 100);
        $paymentStatus = match (true) {
            $roll <= 87 => 'paid',
            $roll <= 95 => 'cancelled',
            default => 'pending',
        };

        $commissionAmount = (int) round($totalGross * self::COMMISSION_RATE);
        $totalNet = $totalGross - $commissionAmount;

        $txTime = $date->copy()->addMinutes(rand(8 * 60, 22 * 60));
        $userId = $userIds[array_rand($userIds)];

        $midtransOrderId = null;
        if ($paymentMethod === 'qris' && in_array($paymentStatus, ['paid', 'pending'])) {
            $midtransOrderId = sprintf(
                'WARSA-%d-%s-%s',
                $warung->id,
                $txTime->format('YmdHi'),
                strtoupper(Str::random(6))
            );
        }

        $transaction = Transaction::create([
            'warung_id' => $warung->id,
            'user_id' => $userId,
            'total_gross' => $totalGross,
            'commission_rate' => self::COMMISSION_RATE,
            'commission_amount' => $commissionAmount,
            'total_net' => $totalNet,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'paid_at' => $paymentStatus === 'paid' ? $txTime : null,
            'cancelled_at' => $paymentStatus === 'cancelled' ? $txTime : null,
            'created_at' => $txTime,
            'updated_at' => $txTime,
        ]);

        foreach ($itemsData as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product']->id,
                'nama_snapshot' => $item['product']->nama_produk,
                'harga_snapshot' => $item['product']->harga_jual,
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
                'created_at' => $txTime,
                'updated_at' => $txTime,
            ]);
        }

        if ($paymentStatus !== 'paid')
            return;

        CommissionLedger::create([
            'warung_id' => $warung->id,
            'transaction_id' => $transaction->id,
            'gross_amount' => $totalGross,
            'commission_rate' => self::COMMISSION_RATE,
            'commission_amount' => $commissionAmount,
            'status' => 'settled',
            'settled_at' => $txTime,
            'created_at' => $txTime,
            'updated_at' => $txTime,
        ]);

        foreach ($itemsData as $item) {
            $productId = $item['product']->id;
            $stokBefore = $stokTracker[$productId] ?? 0;
            $stokAfter = max(0, $stokBefore - $item['quantity']);
            $stokTracker[$productId] = $stokAfter;

            StockMovement::create([
                'warung_id' => $warung->id,
                'product_id' => $productId,
                'user_id' => $userId,
                'transaction_id' => $transaction->id,
                'type' => 'out',
                'quantity' => $item['quantity'],
                'stok_sebelum' => $stokBefore,
                'stok_sesudah' => $stokAfter,
                'keterangan' => 'Penjualan #' . $transaction->id,
                'created_at' => $txTime,
                'updated_at' => $txTime,
            ]);
        }
    }
}
