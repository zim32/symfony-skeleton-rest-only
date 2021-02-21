<?php

namespace App\Controller\Api\V1\Crud;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Zim\Bundle\SymfonyRestHelperBundle\Component\RequestFilter\RequestFilterService;

class BaseGetItemsSetup
{

    public function modifyQueryBuilder(QueryBuilder $qb, Request $request)
    {

    }

    public function filterItems(QueryBuilder $qb, string $field, $value, Request $request, RequestFilterService $requestFilter)
    {
        throw new \Exception('Implement filterItems in subclasses');
    }

    public function isFetchJoinCollection()
    {
        return false;
    }

    public function serializerContext()
    {
        return [];
    }

    public function requiredRole()
    {
        return null;
    }

    public function overrideGroup()
    {
        return null;
    }
}