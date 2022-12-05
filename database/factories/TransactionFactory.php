<?php

namespace Database\Factories;

use App\Traits\CodeGenerationTrait;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class TransactionFactory extends Factory
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
            'id'               => Str::uuid()->toString(),
            'transaction_code' => $this->generateCode(10),
            'amount_deposited' => 1000,
            'transaction_date' => '2022/12/05',
            'motive'           => "Test transfer",
            'status'           => 'COMPLETED',
        ];
    }
}
