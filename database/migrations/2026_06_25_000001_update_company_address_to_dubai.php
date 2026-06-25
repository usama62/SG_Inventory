<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const NEW_ADDRESS = "AL fattan plaza\nOffice no: 904\noffice building Al garhood\nDubai\nU.A.E";

    private const OLD_ADDRESSES = [
        '7 street, city, state, 762782',
        'BIZ00648, Compass Building, Al Shohada Road, Al Hamra Industrial Zone-FZ, Ras Al Khaimah, UAE',
        'BIZ00648, Compass Building, Al Shohada Road Al Hamra, Industrial Zone-FZ, Ras Al Khaimah, UAE',
    ];

    public function up(): void
    {
        foreach (['companies', 'warehouses'] as $table) {
            DB::table($table)
                ->where(function ($query) {
                    $query->whereIn('address', self::OLD_ADDRESSES)
                        ->orWhere('address', 'like', '%Compass Building%')
                        ->orWhere('address', 'like', '%Ras Al Khaimah%');
                })
                ->update(['address' => self::NEW_ADDRESS]);
        }
    }

    public function down(): void
    {
        // Address change is not reversible without knowing prior values.
    }
};
