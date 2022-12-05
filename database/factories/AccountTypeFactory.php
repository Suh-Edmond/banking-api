<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class AccountTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'           => Str::uuid()->toString(),
            'name'         => $this->faker->name(),
            'description'  => $this->faker->paragraph()
        ];
    }
}
