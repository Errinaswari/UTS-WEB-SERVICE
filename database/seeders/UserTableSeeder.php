<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create data user
        $userCreate = User::create([
            'name'      => 'Errinaswari',
            'email'     => 'erina@gmail.com',
            'password'  => bcrypt('erina123')
        ]);
    }
}
