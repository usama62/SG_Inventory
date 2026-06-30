<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const NEW_PHONE = '+97143358029';

    private const OLD_PHONES = [
        '+971 56 409 0798',
        '+971564090798',
        '971564090798',
    ];

    public function up(): void
    {
        foreach (['companies', 'warehouses'] as $table) {
            DB::table($table)
                ->where(function ($query) {
                    $query->whereIn('phone', self::OLD_PHONES)
                        ->orWhere('phone', 'like', '%564090798%');
                })
                ->update(['phone' => self::NEW_PHONE]);
        }
    }

    public function down(): void
    {
        // Phone change is not reversible without knowing prior values.
    }
};
