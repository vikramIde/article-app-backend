<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $faker = \Faker\Factory::create();

        $password = Hash::make('123456');

        User::create([
            'first_name' => 'Administrator',
            'last_name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => $password,
        ]);

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'first_name' => $faker->name,
                'last_name' => $faker->name,
                'email' => $faker->email,
                'password' => $password,
            ]);
        }
    }
}
