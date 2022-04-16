<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::upsert([
            ['id' => 1,'name' => 'Admin A', 'email' => 'admin@email.com', 'password' => bcrypt('12345678'), 'types' => 'Admin'],
            ['id' => 2,'name' => 'Admin B', 'email' => 'admin2@email.com', 'password' => bcrypt('12345678'), 'types' => 'Admin'],
            ['id' => 3,'name' => 'Admin C', 'email' => 'admin3@email.com', 'password' => bcrypt('12345678'), 'types' => 'Admin'],
            ['id' => 4,'name' => 'Customer A', 'email' => 'customer@email.com', 'password' => bcrypt('12345678'), 'types' => 'Customer'],
            ['id' => 5,'name' => 'Customer B', 'email' => 'customer2@email.com', 'password' => bcrypt('12345678'), 'types' => 'Customer'],
            ['id' => 6,'name' => 'Customer C', 'email' => 'customer3@email.com', 'password' => bcrypt('12345678'), 'types' => 'Customer'],
            ['id' => 7,'name' => 'Customer D', 'email' => 'customer4@email.com', 'password' => bcrypt('12345678'), 'types' => 'Customer']
        ], [], ['id']);
    }
}
