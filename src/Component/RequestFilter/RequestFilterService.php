<?php

namespace App\Component\RequestFilter;

use App\Exception\Api\InternalServerErrorException;
use Doctrine\ORM\QueryBuilder;

class RequestFilterService
{
    /**
     * @var RequestFilterInterface[]
     */
    protected $filtersMap = [];

    public function filter(string $filterFqcn, QueryBuilder $qb, string $fieldName, $value)
    {
        $filter = $this->filtersMap[$filterFqcn] ?? null;

        if (!$filter) {
            throw new InternalServerErrorException('Unknown filter ' . $filterFqcn);
        }

        $filter->filter($qb, $fieldName, $value);
    }

    public function registerFilter(RequestFilterInterface $filter)
    {
        $this->filtersMap[get_class($filter)] = $filter;
    }

}