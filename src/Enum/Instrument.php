<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;


enum Instrument : string
{
     case Piano = 'Piano';
     case Guitar = 'Guitar';
     case Violin = 'Violin';
     case Electronic = 'Electronic';
     case Drums = 'Drums';
     case Flute = 'Flute';
     case Trumpet = 'Trumpet';
     case Saxophone = 'Saxophone';
     case Cello = 'Cello';
     case Clarinet = 'Clarinet';
}

