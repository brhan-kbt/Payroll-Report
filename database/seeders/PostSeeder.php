<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // assign posts to first user (admin)

        $posts = [
            [
                'title' => '12th Grade Entrance Exam in Ethiopia and Kenya to be held in 2025',
                'subtitle' => 'The 12th Grade Entrance Exam (SSLE) is scheduled...',
                'body' => 'Full article about the 12th grade entrance exam...',
                'image' => 'https://images.unsplash.com/photo-1558021212-51b6ecfa0db9?q=80&w=1200&auto=format&fit=crop',
                'category_slug' => 'news',
                'views' => 4700,
                'likes' => 120,
            ],
            [
                'title' => 'iOS 26 Beta 4 Brings Back the Stunning Liquid Glass',
                'subtitle' => 'Apple is getting closer to release iOS 26...',
                'body' => 'Details about iOS 26 Beta 4 features...',
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=1200&auto=format&fit=crop',
                'category_slug' => 'tech',
                'views' => 3200,
                'likes' => 80,
            ],
            [
                'title' => 'Unleash Your Inner Photographer with These Tips',
                'subtitle' => 'Tired of awkward photos? Try this...',
                'body' => 'Guide for photography...',
                'image' => 'https://images.unsplash.com/photo-1473655551229-a39d1a982885?q=80&w=1200&auto=format&fit=crop',
                'category_slug' => 'apps',
                'views' => 1800,
                'likes' => 45,
            ],
            [
                'title' => 'AI, Innovation & National Tech Trends',
                'subtitle' => 'Anthropic offers AI to U.S. government...',
                'body' => 'AI news details...',
                'image' => 'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?q=80&w=1200&auto=format&fit=crop',
                'category_slug' => 'ai',
                'views' => 2600,
                'likes' => 67,
            ],
        ];

        foreach ($posts as $p) {
            $category = Category::where('slug', $p['category_slug'])->first();
            if ($category) {
                Post::create([
                    'title' => $p['title'],
                    'subtitle' => $p['subtitle'],
                    'body' => $p['body'],
                    'image' => $p['image'],
                    'category_id' => $category->id,
                    'user_id' => $user->id,
                    'views' => $p['views'],
                    'likes' => $p['likes'],
                ]);
            }
        }
    }
}
