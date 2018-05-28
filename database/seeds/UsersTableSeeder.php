<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'firstname' => 'Администратор',
            'email' => 'romikon164@gmail.com',
            'phone' => '89397126315',
            'password' => bcrypt('09021970'),
        ]);

        DB::table('users')->insert([
            'firstname' => 'Игорь Калганов',
            'email' => 'ibesplatnee@gmail.com',
            'phone' => '89297071188',
            'password' => bcrypt('89297071188'),
        ]);
    }
}
