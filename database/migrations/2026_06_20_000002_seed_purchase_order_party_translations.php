<?php

use App\Classes\LangTrans;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        LangTrans::seedMainTranslations();
    }

    public function down(): void
    {
        //
    }
};
