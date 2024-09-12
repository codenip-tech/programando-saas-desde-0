<?php

namespace App\Value;

use App\Entity\Membership;

readonly class AccessingMember
{
    public function __construct(
        public Membership $membership,
    )
    {
    }
}
