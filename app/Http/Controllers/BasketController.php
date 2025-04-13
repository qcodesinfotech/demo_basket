<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BasketController extends Controller
{



    // working on second half price
//     public function index()
// {
//     $products = Product::all();
//     $basket = Session::get('basket', []);

//     $subtotal = 0;
//     $discount = 0;

//     foreach ($basket as $sku => $item) {
//         $quantity = $item['quantity'];
//         $price = $item['price'];

//         // Standard total for this product
//         $itemTotal = $price * $quantity;
//         $subtotal += $itemTotal;

//         // Special offer: every second item is half price
//         $halfPriceCount = floor($quantity / 2);
//         $discount += ($price / 2) * $halfPriceCount;
//     }

//     $subtotalAfterDiscount = $subtotal - $discount;

//     // Delivery charge rules
//     if ($subtotalAfterDiscount >= 90) {
//         $delivery = 0;
//     } elseif ($subtotalAfterDiscount >= 50) {
//         $delivery = 2.95;
//     } else {
//         $delivery = 4.95;
//     }

//     $total = $subtotalAfterDiscount + $delivery;

//     return view('basket.index', compact(
//         'products', 'basket', 'subtotal', 'discount', 'delivery', 'total'
//     ));
// }

    public function index()
    {
        $products = Product::all();
        $basket = Session::get('basket', []);
    
        $subtotal = 0;
    
        foreach ($basket as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    
        // Delivery charge logic
        if ($subtotal >= 90) {
            $delivery = 0;
        } elseif ($subtotal >= 50) {
            $delivery = 2.95;
        } else {
            $delivery = 4.95;
        }
    
        $total = $subtotal + $delivery;
    
        return view('basket.index', compact('products', 'basket', 'subtotal', 'delivery', 'total'));
    }

    public function add(Request $request)
    {
        $sku = $request->input('sku');
        $product = Product::where('sku', $sku)->firstOrFail();

        $basket = Session::get('basket', []);

        if (isset($basket[$sku])) {
            $basket[$sku]['quantity'] += 1;
        } else {
            $basket[$sku] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        Session::put('basket', $basket);

        return redirect()->route('basket.index')->with('success', 'Product added to basket!');
    }

    public function clear()
    {
        Session::forget('basket');
        return redirect()->route('basket.index')->with('success', 'Basket cleared!');
    }
}
