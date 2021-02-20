<?php

namespace App\Component\Paginator;

use Doctrine\ORM\QueryBuilder;

class Paginator
{

    /**
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * @var int
     */
    protected $currentPage;

    /**
     * @var int
     */
    protected $itemsPerPage;

    /**
     * @var int
     */
    protected $totalItems;

    /**
     * @var int
     */
    protected $totalPages;

    protected $isPrepared = false;

    /**
     * @var bool
     */
    protected $fetchJoin;

    public function __construct(QueryBuilder $qb, int $currentPage, int $itemsPerPage, $fetchJoin = false)
    {
        $this->qb = $qb;
        $this->currentPage  = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
        $this->fetchJoin    = $fetchJoin;
    }

    /**
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     * @throws \Exception
     */
    public function getIterator()
    {
        $firstResult = ($this->currentPage - 1) * $this->itemsPerPage;
        $maxResult   = $this->itemsPerPage;

        $this->qb->setFirstResult($firstResult);
        $this->qb->setMaxResults($maxResult);

        $this->isPrepared = true;

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($this->qb, $this->fetchJoin);

        $this->totalItems = count($paginator);
        $this->totalPages = (int)ceil($this->totalItems / $this->itemsPerPage);

        if ($this->totalPages > 0 && $this->currentPage > $this->totalPages) {
            throw new \OutOfRangeException(sprintf('Current page %d is greater than total pages %d', $this->currentPage, $this->totalPages));
        }

        return $paginator;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage(int $currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * @param int $itemsPerPage
     */
    public function setItemsPerPage(int $itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * @param int $totalItems
     */
    public function setTotalItems(int $totalItems)
    {
        $this->totalItems = $totalItems;
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * @param int $totalPages
     */
    public function setTotalPages(int $totalPages)
    {
        $this->totalPages = $totalPages;
    }

    /**
     * @return int|null
     */
    public function getPrevPage()
    {
        if ($this->isFirstPage()) {
            return null;
        }

        return $this->currentPage - 1;
    }

    /**
     * @return int|null
     */
    public function getNextPage()
    {
        if ($this->isLastPage()) {
            return null;
        }

        return $this->currentPage + 1;
    }

    public function isFirstPage(int $page = null)
    {
        if ($page === null) {
            $page = $this->currentPage;
        }

        return $page == 1;
    }

    public function isLastPage(int $page = null)
    {
        if ($page === null) {
            $page = $this->currentPage;
        }

        return $page == $this->totalPages;
    }

    public function hasPrevPage(int $page = null)
    {
        if ($page === null) {
            $page = $this->currentPage;
        }

        return !$this->isFirstPage($page);
    }

    public function hasNextPage(int $page = null)
    {
        if ($page === null) {
            $page = $this->currentPage;
        }

        return !$this->isLastPage($page);
    }

    public function needsPaging()
    {
        return $this->totalPages > 1;
    }
}