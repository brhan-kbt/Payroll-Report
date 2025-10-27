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
            ['slug' => 'tools', 'name' => 'Tools', 'image' => null],
            ['slug' => 'apps', 'name' => 'Apps', 'image' => null],
            ['slug' => 'ai', 'name' => 'AI', 'image' => null],
            ['slug' => 'news', 'name' => 'News', 'image' => null],
            ['slug' => 'tech', 'name' => 'Technology', 'image' => null],
            ['slug' => 'business', 'name' => 'Business', 'image' => null],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'slug' => $cat['slug'],
                'name' => $cat['name'],
                'image' => $cat['image'],
                'parent_id' => null,
            ]);
        }
    }
}
