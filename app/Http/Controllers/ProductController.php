<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Exception;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::all();
            return view('products.index', compact('products'));
        } catch (Exception $e) {
            Log::error('Failed to load products: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load products. Please try again later.');
        }
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'sku' => 'required|string|unique:products,sku',
                'name' => 'required|string',
                'price' => 'required|numeric|min:0',
            ]);

            Product::create($validated);

            return redirect()->route('basket.index')->with('success', 'Product added!');
        } catch (Exception $e) {
            Log::error('Product creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to add product. Please try again.');
        }
    }
}
