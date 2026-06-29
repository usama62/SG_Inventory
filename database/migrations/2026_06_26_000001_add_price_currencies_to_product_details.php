<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->string('purchase_price_currency', 3)->default('AED')->after('purchase_price');
            $table->string('sales_price_currency', 3)->default('AED')->after('sales_price');
            $table->string('mrp_currency', 3)->default('AED')->after('mrp');
        });
    }

    public function down(): void
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->dropColumn([
                'purchase_price_currency',
                'sales_price_currency',
                'mrp_currency',
            ]);
        });
    }
};
