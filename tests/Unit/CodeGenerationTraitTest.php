<?php

namespace Tests\Unit;

use App\Traits\CodeGenerationTrait;
use PHPUnit\Framework\TestCase;

class CodeGenerationTraitTest extends TestCase
{

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_generateCode_should_generate_a_string_numbers_of_any_given_size()
    {
        $obj = $this->getObjectForTrait(CodeGenerationTrait::class);

        $code = $obj->generateCode(8);

        $this->assertNotNull($code);
    }


}
