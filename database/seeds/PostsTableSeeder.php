<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('posts')->delete();

        DB::table('posts')->insert([
            0 => 
            [
                'id' => 1,
                'title' => 'Thanks for using Wlog',
                'thumb' => '',
                'flag' => 'hello-wlog',
                'tag_id' => 1,
                'content' => '',
                'markdown' => '',
                'state' => true,
                'views' => 1,
                'created_at' => '2018-09-29 00:00:00',
                'updated_at' => '2018-09-29 00:00:00',
            ]
        ]);
    }
}
