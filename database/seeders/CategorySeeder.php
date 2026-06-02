<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Kopi',
                'slug'        => 'kopi',
                'description' => 'Minuman berbahan dasar kopi pilihan — espresso, latte, cappuccino, dan lainnya.',
            ],
            [
                'name'        => 'Non-Kopi',
                'slug'        => 'non-kopi',
                'description' => 'Minuman tanpa kopi — teh, coklat, matcha, frappé, dan minuman segar lainnya.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $this->command->info('✅ Kategori Kopi & Non-Kopi berhasil dibuat.');
    }
}
