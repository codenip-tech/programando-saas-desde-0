<?php

namespace App\Repository\Value;

use App\Value\ListSortDirection;

class ProductListSort
{
    public function __construct(
        public readonly ProductListSortField $field,
        public readonly ListSortDirection $direction,
    )
    {
    }
}
