<?php

use Illuminate\Database\Seeder;

class OrganizationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Besplatnee::organizations()->add([
        	'creator_id' => 1,
        	'manager_id' => 1,
        	'user_id' => 1,
        	'name' => 'AccessMedia',
        ]);
    }
}
