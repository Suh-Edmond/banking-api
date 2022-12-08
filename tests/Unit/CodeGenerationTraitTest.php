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
    public function testGenerateCodeShouldGenerateAStringNumbersOfAnyGivenSize()
    {
        $obj = $this->getObjectForTrait(CodeGenerationTrait::class);

        $code = $obj->generateCode(8);

        $this->assertNotNull($code);
    }


}
