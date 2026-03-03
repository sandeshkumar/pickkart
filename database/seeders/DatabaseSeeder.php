<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ---------------------------------------------------------------
        // 1. ROLES
        // ---------------------------------------------------------------
        $roles = ['super-admin', 'admin', 'seller', 'customer'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ---------------------------------------------------------------
        // 2. SUPER ADMIN USER
        // ---------------------------------------------------------------
        $superAdmin = User::create([
            'name'              => 'Super Admin',
            'email'             => 'admin@pickkart.com',
            'password'          => 'password',
            'phone'             => '9000000001',
            'email_verified_at' => now(),
            'status'            => 'active',
        ]);
        $superAdmin->assignRole('super-admin');

        // ---------------------------------------------------------------
        // 3. SELLER USER + SELLER PROFILE
        // ---------------------------------------------------------------
        $sellerUser = User::create([
            'name'              => 'Test Seller',
            'email'             => 'seller@pickkart.com',
            'password'          => 'password',
            'phone'             => '9000000002',
            'email_verified_at' => now(),
            'status'            => 'active',
        ]);
        $sellerUser->assignRole('seller');

        $seller = Seller::create([
            'user_id'           => $sellerUser->id,
            'store_name'        => 'ZedMart Official Store',
            'store_slug'        => 'zedmart-official-store',
            'store_description' => 'Your one-stop shop for quality products across every category. Fast shipping and excellent customer support.',
            'business_email'    => 'business@zedmart.com',
            'business_phone'    => '9000000010',
            'business_address'  => '123 Market Street, Mumbai, Maharashtra 400001',
            'gst_number'        => '27AABCU9603R1ZM',
            'pan_number'        => 'AABCU9603R',
            'bank_name'         => 'HDFC Bank',
            'bank_account_number' => '50100123456789',
            'bank_ifsc_code'    => 'HDFC0001234',
            'commission_rate'   => 8.00,
            'status'            => 'approved',
            'kyc_status'        => 'verified',
            'rating'            => 4.50,
        ]);

        // ---------------------------------------------------------------
        // 4. CUSTOMER USER
        // ---------------------------------------------------------------
        $customer = User::create([
            'name'              => 'Test Customer',
            'email'             => 'customer@pickkart.com',
            'password'          => 'password',
            'phone'             => '9000000003',
            'email_verified_at' => now(),
            'status'            => 'active',
        ]);
        $customer->assignRole('customer');

        // ---------------------------------------------------------------
        // 5. BRANDS
        // ---------------------------------------------------------------
        $brands = [];
        $brandData = [
            ['name' => 'Samsung',   'description' => 'Global leader in electronics, mobile phones, and home appliances.',       'website' => 'https://www.samsung.com'],
            ['name' => 'Nike',      'description' => 'World-renowned sportswear and athletic footwear brand.',                  'website' => 'https://www.nike.com'],
            ['name' => 'IKEA',      'description' => 'Swedish-origin furnishing company known for functional, affordable design.', 'website' => 'https://www.ikea.com'],
            ['name' => 'Bosch',     'description' => 'German engineering company providing tools, appliances, and technology.',  'website' => 'https://www.bosch.com'],
            ['name' => 'Penguin Books', 'description' => 'Iconic publishing house with a vast catalogue spanning every genre.', 'website' => 'https://www.penguin.com'],
        ];

        foreach ($brandData as $b) {
            $brands[$b['name']] = Brand::create([
                'name'        => $b['name'],
                'slug'        => Str::slug($b['name']),
                'description' => $b['description'],
                'logo'        => 'https://placehold.co/200x100/f0f0f0/333333?text=' . urlencode($b['name']),
                'website'     => $b['website'],
                'is_active'   => true,
            ]);
        }

        // ---------------------------------------------------------------
        // 6. TOP-LEVEL CATEGORIES + SUBCATEGORIES
        // ---------------------------------------------------------------
        $categoryTree = [
            'Electronics' => [
                'description' => 'Laptops, smartphones, TVs, audio systems, and all things tech.',
                'icon' => 'fas fa-tv',
                'children' => ['Mobile Phones', 'Laptops & Computers', 'Audio & Headphones', 'Televisions'],
            ],
            'Beauty' => [
                'description' => 'Skincare, haircare, makeup, and personal grooming essentials.',
                'icon' => 'fas fa-spa',
                'children' => ['Skincare', 'Haircare', 'Makeup', 'Fragrances'],
            ],
            'Toys' => [
                'description' => 'Fun and educational toys for children of all ages.',
                'icon' => 'fas fa-puzzle-piece',
                'children' => ['Action Figures', 'Board Games', 'Building Sets', 'Dolls & Plush'],
            ],
            'Gadgets' => [
                'description' => 'Smart devices, wearables, drones, and innovative tech accessories.',
                'icon' => 'fas fa-microchip',
                'children' => ['Smartwatches', 'Drones', 'VR Headsets'],
            ],
            'Sports' => [
                'description' => 'Equipment, apparel, and accessories for every sport and outdoor activity.',
                'icon' => 'fas fa-futbol',
                'children' => ['Fitness Equipment', 'Outdoor Recreation', 'Team Sports', 'Cycling'],
            ],
            'Books' => [
                'description' => 'Bestsellers, textbooks, novels, and non-fiction across every genre.',
                'icon' => 'fas fa-book',
                'children' => ['Fiction', 'Non-Fiction', 'Academic & Textbooks'],
            ],
            'Gardening' => [
                'description' => 'Seeds, tools, planters, and outdoor living supplies for your garden.',
                'icon' => 'fas fa-seedling',
                'children' => ['Plants & Seeds', 'Garden Tools', 'Pots & Planters'],
            ],
            'Furniture' => [
                'description' => 'Stylish and functional furniture for every room in your home.',
                'icon' => 'fas fa-couch',
                'children' => ['Living Room', 'Bedroom', 'Office Furniture', 'Outdoor Furniture'],
            ],
            'Shoes' => [
                'description' => 'Casual, formal, athletic, and specialty footwear for men and women.',
                'icon' => 'fas fa-shoe-prints',
                'children' => ['Men\'s Shoes', 'Women\'s Shoes', 'Sports Shoes'],
            ],
            'Clothing' => [
                'description' => 'Trendy apparel for men, women, and kids across all seasons.',
                'icon' => 'fas fa-tshirt',
                'children' => ['Men\'s Clothing', 'Women\'s Clothing', 'Kids\' Clothing', 'Winterwear'],
            ],
            'Tools' => [
                'description' => 'Hand tools, power tools, and workshop essentials for every project.',
                'icon' => 'fas fa-tools',
                'children' => ['Hand Tools', 'Power Tools', 'Tool Storage'],
            ],
            'Homemade' => [
                'description' => 'Handcrafted, artisan, and locally sourced homemade goods.',
                'icon' => 'fas fa-hand-holding-heart',
                'children' => ['Handmade Candles', 'Artisan Food', 'Handcrafted Decor'],
            ],
        ];

        $categories = [];   // name => Category model
        $sortOrder = 0;
        foreach ($categoryTree as $parentName => $meta) {
            $parent = Category::create([
                'name'             => $parentName,
                'slug'             => Str::slug($parentName),
                'description'      => $meta['description'],
                'icon'             => $meta['icon'],
                'image'            => 'https://placehold.co/200x200/e0e7ff/4338ca?text=' . urlencode($parentName),
                'parent_id'        => null,
                'sort_order'       => $sortOrder++,
                'is_active'        => true,
                'meta_title'       => $parentName . ' - Shop Online at PickKart',
                'meta_description' => $meta['description'],
            ]);
            $categories[$parentName] = $parent;

            $childSort = 0;
            foreach ($meta['children'] as $childName) {
                $child = Category::create([
                    'name'             => $childName,
                    'slug'             => Str::slug($childName),
                    'description'      => "Browse our curated selection of {$childName}.",
                    'image'            => 'https://placehold.co/200x200/dbeafe/3b82f6?text=' . urlencode($childName),
                    'parent_id'        => $parent->id,
                    'sort_order'       => $childSort++,
                    'is_active'        => true,
                    'meta_title'       => $childName . ' - ' . $parentName . ' | PickKart',
                    'meta_description' => "Shop the best {$childName} in {$parentName} at PickKart.",
                ]);
                $categories[$childName] = $child;
            }
        }

        // ---------------------------------------------------------------
        // 7. CATEGORY-SPECIFIC PRODUCT ATTRIBUTES
        // ---------------------------------------------------------------
        $attributeMap = [
            'Electronics' => [
                ['name' => 'Brand',     'slug' => 'electronics-brand',    'type' => 'select',  'is_filterable' => true,  'is_required' => true],
                ['name' => 'Warranty',  'slug' => 'electronics-warranty', 'type' => 'select',  'is_filterable' => true,  'is_required' => false],
                ['name' => 'Voltage',   'slug' => 'electronics-voltage',  'type' => 'text',    'is_filterable' => false, 'is_required' => false],
            ],
            'Clothing' => [
                ['name' => 'Size',      'slug' => 'clothing-size',     'type' => 'select',  'is_filterable' => true,  'is_required' => true],
                ['name' => 'Color',     'slug' => 'clothing-color',    'type' => 'select',  'is_filterable' => true,  'is_required' => true],
                ['name' => 'Material',  'slug' => 'clothing-material', 'type' => 'select',  'is_filterable' => true,  'is_required' => false],
            ],
            'Shoes' => [
                ['name' => 'Shoe Size', 'slug' => 'shoes-size',     'type' => 'select',  'is_filterable' => true,  'is_required' => true],
                ['name' => 'Color',     'slug' => 'shoes-color',    'type' => 'select',  'is_filterable' => true,  'is_required' => true],
                ['name' => 'Sole Type', 'slug' => 'shoes-sole-type','type' => 'select',  'is_filterable' => true,  'is_required' => false],
            ],
            'Books' => [
                ['name' => 'Author',    'slug' => 'books-author',   'type' => 'text',    'is_filterable' => true,  'is_required' => true],
                ['name' => 'ISBN',      'slug' => 'books-isbn',     'type' => 'text',    'is_filterable' => false, 'is_required' => false],
                ['name' => 'Language',  'slug' => 'books-language',  'type' => 'select',  'is_filterable' => true,  'is_required' => false],
            ],
            'Furniture' => [
                ['name' => 'Material',  'slug' => 'furniture-material',  'type' => 'select',  'is_filterable' => true,  'is_required' => true],
                ['name' => 'Color',     'slug' => 'furniture-color',     'type' => 'select',  'is_filterable' => true,  'is_required' => false],
                ['name' => 'Assembly Required', 'slug' => 'furniture-assembly', 'type' => 'boolean', 'is_filterable' => true,  'is_required' => false],
            ],
            'Sports' => [
                ['name' => 'Sport Type',  'slug' => 'sports-sport-type',  'type' => 'select', 'is_filterable' => true, 'is_required' => false],
                ['name' => 'Skill Level', 'slug' => 'sports-skill-level', 'type' => 'select', 'is_filterable' => true, 'is_required' => false],
            ],
            'Beauty' => [
                ['name' => 'Skin Type',    'slug' => 'beauty-skin-type',   'type' => 'select', 'is_filterable' => true,  'is_required' => false],
                ['name' => 'Ingredient',   'slug' => 'beauty-ingredient',  'type' => 'text',   'is_filterable' => false, 'is_required' => false],
                ['name' => 'Volume (ml)',  'slug' => 'beauty-volume',      'type' => 'number', 'is_filterable' => true,  'is_required' => false],
            ],
            'Tools' => [
                ['name' => 'Power Source', 'slug' => 'tools-power-source', 'type' => 'select',  'is_filterable' => true,  'is_required' => false],
                ['name' => 'Weight (kg)',  'slug' => 'tools-weight',       'type' => 'number',  'is_filterable' => false, 'is_required' => false],
            ],
            'Gadgets' => [
                ['name' => 'Connectivity', 'slug' => 'gadgets-connectivity', 'type' => 'select',  'is_filterable' => true,  'is_required' => false],
                ['name' => 'Battery Life', 'slug' => 'gadgets-battery-life', 'type' => 'text',    'is_filterable' => false, 'is_required' => false],
            ],
            'Toys' => [
                ['name' => 'Age Group',   'slug' => 'toys-age-group',  'type' => 'select',  'is_filterable' => true,  'is_required' => true],
                ['name' => 'Material',    'slug' => 'toys-material',   'type' => 'select',  'is_filterable' => true,  'is_required' => false],
            ],
            'Gardening' => [
                ['name' => 'Plant Type',  'slug' => 'gardening-plant-type', 'type' => 'select', 'is_filterable' => true,  'is_required' => false],
                ['name' => 'Sunlight',    'slug' => 'gardening-sunlight',   'type' => 'select', 'is_filterable' => true,  'is_required' => false],
            ],
            'Homemade' => [
                ['name' => 'Handmade By', 'slug' => 'homemade-artisan',    'type' => 'text',   'is_filterable' => false, 'is_required' => false],
                ['name' => 'Ingredients', 'slug' => 'homemade-ingredients', 'type' => 'text',   'is_filterable' => false, 'is_required' => false],
            ],
        ];

        $attributes = []; // "category-slug" => [ ProductAttribute, ... ]
        foreach ($attributeMap as $catName => $attrs) {
            $cat = $categories[$catName];
            $attrSort = 0;
            foreach ($attrs as $a) {
                $pa = ProductAttribute::create([
                    'name'          => $a['name'],
                    'slug'          => $a['slug'],
                    'type'          => $a['type'],
                    'is_filterable' => $a['is_filterable'],
                    'is_required'   => $a['is_required'],
                    'category_id'   => $cat->id,
                    'sort_order'    => $attrSort++,
                ]);
                $attributes[$catName][] = $pa;
            }
        }

        // ---------------------------------------------------------------
        // 8. SAMPLE PRODUCTS (2-3 per top-level category)
        // ---------------------------------------------------------------
        $now = now();
        $productDefs = [
            // --- Electronics ---
            [
                'cat' => 'Mobile Phones', 'brand' => 'Samsung',
                'name' => 'Samsung Galaxy S25 Ultra',
                'short_description' => 'Flagship smartphone with 200 MP camera and Snapdragon 8 Gen 4 processor.',
                'description' => '<p>Experience the pinnacle of mobile technology with the Samsung Galaxy S25 Ultra. Featuring a stunning 6.9-inch Dynamic AMOLED display, the powerful Snapdragon 8 Gen 4 chipset, and a groundbreaking 200 MP camera system, this device redefines what a smartphone can do. The 5000 mAh battery keeps you powered all day, and the built-in S Pen offers precision note-taking and creative tools.</p>',
                'price' => 1299.99, 'compare_at_price' => 1399.99, 'cost_price' => 850.00,
                'sku' => 'ELEC-SAM-S25U', 'stock' => 50, 'weight' => 0.23, 'is_featured' => true,
                'attrs' => ['Samsung', '2 Years', '5V/3A'],
                'parent_cat' => 'Electronics',
            ],
            [
                'cat' => 'Laptops & Computers', 'brand' => 'Samsung',
                'name' => 'Samsung Galaxy Book 4 Pro',
                'short_description' => 'Ultra-thin laptop with Intel Core Ultra 7 and 16-inch AMOLED display.',
                'description' => '<p>Work and create without compromise on the Samsung Galaxy Book 4 Pro. Its 16-inch 3K AMOLED display delivers breathtaking visuals, while the Intel Core Ultra 7 processor handles demanding workflows effortlessly. Weighing just 1.55 kg, it is the perfect companion for professionals on the move. Enjoy up to 21 hours of battery life and seamless Galaxy ecosystem integration.</p>',
                'price' => 1599.99, 'compare_at_price' => 1799.99, 'cost_price' => 1100.00,
                'sku' => 'ELEC-SAM-GB4P', 'stock' => 30, 'weight' => 1.55, 'is_featured' => true,
                'attrs' => ['Samsung', '1 Year', '100-240V'],
                'parent_cat' => 'Electronics',
            ],
            [
                'cat' => 'Audio & Headphones', 'brand' => 'Samsung',
                'name' => 'Samsung Galaxy Buds 3 Pro',
                'short_description' => 'Premium true wireless earbuds with intelligent ANC and Hi-Fi 360 Audio.',
                'description' => '<p>Immerse yourself in studio-quality sound with the Galaxy Buds 3 Pro. Equipped with a dual-driver system, adaptive active noise cancellation, and Hi-Fi 360 Audio, these earbuds deliver an unparalleled listening experience. The blade-light design ensures all-day comfort, and the IPX7 water resistance makes them ideal for workouts and rainy commutes.</p>',
                'price' => 249.99, 'compare_at_price' => 279.99, 'cost_price' => 120.00,
                'sku' => 'ELEC-SAM-GB3P', 'stock' => 100, 'weight' => 0.05, 'is_featured' => false,
                'attrs' => ['Samsung', '1 Year', '5V/1A'],
                'parent_cat' => 'Electronics',
            ],

            // --- Beauty ---
            [
                'cat' => 'Skincare', 'brand' => 'Bosch',
                'name' => 'HydraGlow Vitamin C Serum',
                'short_description' => 'Brightening serum with 20% Vitamin C for radiant, even-toned skin.',
                'description' => '<p>Transform your complexion with our HydraGlow Vitamin C Serum. Packed with 20% L-Ascorbic Acid, Hyaluronic Acid, and Vitamin E, this lightweight serum targets dark spots, fine lines, and dullness. Dermatologist-tested and suitable for all skin types, it absorbs quickly without any greasy residue. Use daily for visibly brighter, firmer skin in as little as two weeks.</p>',
                'price' => 34.99, 'compare_at_price' => 44.99, 'cost_price' => 12.00,
                'sku' => 'BEAU-HG-VCS01', 'stock' => 200, 'weight' => 0.08, 'is_featured' => true,
                'attrs' => ['All Skin Types', 'Vitamin C, Hyaluronic Acid', '30'],
                'parent_cat' => 'Beauty',
            ],
            [
                'cat' => 'Makeup', 'brand' => 'Bosch',
                'name' => 'Velvet Matte Lipstick Collection',
                'short_description' => 'Long-lasting matte lipstick available in 12 rich shades.',
                'description' => '<p>Express yourself with the Velvet Matte Lipstick Collection. Each shade is formulated with moisturizing jojoba oil and beeswax to deliver a velvety matte finish that lasts up to 12 hours without drying your lips. Cruelty-free and paraben-free. Available in a range of shades from classic reds to trendy nudes.</p>',
                'price' => 18.99, 'compare_at_price' => null, 'cost_price' => 5.00,
                'sku' => 'BEAU-VM-LIP01', 'stock' => 300, 'weight' => 0.04, 'is_featured' => false,
                'attrs' => ['All Skin Types', 'Jojoba Oil, Beeswax', '4'],
                'parent_cat' => 'Beauty',
            ],

            // --- Toys ---
            [
                'cat' => 'Building Sets', 'brand' => 'Bosch',
                'name' => 'Ultimate City Builder 1200-Piece Set',
                'short_description' => 'Creative building set with 1200 pieces for constructing an entire cityscape.',
                'description' => '<p>Unleash your child\'s imagination with the Ultimate City Builder Set. This 1200-piece collection includes buildings, vehicles, minifigures, and landscaping elements to create a vibrant city. Compatible with major building block brands, it encourages spatial reasoning, problem-solving, and hours of screen-free play. Recommended for ages 8 and up.</p>',
                'price' => 79.99, 'compare_at_price' => 99.99, 'cost_price' => 30.00,
                'sku' => 'TOYS-UCB-1200', 'stock' => 60, 'weight' => 1.80, 'is_featured' => true,
                'attrs' => ['8-12 Years', 'ABS Plastic'],
                'parent_cat' => 'Toys',
            ],
            [
                'cat' => 'Board Games', 'brand' => 'Bosch',
                'name' => 'Strategy Masters Board Game',
                'short_description' => 'Engaging strategy game for 2-6 players with modular game board.',
                'description' => '<p>Challenge friends and family with Strategy Masters, a dynamic board game that combines resource management, territory control, and diplomatic negotiation. Each playthrough is unique thanks to the modular hex-tile board. Games last 60-90 minutes, making it perfect for game nights. Ages 10 and up.</p>',
                'price' => 44.99, 'compare_at_price' => 54.99, 'cost_price' => 15.00,
                'sku' => 'TOYS-SMB-001', 'stock' => 80, 'weight' => 1.20, 'is_featured' => false,
                'attrs' => ['10+ Years', 'Cardboard, Wood'],
                'parent_cat' => 'Toys',
            ],

            // --- Gadgets ---
            [
                'cat' => 'Smartwatches', 'brand' => 'Samsung',
                'name' => 'Samsung Galaxy Watch 7',
                'short_description' => 'Advanced health monitoring smartwatch with dual-frequency GPS.',
                'description' => '<p>Stay on top of your health and fitness with the Samsung Galaxy Watch 7. Featuring an advanced BioActive sensor for heart rate, blood pressure, and body composition analysis, plus dual-frequency GPS for precision tracking. The Wear OS interface gives you access to thousands of apps, and the 2-day battery ensures you never miss a beat.</p>',
                'price' => 349.99, 'compare_at_price' => 399.99, 'cost_price' => 180.00,
                'sku' => 'GADG-SAM-GW7', 'stock' => 45, 'weight' => 0.04, 'is_featured' => true,
                'attrs' => ['Bluetooth, Wi-Fi, LTE', '48 hours'],
                'parent_cat' => 'Gadgets',
            ],
            [
                'cat' => 'Drones', 'brand' => 'Bosch',
                'name' => 'SkyVision Pro 4K Drone',
                'short_description' => 'Foldable drone with 4K HDR camera, obstacle avoidance, and 40-min flight time.',
                'description' => '<p>Capture stunning aerial footage with the SkyVision Pro 4K Drone. Its 3-axis gimbal-stabilized camera shoots 4K HDR video and 48 MP photos. Intelligent flight modes like ActiveTrack, Waypoints, and Hyperlapse make cinematic shots effortless. Foldable design fits in a backpack, and 40-minute flight time means more time in the air.</p>',
                'price' => 699.99, 'compare_at_price' => 849.99, 'cost_price' => 350.00,
                'sku' => 'GADG-SVP-4KD', 'stock' => 20, 'weight' => 0.75, 'is_featured' => false,
                'attrs' => ['Wi-Fi, GPS', '40 minutes'],
                'parent_cat' => 'Gadgets',
            ],

            // --- Sports ---
            [
                'cat' => 'Fitness Equipment', 'brand' => 'Nike',
                'name' => 'ProFlex Adjustable Dumbbell Set (5-50 lbs)',
                'short_description' => 'Space-saving adjustable dumbbells replacing 15 pairs of weights.',
                'description' => '<p>Transform your home gym with the ProFlex Adjustable Dumbbell Set. A simple twist of the dial lets you switch between 5 and 50 lbs in 5-lb increments, replacing 15 pairs of traditional dumbbells. The ergonomic grip and durable steel construction ensure a comfortable, safe workout every time.</p>',
                'price' => 299.99, 'compare_at_price' => 349.99, 'cost_price' => 150.00,
                'sku' => 'SPRT-PF-DBS50', 'stock' => 35, 'weight' => 23.00, 'is_featured' => true,
                'attrs' => ['Weightlifting', 'All Levels'],
                'parent_cat' => 'Sports',
            ],
            [
                'cat' => 'Cycling', 'brand' => 'Nike',
                'name' => 'TrailBlazer Mountain Bike 29"',
                'short_description' => 'Full-suspension mountain bike with 29-inch wheels and hydraulic disc brakes.',
                'description' => '<p>Conquer any trail with the TrailBlazer Mountain Bike. Featuring a lightweight aluminium frame, 120 mm full suspension, Shimano 12-speed drivetrain, and hydraulic disc brakes, this bike delivers confidence-inspiring performance on rugged terrain. The 29-inch wheels roll over obstacles with ease, while the dropper seatpost adapts to climbing and descending.</p>',
                'price' => 1199.99, 'compare_at_price' => 1399.99, 'cost_price' => 650.00,
                'sku' => 'SPRT-TB-MTB29', 'stock' => 15, 'weight' => 13.50, 'is_featured' => false,
                'attrs' => ['Cycling', 'Intermediate'],
                'parent_cat' => 'Sports',
            ],

            // --- Books ---
            [
                'cat' => 'Fiction', 'brand' => 'Penguin Books',
                'name' => 'The Midnight Library',
                'short_description' => 'A novel about the choices that go into a life well lived, by Matt Haig.',
                'description' => '<p>Between life and death there is a library, and within that library, the shelves go on forever. Every book provides a chance to try another life you could have lived. Nora Seed finds herself in the Midnight Library, where she can live as many lives as she wishes. But will any of them be enough? A thought-provoking, uplifting novel about regret, hope, and second chances.</p>',
                'price' => 14.99, 'compare_at_price' => 19.99, 'cost_price' => 4.00,
                'sku' => 'BOOK-ML-MH01', 'stock' => 150, 'weight' => 0.32, 'is_featured' => true,
                'attrs' => ['Matt Haig', '978-0525559474', 'English'],
                'parent_cat' => 'Books',
            ],
            [
                'cat' => 'Non-Fiction', 'brand' => 'Penguin Books',
                'name' => 'Atomic Habits',
                'short_description' => 'Proven framework for building good habits and breaking bad ones, by James Clear.',
                'description' => '<p>No matter your goals, Atomic Habits offers a proven framework for improving every day. James Clear, one of the world\'s leading experts on habit formation, reveals practical strategies that will teach you how to form good habits, break bad ones, and master the tiny behaviours that lead to remarkable results. This book will reshape how you think about progress and success.</p>',
                'price' => 16.99, 'compare_at_price' => 22.99, 'cost_price' => 5.00,
                'sku' => 'BOOK-AH-JC01', 'stock' => 200, 'weight' => 0.34, 'is_featured' => true,
                'attrs' => ['James Clear', '978-0735211292', 'English'],
                'parent_cat' => 'Books',
            ],

            // --- Gardening ---
            [
                'cat' => 'Plants & Seeds', 'brand' => 'Bosch',
                'name' => 'Organic Herb Garden Starter Kit',
                'short_description' => 'Complete kit with 10 herb seed varieties, biodegradable pots, and organic soil.',
                'description' => '<p>Start your own herb garden with this all-inclusive starter kit. Includes seeds for basil, cilantro, parsley, mint, rosemary, thyme, oregano, chives, dill, and sage, along with 10 biodegradable pots and premium organic potting soil. Detailed growing guides help beginners achieve a bountiful harvest. Perfect for kitchens, balconies, and windowsills.</p>',
                'price' => 29.99, 'compare_at_price' => 39.99, 'cost_price' => 10.00,
                'sku' => 'GARD-OHG-KIT01', 'stock' => 70, 'weight' => 0.90, 'is_featured' => false,
                'attrs' => ['Herbs', 'Full Sun to Partial Shade'],
                'parent_cat' => 'Gardening',
            ],
            [
                'cat' => 'Garden Tools', 'brand' => 'Bosch',
                'name' => 'Bosch EasyPrune Cordless Secateurs',
                'short_description' => 'Battery-powered pruning shears with micro-electronic power assist.',
                'description' => '<p>Make pruning effortless with the Bosch EasyPrune. The integrated 3.6 V lithium-ion battery provides power-assisted cutting through branches up to 25 mm thick. An LED charge indicator, ergonomic soft-grip handle, and ultra-lightweight 490 g design reduce hand fatigue. Includes a micro USB charging cable and blade cover.</p>',
                'price' => 64.99, 'compare_at_price' => 79.99, 'cost_price' => 30.00,
                'sku' => 'GARD-BSH-EP01', 'stock' => 40, 'weight' => 0.49, 'is_featured' => false,
                'attrs' => ['Shrubs & Hedges', 'Full Sun'],
                'parent_cat' => 'Gardening',
            ],

            // --- Furniture ---
            [
                'cat' => 'Office Furniture', 'brand' => 'IKEA',
                'name' => 'ErgoMax Mesh Office Chair',
                'short_description' => 'Ergonomic mesh-back chair with lumbar support and adjustable armrests.',
                'description' => '<p>Work in comfort all day with the ErgoMax Mesh Office Chair. The breathable mesh backrest provides excellent ventilation, while the adjustable lumbar support, seat height, tilt tension, and 3D armrests let you customise the fit to your body. The heavy-duty nylon base supports up to 150 kg, and smooth-rolling casters glide on any floor surface.</p>',
                'price' => 349.99, 'compare_at_price' => 449.99, 'cost_price' => 160.00,
                'sku' => 'FURN-EM-OC01', 'stock' => 25, 'weight' => 18.00, 'is_featured' => true,
                'attrs' => ['Mesh, Nylon, Steel', 'Black', 'true'],
                'parent_cat' => 'Furniture',
            ],
            [
                'cat' => 'Living Room', 'brand' => 'IKEA',
                'name' => 'Nordic Comfort 3-Seater Sofa',
                'short_description' => 'Scandinavian-design sofa with removable, washable linen covers.',
                'description' => '<p>Bring Scandinavian elegance into your living room with the Nordic Comfort Sofa. Solid birch legs, high-density foam cushions, and removable linen-blend covers create a sofa that is both stylish and practical. The wide seating area comfortably fits three adults, and the low-profile silhouette works beautifully in compact and spacious rooms alike.</p>',
                'price' => 899.99, 'compare_at_price' => 1099.99, 'cost_price' => 400.00,
                'sku' => 'FURN-NC-SOF3', 'stock' => 10, 'weight' => 45.00, 'is_featured' => false,
                'attrs' => ['Birch Wood, Linen', 'Light Grey', 'false'],
                'parent_cat' => 'Furniture',
            ],

            // --- Shoes ---
            [
                'cat' => 'Sports Shoes', 'brand' => 'Nike',
                'name' => 'Nike Air Zoom Pegasus 42',
                'short_description' => 'Versatile running shoe with responsive Zoom Air cushioning.',
                'description' => '<p>The Nike Air Zoom Pegasus 42 continues the legacy of one of running\'s most trusted shoes. Responsive Zoom Air units in the forefoot and heel deliver a spring in every stride, while the engineered mesh upper provides targeted breathability. The Flywire cables wrap your midfoot for a secure, adaptive fit from start line to finish.</p>',
                'price' => 129.99, 'compare_at_price' => 149.99, 'cost_price' => 55.00,
                'sku' => 'SHOE-NK-PEG42', 'stock' => 80, 'weight' => 0.28, 'is_featured' => true,
                'attrs' => ['UK 9', 'Black/White', 'Rubber'],
                'parent_cat' => 'Shoes',
            ],
            [
                'cat' => 'Men\'s Shoes', 'brand' => 'Nike',
                'name' => 'ClassicLeather Oxford Dress Shoe',
                'short_description' => 'Handcrafted full-grain leather oxford with Goodyear welt construction.',
                'description' => '<p>Step into timeless sophistication with the ClassicLeather Oxford. Handcrafted from premium full-grain leather, each pair features Goodyear welt construction for durability and resolability. The cushioned insole and leather lining ensure all-day comfort, whether you are in the boardroom or at a formal event. Available in black and tan.</p>',
                'price' => 189.99, 'compare_at_price' => 229.99, 'cost_price' => 80.00,
                'sku' => 'SHOE-CL-OXF01', 'stock' => 40, 'weight' => 0.45, 'is_featured' => false,
                'attrs' => ['UK 10', 'Black', 'Leather'],
                'parent_cat' => 'Shoes',
            ],

            // --- Clothing ---
            [
                'cat' => 'Men\'s Clothing', 'brand' => 'Nike',
                'name' => 'Nike Dri-FIT Running T-Shirt',
                'short_description' => 'Lightweight, sweat-wicking performance tee for runners.',
                'description' => '<p>Stay cool and dry during every run with the Nike Dri-FIT Running T-Shirt. The moisture-wicking Dri-FIT fabric pulls sweat away from your skin, while the lightweight, breathable mesh panels enhance airflow. A reflective swoosh logo adds visibility in low-light conditions. Available in multiple colours and sizes.</p>',
                'price' => 34.99, 'compare_at_price' => 44.99, 'cost_price' => 12.00,
                'sku' => 'CLTH-NK-DFT01', 'stock' => 150, 'weight' => 0.15, 'is_featured' => false,
                'attrs' => ['L', 'Navy Blue', 'Polyester'],
                'parent_cat' => 'Clothing',
            ],
            [
                'cat' => 'Women\'s Clothing', 'brand' => 'Nike',
                'name' => 'Cashmere Blend Wrap Cardigan',
                'short_description' => 'Luxuriously soft cashmere-blend cardigan with open-front drape design.',
                'description' => '<p>Wrap yourself in effortless luxury with this Cashmere Blend Cardigan. The open-front drape design flatters every body type, while the cashmere-wool blend keeps you warm without bulk. Ribbed cuffs and hem add structure, and the relaxed fit makes it perfect for layering over blouses, dresses, or casual tees. Dry clean recommended.</p>',
                'price' => 89.99, 'compare_at_price' => 119.99, 'cost_price' => 35.00,
                'sku' => 'CLTH-CB-WC01', 'stock' => 60, 'weight' => 0.35, 'is_featured' => true,
                'attrs' => ['M', 'Oatmeal', 'Cashmere Wool Blend'],
                'parent_cat' => 'Clothing',
            ],

            // --- Tools ---
            [
                'cat' => 'Power Tools', 'brand' => 'Bosch',
                'name' => 'Bosch Professional 18V Hammer Drill',
                'short_description' => 'Brushless cordless hammer drill with 90 Nm torque and kickback control.',
                'description' => '<p>Tackle the toughest drilling and driving tasks with the Bosch Professional 18V Hammer Drill. The brushless EC motor delivers 90 Nm of max torque and up to 30% longer runtime than brushed alternatives. Electronic kickback control, 20+1 torque settings, and an all-metal chuck ensure precision and safety. Includes two 4.0 Ah batteries and a fast charger.</p>',
                'price' => 219.99, 'compare_at_price' => 269.99, 'cost_price' => 100.00,
                'sku' => 'TOOL-BSH-HD18', 'stock' => 30, 'weight' => 1.90, 'is_featured' => true,
                'attrs' => ['18V Li-Ion Battery', '1.9'],
                'parent_cat' => 'Tools',
            ],
            [
                'cat' => 'Hand Tools', 'brand' => 'Bosch',
                'name' => '150-Piece Mechanics Tool Set',
                'short_description' => 'Comprehensive chrome vanadium tool set in a blow-moulded carry case.',
                'description' => '<p>Be ready for any repair with this 150-Piece Mechanics Tool Set. Includes ratchets, sockets (metric and imperial), combination wrenches, hex keys, screwdriver bits, pliers, and extension bars. Every tool is crafted from chrome vanadium steel for maximum strength and corrosion resistance. The organised blow-moulded case keeps everything in its place.</p>',
                'price' => 89.99, 'compare_at_price' => 109.99, 'cost_price' => 35.00,
                'sku' => 'TOOL-MTS-150', 'stock' => 50, 'weight' => 6.50, 'is_featured' => false,
                'attrs' => ['Manual', '6.5'],
                'parent_cat' => 'Tools',
            ],

            // --- Homemade ---
            [
                'cat' => 'Handmade Candles', 'brand' => null,
                'name' => 'Lavender & Eucalyptus Soy Candle',
                'short_description' => 'Hand-poured 100% soy wax candle with natural essential oils and cotton wick.',
                'description' => '<p>Create a calming sanctuary with our Lavender & Eucalyptus Soy Candle. Hand-poured in small batches using 100% natural soy wax and a premium cotton wick, this candle delivers a clean, even burn for up to 50 hours. The soothing blend of lavender and eucalyptus essential oils promotes relaxation and restful sleep. Packaged in a reusable amber glass jar.</p>',
                'price' => 24.99, 'compare_at_price' => 29.99, 'cost_price' => 8.00,
                'sku' => 'HOME-LE-SC01', 'stock' => 120, 'weight' => 0.40, 'is_featured' => false,
                'attrs' => ['Local Artisan Collective', 'Soy Wax, Lavender Oil, Eucalyptus Oil'],
                'parent_cat' => 'Homemade',
            ],
            [
                'cat' => 'Artisan Food', 'brand' => null,
                'name' => 'Small-Batch Wildflower Honey (500g)',
                'short_description' => 'Raw, unfiltered wildflower honey harvested from local apiaries.',
                'description' => '<p>Taste the pure sweetness of nature with our Small-Batch Wildflower Honey. Harvested from free-roaming bee colonies among wildflower meadows, this raw, unfiltered honey retains all of its natural enzymes, pollen, and antioxidants. Drizzle it on toast, stir into tea, or use it in baking. Each jar supports sustainable beekeeping practices.</p>',
                'price' => 12.99, 'compare_at_price' => null, 'cost_price' => 4.00,
                'sku' => 'HOME-WFH-500', 'stock' => 90, 'weight' => 0.55, 'is_featured' => false,
                'attrs' => ['Happy Bees Apiary', 'Raw Wildflower Honey'],
                'parent_cat' => 'Homemade',
            ],
        ];

        foreach ($productDefs as $pd) {
            $product = Product::create([
                'name'              => $pd['name'],
                'slug'              => Str::slug($pd['name']),
                'short_description' => $pd['short_description'],
                'description'       => $pd['description'],
                'sku'               => $pd['sku'],
                'price'             => $pd['price'],
                'compare_at_price'  => $pd['compare_at_price'],
                'cost_price'        => $pd['cost_price'],
                'stock_quantity'    => $pd['stock'],
                'low_stock_threshold' => 5,
                'category_id'       => $categories[$pd['cat']]->id,
                'brand_id'          => $pd['brand'] ? $brands[$pd['brand']]->id : null,
                'seller_id'         => $sellerUser->id,
                'weight'            => $pd['weight'],
                'weight_unit'       => 'kg',
                'status'            => 'active',
                'is_featured'       => $pd['is_featured'],
                'is_digital'        => false,
                'meta_title'        => $pd['name'] . ' | PickKart',
                'meta_description'  => $pd['short_description'],
                'published_at'      => $now,
            ]);

            // Product images (placeholder URLs)
            $encodedName = urlencode($pd['name']);
            ProductImage::create([
                'product_id' => $product->id,
                'path'       => "https://placehold.co/600x600/e0e7ff/4338ca?text={$encodedName}",
                'alt_text'   => $pd['name'] . ' - Main Image',
                'sort_order' => 0,
                'is_primary' => true,
            ]);
            ProductImage::create([
                'product_id' => $product->id,
                'path'       => "https://placehold.co/600x600/dbeafe/3b82f6?text={$encodedName}+2",
                'alt_text'   => $pd['name'] . ' - Gallery 1',
                'sort_order' => 1,
                'is_primary' => false,
            ]);

            // Attribute values
            if (isset($attributes[$pd['parent_cat']])) {
                foreach ($attributes[$pd['parent_cat']] as $index => $attr) {
                    if (isset($pd['attrs'][$index])) {
                        ProductAttributeValue::create([
                            'product_id'            => $product->id,
                            'product_attribute_id'  => $attr->id,
                            'value'                 => $pd['attrs'][$index],
                        ]);
                    }
                }
            }
        }

        // ---------------------------------------------------------------
        // 9. BANNERS
        // ---------------------------------------------------------------
        $bannerData = [
            [
                'title'       => 'Summer Sale - Up to 60% Off',
                'subtitle'    => 'Grab the best deals on electronics, fashion, and more before they are gone!',
                'image'       => 'https://placehold.co/1920x500/4338ca/ffffff?text=Summer+Sale+-+Up+to+60%25+Off',
                'link'        => '/collections/summer-sale',
                'button_text' => 'Shop Now',
                'position'    => 'hero',
                'sort_order'  => 0,
            ],
            [
                'title'       => 'New Arrivals in Electronics',
                'subtitle'    => 'Discover the latest smartphones, laptops, and gadgets.',
                'image'       => 'https://placehold.co/1920x500/1e40af/ffffff?text=New+Electronics+Arrivals',
                'link'        => '/categories/electronics',
                'button_text' => 'Explore',
                'position'    => 'hero',
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Top Brands, Best Prices',
                'subtitle'    => 'Shop Samsung, Nike, IKEA, Bosch and more at unbeatable prices.',
                'image'       => 'https://placehold.co/1920x500/7c3aed/ffffff?text=Top+Brands+Best+Prices',
                'link'        => '/brands',
                'button_text' => 'View Brands',
                'position'    => 'hero',
                'sort_order'  => 2,
            ],
            [
                'title'       => 'Free Shipping on Orders Over $50',
                'subtitle'    => null,
                'image'       => 'https://placehold.co/400x600/059669/ffffff?text=Free+Shipping',
                'link'        => '/shipping-policy',
                'button_text' => 'Learn More',
                'position'    => 'sidebar',
                'sort_order'  => 0,
            ],
            [
                'title'       => 'Become a Seller on PickKart',
                'subtitle'    => 'Reach millions of customers. Start your store today.',
                'image'       => 'https://placehold.co/1920x300/0f172a/ffffff?text=Become+a+Seller+on+PickKart',
                'link'        => '/seller/register',
                'button_text' => 'Start Selling',
                'position'    => 'footer',
                'sort_order'  => 0,
            ],
        ];

        foreach ($bannerData as $banner) {
            Banner::create(array_merge($banner, ['is_active' => true]));
        }

        // ---------------------------------------------------------------
        // 10. STATIC PAGES
        // ---------------------------------------------------------------
        $pages = [
            [
                'title'   => 'About Us',
                'slug'    => 'about-us',
                'content' => '<h2>Who We Are</h2>
<p>PickKart is a modern, multi-vendor e-commerce platform that connects buyers with trusted sellers across a wide range of categories. Founded with the mission to make online shopping accessible, affordable, and enjoyable, we bring together millions of products from Electronics and Fashion to Gardening and Homemade goods.</p>
<h2>Our Mission</h2>
<p>We believe everyone deserves access to quality products at fair prices. PickKart empowers small businesses and independent sellers to reach customers worldwide while providing shoppers with a safe, convenient marketplace they can trust.</p>
<h2>Why Choose PickKart?</h2>
<ul>
<li>Wide selection across 12+ categories</li>
<li>Verified sellers with quality assurance</li>
<li>Secure payment processing</li>
<li>Fast, reliable shipping</li>
<li>Dedicated customer support</li>
</ul>',
                'meta_title'       => 'About Us | PickKart',
                'meta_description' => 'Learn about PickKart, our mission, and why millions of shoppers trust us for their online purchases.',
            ],
            [
                'title'   => 'Contact Us',
                'slug'    => 'contact-us',
                'content' => '<h2>Get in Touch</h2>
<p>We would love to hear from you. Whether you have a question about a product, need help with an order, or want to share feedback, our team is here to help.</p>
<h3>Customer Support</h3>
<p>Email: <a href="mailto:support@pickkart.co.in">support@pickkart.co.in</a><br>
Hours: Monday to Sunday, 9:00 AM - 6:00 PM IST</p>
<h3>Seller Support</h3>
<p>Email: <a href="mailto:sellers@pickkart.com">sellers@pickkart.com</a></p>
<h3>Corporate Office</h3>
<p>PickKart Technologies Pvt. Ltd.<br>
123 Market Street, Andheri East<br>
Mumbai, Maharashtra 400069, India</p>',
                'meta_title'       => 'Contact Us | PickKart',
                'meta_description' => 'Reach out to the PickKart customer support team for help with orders, products, or seller enquiries.',
            ],
            [
                'title'   => 'FAQ',
                'slug'    => 'faq',
                'content' => '<h2>Frequently Asked Questions</h2>

<h3>Orders & Shipping</h3>
<p><strong>How long does shipping take?</strong><br>
Standard shipping takes 5-7 business days. Express shipping (2-3 business days) is available at checkout for an additional fee.</p>

<p><strong>Can I track my order?</strong><br>
Yes. Once your order ships, you will receive a tracking number via email. You can also track all orders from your account dashboard.</p>

<p><strong>Do you ship internationally?</strong><br>
Currently we ship within India. International shipping is coming soon.</p>

<h3>Returns & Refunds</h3>
<p><strong>What is your return policy?</strong><br>
Most items can be returned within 30 days of delivery for a full refund. Items must be unused, in original packaging, and accompanied by the receipt.</p>

<p><strong>How long do refunds take?</strong><br>
Refunds are processed within 5-7 business days after we receive the returned item.</p>

<h3>Account & Payments</h3>
<p><strong>What payment methods do you accept?</strong><br>
We accept credit/debit cards (Visa, Mastercard, RuPay), UPI, net banking, and popular wallets.</p>

<p><strong>Is my payment information secure?</strong><br>
Absolutely. All transactions are encrypted with 256-bit SSL and processed through PCI-DSS compliant payment gateways.</p>',
                'meta_title'       => 'FAQ | PickKart',
                'meta_description' => 'Find answers to common questions about orders, shipping, returns, payments, and more at PickKart.',
            ],
            [
                'title'   => 'Terms & Conditions',
                'slug'    => 'terms-and-conditions',
                'content' => '<h2>Terms &amp; Conditions</h2>
<p><em>Last updated: February 2026</em></p>

<h3>1. Acceptance of Terms</h3>
<p>By accessing or using the PickKart website and services, you agree to be bound by these Terms and Conditions. If you do not agree, please do not use our services.</p>

<h3>2. User Accounts</h3>
<p>You must provide accurate information when creating an account. You are responsible for maintaining the confidentiality of your credentials and for all activity under your account.</p>

<h3>3. Products & Pricing</h3>
<p>Product listings are provided by third-party sellers. While we strive for accuracy, PickKart does not warrant that descriptions, pricing, or availability information is error-free. Prices are subject to change without notice.</p>

<h3>4. Orders & Payment</h3>
<p>Placing an order constitutes an offer to purchase. We reserve the right to decline or cancel any order. Payment must be completed at the time of checkout using the available methods.</p>

<h3>5. Shipping & Delivery</h3>
<p>Estimated delivery dates are indicative. PickKart is not liable for delays caused by carriers, natural events, or other circumstances beyond our control.</p>

<h3>6. Returns & Refunds</h3>
<p>Our 30-day return policy applies to eligible items. Certain categories such as perishable goods, personal care items, and digital products may have different return terms.</p>

<h3>7. Limitation of Liability</h3>
<p>PickKart shall not be liable for any indirect, incidental, or consequential damages arising out of the use of our platform.</p>

<h3>8. Governing Law</h3>
<p>These terms are governed by the laws of India. Any disputes will be subject to the exclusive jurisdiction of the courts of Mumbai.</p>',
                'meta_title'       => 'Terms & Conditions | PickKart',
                'meta_description' => 'Read the PickKart terms and conditions governing use of our platform, orders, shipping, and returns.',
            ],
            [
                'title'   => 'Privacy Policy',
                'slug'    => 'privacy-policy',
                'content' => '<h2>Privacy Policy</h2>
<p><em>Last updated: February 2026</em></p>

<h3>1. Information We Collect</h3>
<p>We collect personal information you provide when creating an account, placing orders, or contacting support. This includes your name, email, phone number, shipping address, and payment details.</p>

<h3>2. How We Use Your Information</h3>
<ul>
<li>Process and fulfil your orders</li>
<li>Send order confirmations and shipping updates</li>
<li>Improve our platform and personalise your experience</li>
<li>Communicate promotions and offers (with your consent)</li>
<li>Prevent fraud and ensure platform security</li>
</ul>

<h3>3. Information Sharing</h3>
<p>We do not sell your personal data. We share information only with sellers (to fulfil orders), payment processors, shipping partners, and as required by law.</p>

<h3>4. Data Security</h3>
<p>We implement industry-standard security measures including SSL encryption, secure data storage, and regular security audits to protect your information.</p>

<h3>5. Cookies</h3>
<p>We use cookies and similar technologies to enhance your browsing experience, analyse site traffic, and personalise content. You can manage cookie preferences in your browser settings.</p>

<h3>6. Your Rights</h3>
<p>You may access, update, or delete your personal information at any time through your account settings. For data deletion requests, contact us at <a href="mailto:privacy@pickkart.com">privacy@pickkart.com</a>.</p>

<h3>7. Contact</h3>
<p>For privacy-related enquiries, email <a href="mailto:privacy@pickkart.com">privacy@pickkart.com</a>.</p>',
                'meta_title'       => 'Privacy Policy | PickKart',
                'meta_description' => 'Understand how PickKart collects, uses, and protects your personal information.',
            ],
        ];

        foreach ($pages as $page) {
            Page::create(array_merge($page, ['is_active' => true]));
        }

        // ---------------------------------------------------------------
        // 11. COUPONS
        // ---------------------------------------------------------------
        $coupons = [
            [
                'code'               => 'WELCOME10',
                'type'               => 'percentage',
                'value'              => 10.00,
                'min_order_amount'   => 50.00,
                'max_discount_amount'=> 100.00,
                'usage_limit'        => 1000,
                'per_user_limit'     => 1,
                'starts_at'          => now(),
                'expires_at'         => now()->addMonths(6),
                'is_active'          => true,
            ],
            [
                'code'               => 'FLAT200',
                'type'               => 'fixed',
                'value'              => 200.00,
                'min_order_amount'   => 999.00,
                'max_discount_amount'=> null,
                'usage_limit'        => 500,
                'per_user_limit'     => 2,
                'starts_at'          => now(),
                'expires_at'         => now()->addMonths(3),
                'is_active'          => true,
            ],
            [
                'code'               => 'FREESHIP',
                'type'               => 'free_shipping',
                'value'              => 0.00,
                'min_order_amount'   => 25.00,
                'max_discount_amount'=> null,
                'usage_limit'        => null,
                'per_user_limit'     => 5,
                'starts_at'          => now(),
                'expires_at'         => now()->addYear(),
                'is_active'          => true,
            ],
            [
                'code'               => 'SUMMER25',
                'type'               => 'percentage',
                'value'              => 25.00,
                'min_order_amount'   => 100.00,
                'max_discount_amount'=> 250.00,
                'usage_limit'        => 200,
                'per_user_limit'     => 1,
                'category_id'        => $categories['Clothing']->id,
                'starts_at'          => now(),
                'expires_at'         => now()->addMonths(2),
                'is_active'          => true,
            ],
            [
                'code'               => 'ELEC15',
                'type'               => 'percentage',
                'value'              => 15.00,
                'min_order_amount'   => 200.00,
                'max_discount_amount'=> 500.00,
                'usage_limit'        => 300,
                'per_user_limit'     => 1,
                'category_id'        => $categories['Electronics']->id,
                'starts_at'          => now(),
                'expires_at'         => now()->addMonths(3),
                'is_active'          => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }

        // ---------------------------------------------------------------
        // 12. DEFAULT SETTINGS
        // ---------------------------------------------------------------
        $defaultSettings = [
            'currency_code'               => 'USD',
            'currency_symbol'             => '$',
            'currency_symbol_position'    => 'before',
            'currency_decimal_places'     => '2',
            'currency_thousand_separator' => ',',
            'currency_decimal_separator'  => '.',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::set($key, $value);
        }

        // ---------------------------------------------------------------
        // 13. PRODUCT VARIANTS (Shopify-style options)
        // ---------------------------------------------------------------
        $variantProducts = [
            [
                'sku' => 'CLTH-NK-DFT01',
                'options' => [
                    ['name' => 'Color', 'values' => ['Navy Blue', 'Black', 'White', 'Red']],
                    ['name' => 'Size', 'values' => ['S', 'M', 'L', 'XL']],
                ],
            ],
            [
                'sku' => 'CLTH-CB-WC01',
                'options' => [
                    ['name' => 'Color', 'values' => ['Oatmeal', 'Charcoal', 'Burgundy']],
                    ['name' => 'Size', 'values' => ['S', 'M', 'L']],
                ],
            ],
            [
                'sku' => 'SHOE-NK-PEG42',
                'options' => [
                    ['name' => 'Color', 'values' => ['Black/White', 'Pure White', 'Grey/Volt']],
                    ['name' => 'Size', 'values' => ['UK 7', 'UK 8', 'UK 9', 'UK 10', 'UK 11']],
                ],
            ],
            [
                'sku' => 'ELEC-SAM-S25U',
                'options' => [
                    ['name' => 'Color', 'values' => ['Titanium Black', 'Titanium Gray', 'Titanium Blue']],
                    ['name' => 'Storage', 'values' => ['256GB', '512GB', '1TB']],
                ],
            ],
        ];

        foreach ($variantProducts as $vp) {
            $product = Product::where('sku', $vp['sku'])->first();
            if (!$product) continue;

            $product->update(['options' => $vp['options']]);

            // Generate cartesian product of option values
            $combinations = [[]];
            foreach ($vp['options'] as $option) {
                $newCombinations = [];
                foreach ($combinations as $combo) {
                    foreach ($option['values'] as $value) {
                        $newCombinations[] = array_merge($combo, [$value]);
                    }
                }
                $combinations = $newCombinations;
            }

            foreach ($combinations as $i => $combo) {
                $name = implode(' / ', $combo);
                $priceAdj = 0;

                // Add price adjustments for premium options
                if (in_array('1TB', $combo)) $priceAdj = 200;
                elseif (in_array('512GB', $combo)) $priceAdj = 100;
                elseif (in_array('XL', $combo) || in_array('UK 11', $combo)) $priceAdj = 5;

                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $name,
                    'option1' => $combo[0] ?? null,
                    'option2' => $combo[1] ?? null,
                    'option3' => $combo[2] ?? null,
                    'price' => $product->price + $priceAdj,
                    'compare_at_price' => $product->compare_at_price ? $product->compare_at_price + $priceAdj : null,
                    'stock_quantity' => rand(5, 50),
                    'is_active' => true,
                    'sort_order' => $i,
                ]);
            }
        }
    }
}
