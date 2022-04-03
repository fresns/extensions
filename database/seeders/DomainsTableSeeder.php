<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DomainsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('domains')->delete();

        \DB::table('domains')->insert([
            0 => [
                'id' => 1,
                'domain' => 'fresns.com',
                'sld' => 'fresns.com',
                'icon_file_id' => null,
                'icon_file_url' => null,
                'post_count' => 0,
                'comment_count' => 0,
                'is_enable' => 1,
                'created_at' => '2021-10-08 10:00:00',
                'updated_at' => '2021-10-08 10:00:00',
                'deleted_at' => null,
            ],
            1 => [
                'id' => 2,
                'domain' => 'fresns.org',
                'sld' => 'fresns.org',
                'icon_file_id' => null,
                'icon_file_url' => null,
                'post_count' => 0,
                'comment_count' => 0,
                'is_enable' => 1,
                'created_at' => '2021-10-08 10:00:00',
                'updated_at' => '2021-10-08 10:00:00',
                'deleted_at' => null,
            ],
            2 => [
                'id' => 3,
                'domain' => 'fresns.org',
                'sld' => 'docs.fresns.org',
                'icon_file_id' => null,
                'icon_file_url' => null,
                'post_count' => 0,
                'comment_count' => 0,
                'is_enable' => 1,
                'created_at' => '2021-10-08 10:00:00',
                'updated_at' => '2021-10-08 10:00:00',
                'deleted_at' => null,
            ],
            3 => [
                'id' => 4,
                'domain' => 'fresns.org',
                'sld' => 'apps.fresns.org',
                'icon_file_id' => null,
                'icon_file_url' => null,
                'post_count' => 0,
                'comment_count' => 0,
                'is_enable' => 1,
                'created_at' => '2021-10-08 10:00:00',
                'updated_at' => '2021-10-08 10:00:00',
                'deleted_at' => null,
            ],
            4 => [
                'id' => 5,
                'domain' => 'fresns.org',
                'sld' => 'discuss.fresns.org',
                'icon_file_id' => null,
                'icon_file_url' => null,
                'post_count' => 0,
                'comment_count' => 0,
                'is_enable' => 1,
                'created_at' => '2021-10-08 10:00:00',
                'updated_at' => '2021-10-08 10:00:00',
                'deleted_at' => null,
            ],
            5 => [
                'id' => 6,
                'domain' => 'fresns.cn',
                'sld' => 'fresns.cn',
                'icon_file_id' => null,
                'icon_file_url' => null,
                'post_count' => 0,
                'comment_count' => 0,
                'is_enable' => 1,
                'created_at' => '2021-10-08 10:00:00',
                'updated_at' => '2021-10-08 10:00:00',
                'deleted_at' => null,
            ],
            6 => [
                'id' => 7,
                'domain' => 'fresns.cn',
                'sld' => 'docs.fresns.cn',
                'icon_file_id' => null,
                'icon_file_url' => null,
                'post_count' => 0,
                'comment_count' => 0,
                'is_enable' => 1,
                'created_at' => '2021-10-08 10:00:00',
                'updated_at' => '2021-10-08 10:00:00',
                'deleted_at' => null,
            ],
            7 => [
                'id' => 8,
                'domain' => 'fresns.cn',
                'sld' => 'apps.fresns.cn',
                'icon_file_id' => null,
                'icon_file_url' => null,
                'post_count' => 0,
                'comment_count' => 0,
                'is_enable' => 1,
                'created_at' => '2021-10-08 10:00:00',
                'updated_at' => '2021-10-08 10:00:00',
                'deleted_at' => null,
            ],
            8 => [
                'id' => 9,
                'domain' => 'fresns.cn',
                'sld' => 'discuss.fresns.cn',
                'icon_file_id' => null,
                'icon_file_url' => null,
                'post_count' => 0,
                'comment_count' => 0,
                'is_enable' => 1,
                'created_at' => '2021-10-08 10:00:00',
                'updated_at' => '2021-10-08 10:00:00',
                'deleted_at' => null,
            ],
            9 => [
                'id' => 10,
                'domain' => 'tangjie.me',
                'sld' => 'tangjie.me',
                'icon_file_id' => null,
                'icon_file_url' => null,
                'post_count' => 0,
                'comment_count' => 0,
                'is_enable' => 1,
                'created_at' => '2021-10-08 10:00:00',
                'updated_at' => '2021-10-08 10:00:00',
                'deleted_at' => null,
            ],
        ]);
    }
}
