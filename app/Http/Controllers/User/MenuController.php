<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $categorySlug = $request->get('category', 'all');

        $categories = Category::query()
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active')
                    ->where('stock', '>', 0);
            }])
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->with('category')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($categorySlug && $categorySlug !== 'all', function ($query) use ($categorySlug) {
                $query->whereHas('category', function ($categoryQuery) use ($categorySlug) {
                    $categoryQuery->where('slug', $categorySlug)
                        ->orWhereRaw('LOWER(name) = ?', [strtolower(str_replace('-', ' ', $categorySlug))]);
                });
            })
            ->latest()
            ->get();

        return view('user.menu.index', compact('products', 'categories', 'search', 'categorySlug'));
    }
}