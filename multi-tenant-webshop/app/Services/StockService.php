<?php

namespace App\Services;

use App\Models\Tenant\Order;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductVariation;
use App\Models\Tenant\StockMutation;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Adjust stock for a product or variation and record a mutation audit entry.
     *
     * @param Product $product
     * @param ProductVariation|null $variation
     * @param int $delta Can be positive (+5) or negative (-2)
     * @param string $type ('purchase', 'sale', 'adjustment', 'return', 'cancel_restitution')
     * @param string|null $orderId
     * @param string|null $description
     * @return StockMutation
     * @throws \Exception
     */
    public function adjustStock(
        Product $product,
        ?ProductVariation $variation,
        int $delta,
        string $type,
        ?string $orderId = null,
        ?string $description = null
    ): StockMutation {
        return DB::transaction(function () use ($product, $variation, $delta, $type, $orderId, $description) {
            if ($variation) {
                $stockBefore = (int) $variation->stock;
                $stockAfter = $stockBefore + $delta;

                if ($stockAfter < 0) {
                    throw new \Exception("Onvoldoende voorraad voor variant '{$variation->sku}'. Huidig: {$stockBefore}, gevraagd: " . abs($delta));
                }

                $variation->update(['stock' => $stockAfter]);
            } else {
                $stockBefore = (int) $product->stock;
                $stockAfter = $stockBefore + $delta;

                if ($stockAfter < 0) {
                    throw new \Exception("Onvoldoende voorraad voor product '{$product->name}'. Huidig: {$stockBefore}, gevraagd: " . abs($delta));
                }

                $product->update(['stock' => $stockAfter]);
            }

            return StockMutation::create([
                'product_id' => $product->id,
                'product_variation_id' => $variation?->id,
                'order_id' => $orderId,
                'type' => $type,
                'quantity' => $delta,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'description' => $description,
            ]);
        });
    }

    /**
     * Deduct stock for all items in a paid/fulfilled order.
     */
    public function fulfillOrderStock(Order $order): void
    {
        // Prevent duplicate deduction
        $alreadyMutated = StockMutation::where('order_id', $order->id)
            ->where('type', 'sale')
            ->exists();

        if ($alreadyMutated) {
            return;
        }

        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if (!$product) {
                continue;
            }

            $variation = null;
            if ($item->product_variation_id) {
                $variation = ProductVariation::find($item->product_variation_id);
            }

            $this->adjustStock(
                product: $product,
                variation: $variation,
                delta: -((int) $item->quantity),
                type: 'sale',
                orderId: $order->id,
                description: "Verkoop via bestelling #{$order->order_number}"
            );
        }
    }

    /**
     * Restitute stock for a cancelled order.
     */
    public function restituteOrderStock(Order $order): void
    {
        // Only restitute if there were previous 'sale' deductions and not yet restituted
        $wasDeducted = StockMutation::where('order_id', $order->id)
            ->where('type', 'sale')
            ->exists();

        $alreadyRestituted = StockMutation::where('order_id', $order->id)
            ->where('type', 'cancel_restitution')
            ->exists();

        if (!$wasDeducted || $alreadyRestituted) {
            return;
        }

        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if (!$product) {
                continue;
            }

            $variation = null;
            if ($item->product_variation_id) {
                $variation = ProductVariation::find($item->product_variation_id);
            }

            $this->adjustStock(
                product: $product,
                variation: $variation,
                delta: (int) $item->quantity,
                type: 'cancel_restitution',
                orderId: $order->id,
                description: "Voorraadherstel geannuleerde bestelling #{$order->order_number}"
            );
        }
    }
}
