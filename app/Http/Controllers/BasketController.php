<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    // Display the basket content
    public function index()
    {
        $userId = Auth::id();
        $baskets = Basket::where('user_id', $userId)->get();
        $products = Product::all();

        $subtotal = 0;
        $discount = 0;
        // dd($baskets->toArray());
        foreach ($baskets as $item) {
            $quantity = $item->quantity;
            $price = $item->product_price;

            // Standard total for this product
            $itemTotal = $price * $quantity;
            $subtotal += $itemTotal;

            // Special offer: every second item is half price
            $halfPriceCount = floor($quantity / 2);
            $discount += ($price / 2) * $halfPriceCount;
        }

        $subtotalAfterDiscount = $subtotal - $discount;

        // Delivery charge rules
        if ($subtotalAfterDiscount >= 90) {
            $delivery = 0;
        } elseif ($subtotalAfterDiscount >= 50) {
            $delivery = 2.95;
        } else {
            $delivery = 4.95;
        }

        $total = $subtotalAfterDiscount + $delivery;
        return view('basket.index', compact(
            'baskets',
            'subtotal',
            'discount',
            'products',
            'delivery',
            'total'
        ));
    }

    // Add product to the basket
    public function add(Request $request)
    {
        $sku = $request->input('sku');
        $product = Product::where('sku', $sku)->firstOrFail();

        // Check if the product already exists in the user's basket
        $userId = Auth::id();
        $basketItem = Basket::where('user_id', $userId)->where('sku', $sku)->first();

        if ($basketItem) {
            // If the product already exists, update the quantity
            $basketItem->quantity += 1;
            $basketItem->save();
        } else {
            // Otherwise, create a new basket item
            Basket::create([
                'user_id' => $userId,
                'sku' => $product->sku,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'quantity' => 1,
            ]);
        }

        return redirect()->route('basket.index')->with('success', 'Product added to basket!');
    }

    // Clear the user's basket
    public function clear()
    {
        $userId = Auth::id();
        Basket::where('user_id', $userId)->delete();

        return redirect()->route('basket.index')->with('success', 'Basket cleared!');
    }
}
