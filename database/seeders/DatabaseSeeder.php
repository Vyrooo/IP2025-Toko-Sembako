<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin350@gmail.com'],
            [
                'name' => 'Administrator',
                'role' => 'admin',
                'password' => Hash::make('iniadmin350'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir350@gmail.com'],
            [
                'name' => 'Kasir Utama',
                'role' => 'kasir',
                'password' => Hash::make('inikasir350'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner350@gmail.com'],
            [
                'name' => 'Owner Toko',
                'role' => 'owner',
                'password' => Hash::make('iniowner350'),
            ]
        );

        $categories = collect(['beras', 'gula', 'minyak', 'mie'])->mapWithKeys(function ($name) {
            $category = Category::firstOrCreate(['name' => ucfirst($name)]);
            return [$name => $category->id];
        });

        $products = [
            ['category' => 'beras', 'name' => 'Beras Anak Lanang 5kg', 'purchase_price' => 75000, 'selling_price' => 77000, 'stock' => 10, 'unit' => 'kg'],
            ['category' => 'gula', 'name' => 'Gula Pasir 1kg', 'purchase_price' => 16000, 'selling_price' => 16500, 'stock' => 36, 'unit' => 'kg'],
            ['category' => 'minyak', 'name' => 'Minyak Goreng 1L', 'purchase_price' => 18500, 'selling_price' => 19500, 'stock' => 7, 'unit' => 'liter'],
            ['category' => 'mie', 'name' => 'Mie Instan Goreng', 'purchase_price' => 2200, 'selling_price' => 3500, 'stock' => 100, 'unit' => 'pcs'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'category_id' => $categories[$product['category']],
                    'purchase_price' => $product['purchase_price'],
                    'selling_price' => $product['selling_price'],
                    'stock' => $product['stock'],
                    'unit' => $product['unit'],
                ]
            );
        }
    }
}
