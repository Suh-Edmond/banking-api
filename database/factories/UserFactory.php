<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'         => Str::uuid()->toString(),
            'first_name' => $this->faker->name(),
            'last_name'  => $this->faker->name(),
            'telephone'  => '503490234523',
            'dob'        => '2022/09/09',
            'pob'        => 'Buea',
            'country'    => 'Cameroon',
            'location'   => 'Buea',
            'box_number' => 6,
            'gender'     => 'MALE',
            'email'      => 'johndoe@gmail.com',
            'email_verified_at' => now(),
            'password'      => Hash::make("password"),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
