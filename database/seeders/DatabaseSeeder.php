<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create test users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        $cs1 = User::create([
            'name' => 'Customer Service 1',
            'email' => 'cs1@example.com',
            'password' => bcrypt('password123'),
            'role' => 'cs_layer1'
        ]);

        $cs2 = User::create([
            'name' => 'Customer Service 2',
            'email' => 'cs2@example.com',
            'password' => bcrypt('password123'),
            'role' => 'cs_layer2'
        ]);

        $customer = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer'
        ]);

        // Create sample products
        $products = [
            [
                'name' => 'Smartphone X Pro',
                'description' => 'Smartphone flagship dengan kamera 108MP dan baterai 5000mAh',
                'price' => 8999000,
                'stock' => 25,
                'image' => null
            ],
            [
                'name' => 'Laptop Gaming Ultra',
                'description' => 'Laptop gaming dengan RTX 4060 dan processor i7 gen 13',
                'price' => 18999000,
                'stock' => 15,
                'image' => null
            ],
            [
                'name' => 'Smartwatch Series 5',
                'description' => 'Smartwatch dengan EKG, GPS, dan battery life 2 hari',
                'price' => 3499000,
                'stock' => 40,
                'image' => null
            ],
            [
                'name' => 'Headphone Wireless Premium',
                'description' => 'Headphone dengan noise cancellation dan kualitas audio tinggi',
                'price' => 2499000,
                'stock' => 30,
                'image' => null
            ],
            [
                'name' => 'Tablet Pro 12.9"',
                'description' => 'Tablet dengan layar Liquid Retina XDR dan chip M2',
                'price' => 15999000,
                'stock' => 20,
                'image' => null
            ],
            [
                'name' => 'Kamera Mirrorless Profesional',
                'description' => 'Kamera 45MP dengan 8K video recording',
                'price' => 28999000,
                'stock' => 10,
                'image' => null
            ],
            [
                'name' => 'Speaker Bluetooth Portable',
                'description' => 'Speaker waterproof dengan bass yang kuat',
                'price' => 899000,
                'stock' => 50,
                'image' => null
            ],
            [
                'name' => 'Mouse Gaming Wireless',
                'description' => 'Mouse dengan polling rate 1000Hz dan RGB lighting',
                'price' => 599000,
                'stock' => 35,
                'image' => null
            ],
        ];

        $createdProducts = [];
        foreach ($products as $productData) {
            $createdProducts[] = Product::create($productData);
        }

        // Add some products to customer's cart
        Cart::create([
            'user_id' => $customer->id,
            'product_id' => $createdProducts[0]->id,
            'quantity' => 2
        ]);

        Cart::create([
            'user_id' => $customer->id,
            'product_id' => $createdProducts[2]->id,
            'quantity' => 1
        ]);

        Cart::create([
            'user_id' => $customer->id,
            'product_id' => $createdProducts[4]->id,
            'quantity' => 1
        ]);
    }
}