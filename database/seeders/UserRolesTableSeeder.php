<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('user_roles')->delete();

        \DB::table('user_roles')->insert([
            0 => [
                'id' => 1,
                'user_id' => 1,
                'role_id' => 1,
                'type' => 2,
                'expired_at' => null,
                'restore_role_id' => null,
                'created_at' => '2021-10-08 10:00:00',
                'updated_at' => '2021-10-08 10:00:00',
                'deleted_at' => null,
            ],
        ]);
    }
}
