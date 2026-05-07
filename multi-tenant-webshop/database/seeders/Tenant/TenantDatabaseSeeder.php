<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Attribute;
use App\Models\Tenant\AttributeValue;
use App\Models\Tenant\Category;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductVariation;
use App\Models\Tenant\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the tenant's database.
     */
    public function run(): void
    {
        // 1. Create a Shop Owner (for /dashboard)
        User::updateOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Winkel Eigenaar',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Create a Test Customer (for Storefront)
        Customer::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test Klant',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Categories
        $electronics = Category::updateOrCreate(['slug' => 'electronics'], ['name' => 'Elektronica']);
        $clothing = Category::updateOrCreate(['slug' => 'clothing'], ['name' => 'Kleding']);

        // 3. Create Simple Product
        Product::updateOrCreate(
            ['slug' => 'demo-smartphone'],
            [
                'category_id' => $electronics->id,
                'name' => 'Demo Smartphone',
                'sku' => 'SMART-001',
                'description' => 'Een geweldige smartphone voor demo doeleinden.',
                'price' => 599.99,
                'stock' => 15,
            ]
        );

        // 4. Create Product with Variations
        $tshirt = Product::updateOrCreate(
            ['slug' => 'demo-tshirt'],
            [
                'category_id' => $clothing->id,
                'name' => 'Demo T-Shirt',
                'sku' => 'TSHIRT-VAR',
                'description' => 'Een comfortabel shirt in verschillende maten.',
                'price' => 24.99,
                'stock' => 0, // Stock is managed via variations
            ]
        );

        $sizeAttr = Attribute::updateOrCreate(['name' => 'Maat']);
        $sizeM = AttributeValue::updateOrCreate(['attribute_id' => $sizeAttr->id, 'value' => 'Medium']);
        $sizeL = AttributeValue::updateOrCreate(['attribute_id' => $sizeAttr->id, 'value' => 'Large']);

        $varM = ProductVariation::updateOrCreate(
            ['sku' => 'TSHIRT-M'],
            [
                'product_id' => $tshirt->id,
                'price' => 24.99,
                'stock' => 10,
            ]
        );
        $varM->attributeValues()->sync([$sizeM->id]);

        $varL = ProductVariation::updateOrCreate(
            ['sku' => 'TSHIRT-L'],
            [
                'product_id' => $tshirt->id,
                'price' => 29.99, // Extra cost for Large
                'stock' => 5,
            ]
        );
        $varL->attributeValues()->sync([$sizeL->id]);
    }
}
