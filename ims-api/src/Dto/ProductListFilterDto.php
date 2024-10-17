<?php

namespace App\Dto;

use App\Repository\Value\ProductListSortField;
use App\Value\ListSortDirection;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ProductListFilterDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(options: ['name'])]
        public readonly string $field,
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 100)]
        public readonly string $value
    ) {
    }
}
