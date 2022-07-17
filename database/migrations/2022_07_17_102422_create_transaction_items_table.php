<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('key')->nullable();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name')->nullable();
            $table->integer('product_cost')->nullable()->default(0);
            $table->integer('price')->nullable()->default(0);
            $table->integer('discount_price')->nullable()->default(0);
            $table->double('discount_percentage')->nullable()->default(0.0);
            $table->integer('quantity')->nullable()->default(0);
            $table->integer('amount')->nullable()->default(0);
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_items');
    }
}
