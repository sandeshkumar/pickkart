<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add options JSON to products
        Schema::table('products', function (Blueprint $table) {
            $table->json('options')->nullable()->after('custom_attributes');
        });

        // 2. Add option1/option2/option3 to product_variants
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('option1')->nullable()->after('name');
            $table->string('option2')->nullable()->after('option1');
            $table->string('option3')->nullable()->after('option2');
        });

        // 3. Migrate existing data: color→option1, size→option2, material→option3
        DB::table('product_variants')->whereNotNull('color')->update([
            'option1' => DB::raw('color'),
        ]);
        DB::table('product_variants')->whereNotNull('size')->update([
            'option2' => DB::raw('size'),
        ]);
        DB::table('product_variants')->whereNotNull('material')->update([
            'option3' => DB::raw('material'),
        ]);

        // 4. Build options JSON on products from their variants
        $products = DB::table('products')
            ->whereIn('id', DB::table('product_variants')->select('product_id')->distinct())
            ->get();

        foreach ($products as $product) {
            $variants = DB::table('product_variants')->where('product_id', $product->id)->get();
            $options = [];

            $colors = $variants->pluck('color')->unique()->filter()->values();
            if ($colors->isNotEmpty()) {
                $options[] = ['name' => 'Color', 'position' => 1, 'values' => $colors->toArray()];
            }

            $sizes = $variants->pluck('size')->unique()->filter()->values();
            if ($sizes->isNotEmpty()) {
                $options[] = ['name' => 'Size', 'position' => count($options) + 1, 'values' => $sizes->toArray()];
            }

            $materials = $variants->pluck('material')->unique()->filter()->values();
            if ($materials->isNotEmpty()) {
                $options[] = ['name' => 'Material', 'position' => count($options) + 1, 'values' => $materials->toArray()];
            }

            if (!empty($options)) {
                DB::table('products')->where('id', $product->id)->update([
                    'options' => json_encode($options),
                ]);
            }
        }

        // 5. Drop old columns
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['color', 'size', 'material']);
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('material')->nullable();
        });

        // Migrate back
        DB::table('product_variants')->whereNotNull('option1')->update([
            'color' => DB::raw('option1'),
        ]);
        DB::table('product_variants')->whereNotNull('option2')->update([
            'size' => DB::raw('option2'),
        ]);
        DB::table('product_variants')->whereNotNull('option3')->update([
            'material' => DB::raw('option3'),
        ]);

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['option1', 'option2', 'option3']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
};
