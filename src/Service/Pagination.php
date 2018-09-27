<?php

namespace App\Service;

class Pagination
{
    CONST PER_PAGE = 3;

    private $start;
    private $limit;
    private $nbPages;

    public function init(int $page, array $total) : void
    {
        $this->nbPages = ceil(count($total) / self::PER_PAGE);
        if ($page < 1 || $page > $this->nbPages) {
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

    public function getNbPages()
    {
        return $this->nbPages;
    }

    public function setNbPages($nbPages)
    {
        $this->nbPages = $nbPages;
    }
}
