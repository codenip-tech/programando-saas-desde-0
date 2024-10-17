<?php

namespace App\Repository\Value;

class ProductListFilter
{
    public function __construct(
        public readonly ProductListFilterField $field,
        public readonly string $value,
    )
    {
    }
}
