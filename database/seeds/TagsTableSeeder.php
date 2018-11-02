<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tags')->delete();

        DB::table('tags')->insert([
            0 => 
            [
                'id' => 1,
                'tag_name' => 'wlog',
                'tag_flag' => 'wlog',
                'created_at' => '2018-09-29 00:00:00',
                'updated_at' => '2018-09-29 00:00:00',
            ]
        ]);
    }
}
