<?php

use App\Constant\UserStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldUserStatusOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('is_member', function (Blueprint $table) {
                $table->enum('user_status', [UserStatus::FREE, UserStatus::TRIAL, UserStatus::PRO])->default(UserStatus::FREE)->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_status']);
        });
    }
}
