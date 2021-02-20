<?php

namespace App\Component\RequestFilter\Filter;

use App\Component\RequestFilter\RequestFilterInterface;
use App\Exception\Api\InternalServerErrorException;
use Doctrine\ORM\QueryBuilder;

class NumberFilter implements RequestFilterInterface
{

    public function filter(QueryBuilder $qb, string $fieldName, $value)
    {
        $type        = key($value);
        $value       = (int)$value[$type];
        $exprLiteral = $qb->expr()->literal($value);

        switch ($type) {
            case 'eq':
                $qb->andWhere($qb->expr()->eq($fieldName, $exprLiteral));
                break;
            case 'gte':
                $qb->andWhere($qb->expr()->gte($fieldName, $exprLiteral));
                break;
            case 'gt':
                $qb->andWhere($qb->expr()->gt($fieldName, $exprLiteral));
                break;
            case 'lte':
                $qb->andWhere($qb->expr()->lte($fieldName, $exprLiteral));
                break;
            case 'lt':
                $qb->andWhere($qb->expr()->lt($fieldName, $exprLiteral));
                break;
            default:
                throw new InternalServerErrorException(sprintf('Unsupported filter operator %s', $type));
        }
    }

}