<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->after('cost', function ($table) {
                $table->boolean("use_stock_opname")->default(true);
            });
        });
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(["use_stock_opname"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(["use_stock_opname"]);
        });
        Schema::table('stores', function (Blueprint $table) {
            $table->after('phone', function ($table) {
                $table->boolean("use_stock_opname")->default(true);
            });
        });
    }
};
