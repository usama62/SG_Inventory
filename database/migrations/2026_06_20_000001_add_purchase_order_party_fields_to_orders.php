<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('supplier_company_name')->nullable()->after('branch');
            $table->text('supplier_address')->nullable()->after('supplier_company_name');
            $table->string('supplier_phone', 50)->nullable()->after('supplier_address');
            $table->string('ship_to_name')->nullable()->after('supplier_phone');
            $table->string('ship_to_company_name')->nullable()->after('ship_to_name');
            $table->text('ship_to_address')->nullable()->after('ship_to_company_name');
            $table->string('ship_to_phone', 50)->nullable()->after('ship_to_address');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'supplier_company_name',
                'supplier_address',
                'supplier_phone',
                'ship_to_name',
                'ship_to_company_name',
                'ship_to_address',
                'ship_to_phone',
            ]);
        });
    }
};
