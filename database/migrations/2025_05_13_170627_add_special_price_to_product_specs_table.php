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
        Schema::table('product_specs', function (Blueprint $table) {
            $table->decimal('special_price', 10, 2)->nullable()->after('price')->comment('優惠價');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_specs', function (Blueprint $table) {
            $table->dropColumn('special_price');
        });
    }
};
