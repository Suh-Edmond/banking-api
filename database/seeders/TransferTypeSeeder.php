<?php

namespace Database\Seeders;

use App\Models\TransferType;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class TransferTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        TransferType::create([
            'name'   => 'WIRED_TRANSFER',
            'description'   => $faker->text
        ]);
    }
}
