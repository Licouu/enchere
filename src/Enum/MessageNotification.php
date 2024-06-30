<?php

namespace App\Enum;

enum MessageNotification : string
{
    case messageWinAuction = "Congrats!!! You won the auction!";
    case messageYouAreNotLongerFirst = "You are no longer first on the auction...";
    case messageYouAreNotFirst = "You are not first on the auction...";
    case messageYouLoseAuction = "Sorry you lost the auction...";
    case messageYouAreEliminated = "Sorry you were eliminated...";
    case messageYouAreFirst = "You are the first on the auction";

      public function toString() : string
      {
        return $this->value;
    }
}
