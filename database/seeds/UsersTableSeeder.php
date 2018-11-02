<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->delete();

        DB::table('users')->insert([
            'name' => 'wlog',
            'email' => 'example@wlog.me',
            'password' => '$2y$10$3CyYxSn4YNpjwu67NajLvO3iX07ldVZ8wceBxz1BZ0TF8m83vC6ga',
            'created_at' => '2018-09-29 00:00:00',
            'updated_at' => '2018-09-29 00:00:00',
        ]);
    }
}
