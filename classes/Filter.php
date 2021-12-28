<?php

class Filter
{
    private static $words = array(
        'coop gold' => false,
        'coop' => false,
        'c.gold' => false,
        'c. gold' => false,
        'centsibles' => false,
        'centsible' => false,
		'CENTS' => true,
        'co-op' => false,
        'CG' => true,
        'C.G.' => true,
        'C.G' => true,
		'c/gld' => false,
        'c/gold' => false,
		'C/Gold' => true,
        'c gold' => false,
        'care' => false,
    );

    private static function _strtolower($str, $flag){
        return (!$flag) ? strtolower($str) : $str;
    }

    public static function filter_by_words($title)
    {
        foreach (self::$words as $word => $flag) {
            if (strpos(self::_strtolower($title, $flag), $word) !== false) {
                return false;
            }
        }

        return true;
    }
}