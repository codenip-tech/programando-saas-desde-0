<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ProductListDto
{
    public function __construct(
        #[Assert\Valid]
        public readonly ?ProductListSortDto $sort,
        #[Assert\Valid]
        public readonly ?ProductListFilterDto $filter,
    ) {
    }
}
