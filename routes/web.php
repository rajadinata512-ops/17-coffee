<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RatingController;
use App\Models\Product;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController as UserOrderController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\RatingController as UserRatingController;
use App\Http\Controllers\User\MenuController as UserMenuController;

/*
|--------------------------------------------------------------------------
| Public Routes - 17 Coffee
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $products = collect();

    try {
        if (class_exists(\App\Models\Product::class)) {
            $query = \App\Models\Product::query();

            if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'status')) {
                $query->where('status', 'active');
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'stock')) {
                $query->where('stock', '>', 0);
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'is_featured')) {
                $query->where('is_featured', true);
            }

            $products = $query->latest()->take(8)->get();
        }
    } catch (\Throwable $e) {
        $products = collect();
    }

    return view('home', compact('products'));
})->name('home');

Route::get('/about', function () {
    return redirect('/#about');
})->name('about');

Route::get('/tentang', function () {
    return redirect('/#about');
})->name('tentang');

Route::get('/contact', function () {
    return redirect('/#contact');
})->name('contact');


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/product-image/{product}', function (\App\Models\Product $product) {
    if (!$product->image || !Storage::disk('public')->exists($product->image)) {
        return redirect(asset('images/cappuccino.jpg'));
    }

    return response()->file(Storage::disk('public')->path($product->image));
})->name('product.image');



/*
|--------------------------------------------------------------------------
| Midtrans Payment Gateway Routes
|--------------------------------------------------------------------------
| Set Payment Notification URL di Dashboard Midtrans:
| https://domain-kamu.com/midtrans/notification
*/
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');
Route::get('/payment/finish', [MidtransController::class, 'finish'])->name('midtrans.finish')->middleware(['auth', 'verified', 'otp.session']);
Route::get('/payment/unfinish', [MidtransController::class, 'unfinish'])->name('midtrans.unfinish')->middleware(['auth', 'verified', 'otp.session']);
Route::get('/payment/error', [MidtransController::class, 'error'])->name('midtrans.error')->middleware(['auth', 'verified', 'otp.session']);

/*
|--------------------------------------------------------------------------
| User Dashboard
|--------------------------------------------------------------------------
*/



Route::middleware(['auth', 'verified', 'otp.session', 'throttle:60,1'])->group(function () {
    Route::get('/menu', [UserMenuController::class, 'index'])->name('user.menu');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add')->middleware('throttle:20,1');
    Route::patch('/cart/item/{cartItem}', [CartController::class, 'update'])->name('cart.update')->middleware('throttle:30,1');
    Route::delete('/cart/item/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy')->middleware('throttle:30,1');

    Route::get('/checkout', [UserOrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [UserOrderController::class, 'store'])->name('orders.store')->middleware('throttle:10,1');

    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');

    Route::post('/orders/{order}/rating', [UserRatingController::class, 'store'])->name('ratings.store')->middleware('throttle:10,1');
});

/*
|--------------------------------------------------------------------------
| Admin Routes  (protected: auth + admin middleware)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'otp.session', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Categories
        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        // Products
        Route::resource('products', ProductController::class)
            ->except(['show']);

        // Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::patch('/orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('orders.updatePayment');

        // Ratings
        Route::get('/ratings', [RatingController::class, 'index'])->name('ratings.index');
        Route::delete('/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');

    });

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/


Route::get('/category-image/{category}', function (\App\Models\Category $category) {
    if (!$category->image) {
        abort(404);
    }

    $path = storage_path('app/public/' . $category->image);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->name('category.image');

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| User Order Cancel Route
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'otp.session'])->post('/orders/{order}/cancel', [\App\Http\Controllers\User\OrderActionController::class, 'cancel'])->name('orders.cancel');
