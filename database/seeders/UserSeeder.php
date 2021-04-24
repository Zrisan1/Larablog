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
        User::create([
            'name'=>"Renzo",
            'email' => "r@admin.com",
            'password' => bcrypt('123123123')
        ])->assignRole('Admin');



        User::factory(50)->create();
    }
}
