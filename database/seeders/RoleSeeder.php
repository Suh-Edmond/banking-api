<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        Role::create([
            'name'          => 'CUSTOMER',
            'description'   => $faker->text
        ]);

        Role::create([
            'name'          => 'SUPPORT_BENCH',
            'description'   => $faker->text
        ]);
    }
}
