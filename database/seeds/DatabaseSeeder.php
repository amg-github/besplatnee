<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ApplicationsTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(PropertiesTableSeeder::class);
        $this->call(HeadingsTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(AdvertsTableSeeder::class);
        $this->call(PhonesTableSeeder::class);
        $this->call(BannersTableSeeder::class);
        $this->call(OrganizationTableSeeder::class);
    }
}
