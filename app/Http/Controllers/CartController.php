<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $cart->load('cartItems.product.category');

        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $shippingCost = $cart->cartItems->isEmpty() ? 0 : 8000;
        $total = $subtotal + $shippingCost;

        return view('user.cart.index', compact('cart', 'subtotal', 'shippingCost', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        if ($product->status !== 'active' || $product->stock < 1) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        if ($data['quantity'] > $product->stock) {
            return back()->with('error', 'Jumlah melebihi stok tersedia.');
        }

        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $newQuantity = $item->quantity + $data['quantity'];

            if ($newQuantity > $product->stock) {
                return back()->with('error', 'Jumlah di keranjang melebihi stok tersedia.');
            }

            $item->update([
                'quantity' => $newQuantity,
                'price' => $product->price,
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'price' => $product->price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Menu berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        abort_unless($cartItem->cart->user_id === auth()->id(), 403);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        if ($cartItem->product && $data['quantity'] > $cartItem->product->stock) {
            return back()->with('error', 'Jumlah melebihi stok tersedia.');
        }

        $cartItem->update([
            'quantity' => $data['quantity'],
        ]);

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function destroy(CartItem $cartItem)
    {
        abort_unless($cartItem->cart->user_id === auth()->id(), 403);

        $cartItem->delete();

        return back()->with('success', 'Menu berhasil dihapus dari keranjang.');
    }
}