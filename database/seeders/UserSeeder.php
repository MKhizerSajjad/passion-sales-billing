<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() == 0) {

            $data = [
                'user_type'      => 1,
                'first_name'   => 'Super',
                'last_name'    => 'Admin',
                'email'        => 'admin@gmail.com',
                'password'     => bcrypt('12345678'),
            ];
            User::create($data);
        }
    }
}
