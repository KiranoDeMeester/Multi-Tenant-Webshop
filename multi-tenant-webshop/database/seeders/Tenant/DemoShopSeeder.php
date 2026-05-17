<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Attribute;
use App\Models\Tenant\AttributeValue;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductVariation;
use App\Models\Tenant\User;
use App\Models\Tenant\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoShopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Shop Owner
        User::updateOrCreate(
            ['email' => 'owner@demo-shop.localhost'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Set Theme Settings
        $settings = [
            'theme_primary_color' => '#18181b', // Zinc 900
            'theme_secondary_color' => '#4f46e5', // Indigo 600
            'theme_accent_color' => '#f59e0b', // Amber 500
            'theme_font_family' => 'Outfit',
            'layout_type' => 'modern',
            'show_hero_banner' => true,
            'hero_image_url' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=1600&q=80',
            'shop_name' => 'Minimalist Design Store',
            'shop_description' => 'Hoogwaardige producten voor de moderne minimalist.',
            'about_us_content' => 'Wij zijn een passievol team van designers die geloven dat minder meer is. Onze missie is om rust en schoonheid te brengen in elk interieur door middel van zorgvuldig geselecteerde objecten.',
            'privacy_policy_content' => 'Jouw privacy is belangrijk voor ons. Wij verzamelen alleen de hoogst noodzakelijke gegevens om je bestelling te verwerken en je ervaring te verbeteren.',
            'terms_conditions_content' => 'Onze algemene voorwaarden zijn simpel en eerlijk. We streven naar 100% klanttevredenheid bij elke aankoop.',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // 3. Create Categories
        $categories = [
            [
                'name' => 'Meubels',
                'slug' => 'meubels',
                'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Verlichting',
                'slug' => 'verlichting',
                'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Accessoires',
                'slug' => 'accessoires',
                'image' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], [
                'name' => $cat['name'],
                'image' => $cat['image'] ?? null,
            ]);
        }

        $meubels = Category::where('slug', 'meubels')->first();
        $verlichting = Category::where('slug', 'verlichting')->first();
        $accessoires = Category::where('slug', 'accessoires')->first();

        // 4. Create Products
        
        // Product 1: Designer Chair (Variations)
        $chair = Product::create([
            'category_id' => $meubels->id,
            'name' => 'Eames Lounge Chair Replica',
            'slug' => 'eames-lounge-chair',
            'sku' => 'CHAIR-EAMES',
            'description' => 'De ultieme klassieker in modern design. Vervaardigd met oog voor elk detail, van de houtnerven tot het premium leer.',
            'price' => 1249.00,
            'stock' => 0,
        ]);

        $colorAttr = Attribute::updateOrCreate(['name' => 'Kleur']);
        $black = AttributeValue::updateOrCreate(['attribute_id' => $colorAttr->id, 'value' => 'Zwart']);
        $walnut = AttributeValue::updateOrCreate(['attribute_id' => $colorAttr->id, 'value' => 'Walnoot']);

        $varBlack = ProductVariation::create([
            'product_id' => $chair->id,
            'sku' => 'CHAIR-EAMES-BLACK',
            'price' => 1249.00,
            'stock' => 5,
        ]);
        $varBlack->attributeValues()->sync([$black->id]);

        $varWalnut = ProductVariation::create([
            'product_id' => $chair->id,
            'sku' => 'CHAIR-EAMES-WALNUT',
            'price' => 1399.00,
            'stock' => 3,
        ]);
        $varWalnut->attributeValues()->sync([$walnut->id]);

        // Product 2: Floor Lamp
        Product::create([
            'category_id' => $verlichting->id,
            'name' => 'Arc Vloerlamp Geborsteld Staal',
            'slug' => 'arc-vloerlamp',
            'sku' => 'LAMP-ARC-01',
            'description' => 'Een elegante booglamp die perfect boven de bank of eettafel past. Minimalistisch design met maximale impact.',
            'price' => 189.99,
            'stock' => 12,
        ]);

        // Product 3: Ceramic Vase
        Product::create([
            'category_id' => $accessoires->id,
            'name' => 'Organische Keramiek Vaas',
            'slug' => 'keramiek-vaas',
            'sku' => 'ACC-VASE-01',
            'description' => 'Handgemaakte keramieken vaas met een unieke organische vorm. Een kunstobject op zich.',
            'price' => 45.00,
            'stock' => 20,
        ]);

        // Product 4: Minimalist Clock
        Product::create([
            'category_id' => $accessoires->id,
            'name' => 'Betonnen Wandklok',
            'slug' => 'betonnen-wandklok',
            'sku' => 'ACC-CLOCK-01',
            'description' => 'Rauwe materialen ontmoeten verfijnd design. Deze klok is een statement voor elk modern interieur.',
            'price' => 79.00,
            'stock' => 8,
        ]);

        // Product 5: Pendant Light
        Product::create([
            'category_id' => $verlichting->id,
            'name' => 'Glazen Hanglamp Smoke',
            'slug' => 'hanglamp-smoke',
            'sku' => 'LAMP-SMOKE-01',
            'description' => 'Sfeervolle verlichting door getint glas. Creëer een warme ambiance in de slaapkamer of hal.',
            'price' => 115.00,
            'stock' => 15,
        ]);

        // Product 6: Velvet Cushion
        Product::create([
            'category_id' => $accessoires->id,
            'name' => 'Fluwelen Sierkussen Olijfgroen',
            'slug' => 'kussen-olijfgroen',
            'sku' => 'ACC-CUSHION-01',
            'description' => 'Zacht fluweel in een rijke olijfgroene kleur. Voeg textuur en comfort toe aan je zithoek.',
            'price' => 29.95,
            'stock' => 50,
        ]);
    }
}
