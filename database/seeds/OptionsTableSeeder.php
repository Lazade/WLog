<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('options')->delete();

        DB::table('options')->insert([
            0 =>
            [
                'option_key' => 'web_name',
                'option_value' => 'Wlog',
                'type' => 'base',
                'data_type' => 'text',
                'created_at' => '2018-09-29 00:00:00',
                'updated_at' => '2018-09-29 00:00:00',
            ],
            1 =>
            [
                'option_key' => 'seo_title',
                'option_value' => 'WLOG',
                'type' => 'base',
                'data_type' => 'text',
                'created_at' => '2018-09-29 00:00:00',
                'updated_at' => '2018-09-29 00:00:00',
            ],
            2 =>
            [
                'option_key' => 'seo_keywords',
                'option_value' => '',
                'type' => 'base',
                'data_type' => 'text',
                'created_at' => '2018-09-29 00:00:00',
                'updated_at' => '2018-09-29 00:00:00',
            ],
            3 => 
            [
                'option_key' => 'seo_description',
                'option_value' => '',
                'type' => 'base',
                'data_type' => 'text',
                'created_at' => '2018-09-29 00:00:00',
                'updated_at' => '2018-09-29 00:00:00',
            ],
            4 =>
            [
                'option_key' => 'content',
                'option_value' => 'Hi, this is Wlog. Enjoy it!',
                'type' => 'base',
                'data_type' => 'text',
                'created_at' => '2018-09-29 00:00:00',
                'updated_at' => '2018-09-29 00:00:00',
            ],
            5 => 
            [
                'option_key' => 'ga',
                'option_value' => '',
                'type' => 'extends',
                'data_type' => 'textarea',
                'created_at' => '2018-09-29 00:00:00',
                'updated_at' => '2018-09-29 00:00:00',
            ],
        ]);
    }
}
