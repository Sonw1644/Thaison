<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update categories table if needed
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('categories', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            if (!Schema::hasColumn('categories', 'sort_order')) {
                $table->integer('sort_order')->default(1)->after('image');
            }
            if (!Schema::hasColumn('categories', 'status')) {
                $table->boolean('status')->default(true)->after('sort_order');
            }
        });

        // Create products table
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->string('name');
                $table->string('sku')->unique();
                $table->string('unit')->default('Thùng (Hộp)');
                $table->decimal('price', 15, 2)->default(0);
                $table->decimal('sale_price', 15, 2)->nullable();
                $table->integer('stock')->default(0);
                $table->string('brand')->nullable();
                $table->string('size')->nullable();
                $table->decimal('area_per_box', 8, 3)->nullable();
                $table->integer('pieces_per_box')->nullable();
                $table->string('surface')->nullable();
                $table->string('material')->nullable();
                $table->string('color_pattern')->nullable();
                $table->string('origin')->nullable()->default('Việt Nam');
                $table->string('anti_slip')->nullable()->default('R9');
                $table->decimal('weight_per_box', 8, 2)->nullable();
                $table->text('short_description')->nullable();
                $table->longText('description')->nullable();
                $table->string('thumbnail')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        // Create product_variants table for colors and tiles
        if (!Schema::hasTable('product_variants')) {
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->string('color_code')->nullable()->default('#787878');
                $table->string('color_name');
                $table->string('sku')->nullable();
                $table->integer('stock')->default(0);
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['description', 'image', 'sort_order', 'status']);
        });
    }
};
