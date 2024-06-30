<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;


enum Category : string
{
     case Pop = 'Pop';
     case Rock = 'Rock';
     case Rap = 'Rap';
     case Electronic = 'Electronic';
     case Funk = 'Funk';
     case Dubstep = 'Dubstep';
     case RNB = 'RNB';
     case House = 'House';
     case Reggaeton = 'Reggaeton';
     case Trap = 'Trap';
}

    /*const POP = 'pop';
    const ROCK = 'rock';
    const RAP = 'rap';*/
/*
    public static function toArray()
    {
        return [
            self::POP,
            self::ROCK,
            self::RAP,
        ];
    }

    public static function getKey($value)
    {
        if ($value === self::Pop) {
            return 'Pop';
        } elseif ($value === self::Rock) {
            return 'Rock';
        } elseif ($value === self::Rap) {
            return 'Rap';
        }
        throw new \InvalidArgumentException();
    }*/















