<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category_id', $request->category);
            })
            ->latest()
            ->get()
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category?->name,
                'description' => $product->description,
                'price' => (float) $product->price,
                'stock' => $product->stock,
                'image_url' => $product->image ? asset('storage/' . $product->image) : null,
            ]);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }
}
