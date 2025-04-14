<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\BasketService;

class BasketController extends Controller
{

    public function index(BasketService $basketService)
    {
        try {
            $userId = Auth::id();

            // ✅ Use service for logic
            $summary = $basketService->getSummaryForUser($userId);

            $products = Product::all(); // If you still need it in the view

            return view('basket.index', array_merge($summary, [
                'products' => $products
            ]));
        } catch (Exception $e) {
            Log::error('Basket view failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load basket. Please try again later.');
        }
    }


    public function add(Request $request)
    {
        try {
            $request->validate([
                'sku' => 'required|string|exists:products,sku',
            ]);

            $sku = $request->input('sku');
            $product = Product::where('sku', $sku)->first();

            $userId = Auth::id();

            $basketItem = Basket::where('user_id', $userId)
                ->where('sku', $sku)
                ->first();

            if ($basketItem) {
                $basketItem->increment('quantity');
            } else {
                Basket::create([
                    'user_id' => $userId,
                    'sku' => $product->sku,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => 1,
                ]);
            }

            return redirect()->route('basket.index')->with('success', 'Product added to basket!');
        } catch (Exception $e) {
            Log::error('Add to basket failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not add product to basket. Please try again.');
        }
    }

    public function clear()
    {
        try {
            $userId = Auth::id();
            Basket::where('user_id', $userId)->delete();

            return redirect()->route('basket.index')->with('success', 'Basket cleared!');
        } catch (Exception $e) {
            Log::error('Clear basket failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not clear basket.');
        }
    }
}
