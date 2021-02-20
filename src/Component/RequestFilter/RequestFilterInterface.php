<?php

namespace App\Component\RequestFilter;

use Doctrine\ORM\QueryBuilder;

interface RequestFilterInterface
{

    public function filter(QueryBuilder $qb, string $fieldName, $value);

}