<?php

namespace Database\Factories;

use App\Constants\AccountStatus;
use App\Traits\CodeGenerationTrait;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AccountFactory extends Factory
{
    use CodeGenerationTrait;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'             => Str::uuid()->toString(),
            'status'         => AccountStatus::ACTIVE,
            'account_number' => $this->generateCode(8),
            'telephone'      => '674567091',
            'currency'       => 'XAF',
            'current_balance'=> 10000.00,
            'available_balance' => 10000.00
        ];
    }
}
