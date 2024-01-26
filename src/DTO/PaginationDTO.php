<?php

namespace App\DTO;

class PaginationDTO
{
    private $page;
    private $limit;
    private $totalItems;

    public function __construct(int $page, int $limit, int $totalItems)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->totalItems = $totalItems;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getTotalPages(): int
    {
        return ceil($this->totalItems / $this->limit);
    }

    public function getOffset(): int
    {
        return ($this->page - 1) * $this->limit;
    }
}
