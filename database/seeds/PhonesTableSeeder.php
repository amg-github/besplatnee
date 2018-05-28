<?php

use Illuminate\Database\Seeder;

class PhonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('phones')->insert([
            'user_id' => 1,
            'verify' => 1,
        ]);

        DB::table('phones')->insert([
            'user_id' => 2,
            'verify' => 1,
        ]);
    }
}
