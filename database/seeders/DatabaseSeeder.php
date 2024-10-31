<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierProductVariant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Variant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        Variant::factory(10)->create();
        $variants = Variant::pluck('id');

        Product::factory(50)->create()->each(function ($product) use ($variants) {
            $product->variants()->attach($variants->random(rand(3,5)));
        });

        Supplier::factory(10)->create()
        // Now for each supplier, add 1-2 products with between 2-3 variants
            ->each(function ($supplier) {
                $randomProducts = Product::get()->random(rand(1,2));
                foreach($randomProducts as $product) {
                    $randomVariants = $product->variants->random(rand(2,3));
                    foreach($randomVariants as $variant) {
                        SupplierProductVariant::create([
                            'supplier_id' => $supplier->id,
                            'product_id' => $product->id,
                            'variant_id' => $variant->id,
                        ]);
                    }
                }
            });




    }
}
