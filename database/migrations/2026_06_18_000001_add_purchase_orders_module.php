<?php

use App\Classes\LangTrans;
use App\Classes\PermsSeed;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        PermsSeed::seedMainPermissions();
        LangTrans::seedMainTranslations();
    }

    public function down(): void
    {
        //
    }
};
