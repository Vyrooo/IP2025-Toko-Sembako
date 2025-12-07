<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    /**
     * @param  User   $user   User who performs the transaction.
     * @param  array  $items  Cart items [product_id, qty, price].
     * @param  float  $paid   Cash received from customer.
     */
    public function process(User $user, array $items, float $paid): Transaction
    {
        if (empty($items)) {
            throw ValidationException::withMessages([
                'items' => 'Keranjang masih kosong.',
            ]);
        }

        return DB::transaction(function () use ($user, $items, $paid) {
            $resolvedItems = [];
            $total = 0;

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $qty = (int) $item['qty'];

                if ($qty < 1) {
                    throw ValidationException::withMessages([
                        'qty' => 'Kuantitas minimal 1 untuk setiap barang.',
                    ]);
                }

                if ($product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'stock' => "Stok {$product->name} tidak mencukupi.",
                    ]);
                }

                $lineTotal = $product->selling_price * $qty;
                $total += $lineTotal;

                $resolvedItems[] = [
                    'product' => $product,
                    'qty' => $qty,
                    'price' => $product->selling_price,
                ];
            }

            if ($paid < $total) {
                throw ValidationException::withMessages([
                    'paid' => 'Nominal bayar kurang dari total bayar.',
                ]);
            }

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'total' => $total,
                'paid' => $paid,
                'change' => $paid - $total,
            ]);

            foreach ($resolvedItems as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                ]);

                $item['product']->decrement('stock', $item['qty']);
            }

            return $transaction->load(['items.product', 'user']);
        });
    }
}

