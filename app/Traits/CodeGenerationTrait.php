<?php

namespace App\Traits;


trait CodeGenerationTrait {


    public static function generateTransactionCode()
    {
        $code  = "";
        $current_year = date("Y");
        for($i = 0; $i < 10; $i++){
            $code = $code.rand(0, 9);
        }
        $code = $code.$current_year;

        return $code;
    }
}
