<?php

namespace App\Helpers;

class NumberHelper
{
    public static function toWords($number)
    {
        $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        return $f->format($number);
    }
}
