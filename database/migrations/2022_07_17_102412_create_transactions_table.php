<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('key')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('store_id');
            $table->date('date')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('note')->nullable();
            $table->integer('discount')->default(0)->nullable();
            $table->integer('amount')->default(0)->nullable();
            $table->integer('received_money')->default(0)->nullable();
            $table->integer('change_money')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
