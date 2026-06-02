<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    public function deleting(Product $product): void
    {
        $this->deleteProductImage($product->image ?? null);
    }

    private function deleteProductImage(?string $image): void
    {
        if (!$image) {
            return;
        }

        $image = trim(str_replace('\\', '/', $image));
        $image = ltrim($image, '/');

        $candidates = [
            $image,
            preg_replace('#^storage/#', '', $image),
            preg_replace('#^public/#', '', $image),
            'products/' . basename($image),
        ];

        foreach (array_unique(array_filter($candidates)) as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $publicStoragePath = public_path('storage/products/' . basename($image));
        if (is_file($publicStoragePath) && !is_link(public_path('storage'))) {
            @unlink($publicStoragePath);
        }
    }
}