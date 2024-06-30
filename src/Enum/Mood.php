<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

enum Mood : string
{
     case Aggressive = 'Aggressive';
     case Energetic = 'Energetic';
     case Mellow = 'Mellow';
     case Melancholic = 'Melancholic';
     case Upbeat = 'Upbeat';
     case Mysterious = 'Mysterious';
     case Funky = 'Funky';
     case Tense = 'Tense';
     case Dreamy = 'Dreamy';
     case Powerful = 'Powerful';
}

