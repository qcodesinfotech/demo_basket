<?php

namespace App\Services;

use App\Models\Basket;

class BasketService
{
    public function getSummaryForUser($userId)
    {
        $baskets = Basket::where('user_id', $userId)->get();

        $subtotal = 0;
        $discount = 0;

        foreach ($baskets as $item) {
            $subtotal += $item->subtotal;
            $discount += $item->discount;
        }

        $subtotalAfterDiscount = $subtotal - $discount;

        $delivery = $this->calculateDeliveryCharge($subtotalAfterDiscount);

        $total = $subtotalAfterDiscount + $delivery;

        return compact('baskets', 'subtotal', 'discount', 'delivery', 'total');
    }

    private function calculateDeliveryCharge($amount)
    {
        if ($amount >= 90) {
            return 0;
        } elseif ($amount >= 50) {
            return 2.95;
        } else {
            return 4.95;
        }
    }
}
