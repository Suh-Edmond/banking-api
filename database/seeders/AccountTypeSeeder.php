<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        AccountType::create([
            'name'          => 'CURRENT_ACCOUNT',
            'description'   => $faker->text
        ]);

        AccountType::create([
            'name'          => 'SAVING_ACCOUNT',
            'description'   => $faker->text
        ]);
    }
}
