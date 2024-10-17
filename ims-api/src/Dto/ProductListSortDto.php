<?php

namespace App\Dto;

use App\Repository\Value\ProductListSortField;
use App\Value\ListSortDirection;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ProductListSortDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(options: ['id', 'name'])]
        public readonly string $field,
        #[Assert\NotBlank]
        #[Assert\Choice(options: ['asc', 'desc'])]
        public readonly string $direction
    ) {
    }
}
