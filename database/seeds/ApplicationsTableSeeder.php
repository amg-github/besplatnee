<?php

use Illuminate\Database\Seeder;

class ApplicationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('applications')->insert([
            'name' => 'Laravel Application',
            'key' => '1111',
            'secret' => '1111',
            'owner_id' => '1',
        ]);
    }
}
