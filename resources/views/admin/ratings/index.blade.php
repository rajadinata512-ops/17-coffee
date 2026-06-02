@extends('admin.layouts.app')

@section('title', 'Rating & Ulasan')
@section('page-title', 'Rating & Ulasan')
@section('page-subtitle', 'Semua ulasan dari pelanggan')

@section('content')

{{-- Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
  <div class="stat-card text-center">
    <div class="text-4xl font-black text-[#1a0d06]">{{ number_format($avgRating, 1) }}</div>
    <div class="text-yellow-400 text-xl my-1">
      @for($i=1;$i<=5;$i++)
        <i class="fas fa-star{{ $i <= round($avgRating) ? '' : ($i - 0.5 <= $avgRating ? '-half-alt' : '') }}" style="font-size:16px"></i>
      @endfor
    </div>
    <p class="text-xs text-gray-400">Rata-rata rating</p>
  </div>
  <div class="stat-card text-center">
    <div class="text-4xl font-black text-[#1a0d06]">{{ $totalRatings }}</div>
    <p class="text-xs text-gray-400 mt-2">Total ulasan</p>
  </div>
  <div class="stat-card text-center">
    <div class="text-4xl font-black text-green-600">
      {{ \App\Models\Rating::where('rating','>=',4)->count() }}
    </div>
    <p class="text-xs text-gray-400 mt-2">Ulasan positif (4 )</p>
  </div>
</div>

{{-- List --}}
<div class="page-card overflow-hidden">
  <div class="px-5 py-4 border-b border-gray-100">
    <h3 class="font-bold text-[#1a0d06]">Semua Ulasan</h3>
  </div>

  <div class="divide-y divide-gray-50">
    @forelse($ratings as $rating)
      <div class="px-5 py-4 flex gap-4">
        {{-- Avatar --}}
        <div class="w-10 h-10 bg-[#f4ebe0] rounded-full flex items-center justify-center font-bold text-[#2b160b] shrink-0">
          {{ strtoupper(substr($rating->user->name ?? '?', 0, 1)) }}
        </div>

        <div class="flex-1">
          <div class="flex items-center gap-3 flex-wrap">
            <span class="font-semibold text-sm text-[#1a0d06]">{{ $rating->user->name ?? '' }}</span>
            <span class="text-yellow-400 text-sm">
              @for($i=1;$i<=5;$i++)
                <i class="fas fa-star{{ $i <= $rating->rating ? '' : '-empty' }}" style="font-size:12px"></i>
              @endfor
            </span>
            <span class="text-xs text-gray-400">{{ $rating->created_at->diffForHumans() }}</span>
          </div>
          <p class="text-xs text-[#c8a87a] font-medium mt-0.5">
            <i class="fas fa-mug-hot mr-1"></i>{{ $rating->product->name ?? '' }}
          </p>
          @if($rating->review)
            <p class="text-sm text-gray-600 mt-1.5 leading-relaxed">{{ $rating->review }}</p>
          @endif
        </div>

        {{-- Delete --}}
        <form action="{{ route('admin.ratings.destroy', $rating) }}" method="POST"
           onsubmit="return confirm('Hapus ulasan ini?')" class="shrink-0">
          @csrf @method('DELETE')
          <button type="submit" class="text-gray-300 hover:text-red-400 transition p-1">
            <i class="fas fa-trash text-xs"></i>
          </button>
        </form>
      </div>
    @empty
      <div class="py-16 text-center text-gray-400">
        <div class="text-4xl mb-2"></div>
        Belum ada ulasan dari pelanggan
      </div>
    @endforelse
  </div>

  @if($ratings->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
      {{ $ratings->links() }}
    </div>
  @endif
</div>
@endsection
