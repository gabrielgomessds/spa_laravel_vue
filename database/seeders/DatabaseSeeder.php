<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $category = Category::factory(10)->create();
        User::factory(5)->has(Product::factory(30)->state(function() use ($category) {
            return ['category_id' => $category->random()->id];
        }))->create();
    }
}
