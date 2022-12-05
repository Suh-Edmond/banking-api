<?php

namespace App\Traits;


trait CodeGenerationTrait {


    public static function generateCode($size)
    {
        $code  = "";
        $current_year = date("Y");
        for($i = 0; $i < $size; $i++){
            $code = $code.rand(0, 9);
        }
        $code = $code.$current_year;

        return $code;
    }
}
