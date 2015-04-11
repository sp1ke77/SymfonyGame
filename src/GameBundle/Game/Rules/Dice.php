<?php
/**
 * Created by PhpStorm.
 * User: Anon
 * Date: 4/2/2015
 * Time: 12:00 AM
 */

namespace GameBundle\Game\Rules;

class Dice
{
    public static function Odds($percent)
    {
        if (rand(1,100) < $percent)
        {
            return true;
        } else {
            return false;
        }
    }
}