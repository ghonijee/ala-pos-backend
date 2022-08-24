<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Actions\Users\SetupRolePermission;

class UserExistSetPermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        $users = User::with("mainStore")->get();
        $users->each(function ($user) {
            SetupRolePermission::fromRegister($user, $user->mainStore);
        });

        DB::commit();
    }
}
