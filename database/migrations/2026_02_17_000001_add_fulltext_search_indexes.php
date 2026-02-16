<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE products ADD FULLTEXT INDEX ft_products_search (name, short_description, description)');

        Schema::table('brands', function (Blueprint $table) {
            $table->index('name', 'idx_brands_name');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('name', 'idx_categories_name');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->index('name', 'idx_tags_name');
        });
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE products DROP INDEX ft_products_search');

        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex('idx_brands_name');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_name');
        });
        Schema::table('tags', function (Blueprint $table) {
            $table->dropIndex('idx_tags_name');
        });
    }
};
