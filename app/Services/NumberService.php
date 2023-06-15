<?php

namespace App\Services;

class NumberService
{
    public static function isLogicalNumber($num): bool
    {
        if (strlen($num) < 7
            || strlen($num) > 16
            || !is_numeric($num)
            || preg_match('/(1{5}|2{5}|3{5}|4{5}|5{5}|6{5}|7{5}|8{5}|9{5}|0{5}|123456|56789|98765|54321)/', $num)) {
            return false;
        }

        return true;
    }
}
