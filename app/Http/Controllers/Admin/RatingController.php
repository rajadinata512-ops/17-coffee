<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::with(['user', 'product', 'order'])
            ->latest()
            ->paginate(20);

        $avgRating = Rating::avg('rating');
        $totalRatings = Rating::count();

        return view('admin.ratings.index', compact('ratings', 'avgRating', 'totalRatings'));
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();
        return redirect()->route('admin.ratings.index')
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}
