<?php

namespace App\DTO;

class SearchRequest
{
    public function __construct(
        public readonly string $filterName = '',
        public readonly string $sortBy = 'name',
        public readonly string $sortDirection = 'asc',
        public readonly int $limit = 100,
        public readonly array $messages = []
    ) {}
}
?>