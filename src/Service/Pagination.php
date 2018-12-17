<?php

namespace App\Service;

class Pagination
{
    const PER_PAGE = 10;

    private $start;
    private $limit;
    private $totalPages;

    public function init(int $page, array $total): void
    {
        $this->totalPages = ceil(count($total) / self::PER_PAGE);
        if ($page < 1 || $page > $this->totalPages) {
            $page = 1;
        }

        $this->start = self::PER_PAGE * $page - self::PER_PAGE;
        $this->limit = self::PER_PAGE;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function setStart($start)
    {
        $this->start = $start;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getTotalPages()
    {
        return $this->totalPages;
    }

    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;
    }
}
