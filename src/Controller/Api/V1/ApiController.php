<?php

namespace App\Controller\Api\V1;

use App\CircularReferableInterface;
use App\Component\Paginator\Paginator;
use App\Component\RequestFilter\RequestFilterService;
use App\Controller\Api\V1\Crud\BaseDeleteItemSetup;
use App\Controller\Api\V1\Crud\BaseGetItemSetup;
use App\Controller\Api\V1\Crud\BaseGetItemsSetup;
use App\Controller\Api\V1\Crud\BasePatchItemSetup;
use App\Controller\Api\V1\Crud\BasePostItemSetup;
use App\Exception\Api\NotFoundException;
use App\Exception\Api\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @OA\OpenApi(
 *     info=@OA\Info(
 *         title="My Project API",
 *         version="1.0"
 *     ),
 *     servers={
 *         @OA\Server(url=API_ENDPOINT_URL)
 *     },
 *     @OA\Components(securitySchemes={@OA\SecurityScheme(type="http", scheme="bearer", bearerFormat="JWT", securityScheme="bearerAuth")}),
 *     security={ { "bearerAuth"={} } }
 * )
 */
class ApiController extends AbstractController
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var NormalizerInterface
     */
    protected $normalizer;

    /**
     * @var DenormalizerInterface
     */
    protected $denormalizer;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var RequestFilterService
     */
    protected $requestFilter;

    public function __construct(
        RouterInterface $router,
        EntityManagerInterface $em,
        NormalizerInterface $normalizer,
        DenormalizerInterface $denormalizer,
        ValidatorInterface $validator,
        RequestFilterService $requestFilter
    )
    {
        $this->router        = $router;
        $this->em            = $em;
        $this->normalizer    = $normalizer;
        $this->denormalizer  = $denormalizer;
        $this->validator     = $validator;
        $this->requestFilter = $requestFilter;
    }

    /**
     * @Route("/login_check", name="api_login_check")
     */
    public function loginCheck()
    {

    }



    /**
     * @param Request $request
     * @param string $itemClass
     * @param BaseGetItemsSetup $getItemsSetup
     * @param array $additionalGroups
     *
     * @return JsonResponse
     */
    protected function handleGetItemsOperation(Request $request, string $itemClass, BaseGetItemsSetup $getItemsSetup = null, array $additionalGroups = [])
    {
        $qb = $this->em->createQueryBuilder()
            ->select('m')
            ->from($itemClass, 'm')
        ;

        if (!$getItemsSetup) {
            $getItemsSetup = new BaseGetItemsSetup();
        }

        $requiredRole = $getItemsSetup->requiredRole();

        if ($requiredRole) {
            $this->denyAccessUnlessGranted($requiredRole);
        }

        $getItemsSetup->modifyQueryBuilder($qb, $request);

        $this->processFiltering($request, $qb, $getItemsSetup);
        $this->processSorting($request, $qb);

        $itemsPerPage = (int)$request->query->get('itemsPerPage');

        if ($itemsPerPage === -1) {
            $data = $qb->getQuery()->getArrayResult();

            // convert id to string, to be consistent
            $data = array_map(function($item) { $item['id'] = (string)$item['id']; return $item; }, $data);

            return new JsonResponse([
                'status' => 'ok',
                'result' => $data
            ]);
        }

        $pager = $this->processPagination($request, $qb, $getItemsSetup);
        $items = $pager->getIterator()->getIterator()->getArrayCopy();

        $json  = $this->normalize($itemClass, $items, 'List', $additionalGroups, $getItemsSetup->overrideGroup());

        return $this->successResponse([
            'status' => 'ok',
            'result' => $json,
            'pager'  => $this->formatPagerData($pager)
        ]);
    }

    /**
     * @param string $id
     * @param string $itemClass
     * @param BaseGetItemSetup|null $setup
     * @param array $additionalGroups
     * @return JsonResponse
     */
    protected function handleGetItemOperation(string $id, string $itemClass, BaseGetItemSetup $setup = null, array $additionalGroups = [])
    {
        if (!$setup) {
            $setup = new BaseGetItemSetup();
        }

        $entity = $this->em->find($itemClass, $id);

        $requiredRole = $setup->requiredRole($entity);

        if ($requiredRole) {
            $this->denyAccessUnlessGranted($requiredRole, $entity);
        }

        if (!$entity) {
            throw new NotFoundException();
        }

        $json = $this->normalize($itemClass, $entity, 'Show', $additionalGroups, $setup->overrideGroup());

        return $this->successResponse($json);
    }

    /**
     * @param Request $request
     * @param string $itemClass
     * @param BasePostItemSetup|null $setup
     * @param array $additionalGroups
     * @return JsonResponse
     * @throws \Exception
     */
    protected function handlePostItemOperation(Request $request, string $itemClass, BasePostItemSetup $setup = null, array $additionalGroups = [])
    {
        if (!$setup) {
            $setup = new BasePostItemSetup();
        }

        $entity = new $itemClass();

        $data   = json_decode($request->getContent(), 1);
        $setup->changeSubmittedData($data, $request);
        $entity = $this->denormalize($data, $itemClass, $entity, 'Post', $additionalGroups);

        // validate
        $errors = $this->validator->validate($entity);

        if ($errors->count() > 0) {
            throw new ValidationException($errors);
        }

        $requiredRole = $setup->requiredRole($entity);

        if ($requiredRole) {
            $this->denyAccessUnlessGranted($requiredRole, $entity);
        }

        $conn = $this->em->getConnection();

        try {
            $conn->beginTransaction();

            $setup->beforeFlush($entity, $request);

            $this->em->persist($entity);
            $this->em->flush();

            $setup->afterFlush($entity, $request);

            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollBack();

            $setup->rollbackAfterFlush($entity, $request);
            $setup->rollbackBeforeFlush($entity, $request);

            throw $e;
        }

        $json  = $this->normalize($itemClass, $entity, 'Show', $additionalGroups);

        return $this->successResponse($json);
    }

    /**
     * @param string $id
     * @param Request $request
     * @param string $itemClass
     * @param BasePatchItemSetup|null $setup
     * @param array $additionalGroups
     * @return JsonResponse
     * @throws \Exception
     */
    protected function handlePatchItemOperation(string $id, Request $request, string $itemClass, BasePatchItemSetup $setup = null, array $additionalGroups = [])
    {
        if (!$setup) {
            $setup = new BasePatchItemSetup();
        }

        $entity = $this->em->find($itemClass, $id);

        if (!$entity) {
            throw new NotFoundException();
        }

        $requiredRole = $setup->requiredRole($entity);

        if ($requiredRole) {
            $this->denyAccessUnlessGranted($requiredRole, $entity);
        }

        $data = json_decode($request->getContent(), 1);
        $setup->changeSubmittedData($data, $request);
        $entity = $this->denormalize($data, $itemClass, $entity, 'Post', $additionalGroups);

        // validate
        $errors = $this->validator->validate($entity);

        if ($errors->count() > 0) {
            throw new ValidationException($errors);
        }

        $conn = $this->em->getConnection();

        try {
            $conn->beginTransaction();

            $setup->beforeFlush($entity, $request);

            $this->em->persist($entity);
            $this->em->flush();

            $setup->afterFlush($entity, $request);

            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollBack();

            $setup->rollbackAfterFlush($entity, $request);
            $setup->rollbackBeforeFlush($entity, $request);

            throw $e;
        }

        $json = $this->normalize($itemClass, $entity, 'Show', $additionalGroups);

        return $this->successResponse($json);
    }

    /**
     * @param string $id
     * @param string $itemClass
     * @param Request $request
     * @param BaseDeleteItemSetup|null $setup
     * @return JsonResponse
     * @throws \Exception
     */
    protected function handleDeleteItemOperation(string $id, string $itemClass, Request $request, BaseDeleteItemSetup $setup = null)
    {
        if (!$setup) {
            $setup = new BaseDeleteItemSetup();
        }

        $entity = $this->em->find($itemClass, $id);

        if (!$entity) {
            throw new NotFoundException();
        }

        $requiredRole = $setup->requiredRole($entity);

        if ($requiredRole) {
            $this->denyAccessUnlessGranted($requiredRole, $entity);
        }

        $conn = $this->em->getConnection();

        try {
            $conn->beginTransaction();

            $setup->beforeFlush($entity, $request);

            $this->em->remove($entity);
            $this->em->flush();

            $setup->afterFlush($entity, $request);

            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollBack();

            $setup->rollbackAfterFlush($entity, $request);
            $setup->rollbackBeforeFlush($entity, $request);

            throw $e;
        }

        return $this->successResponse();
    }

    protected function processFiltering(Request $request, QueryBuilder $qb, BaseGetItemsSetup $getItemsSetup)
    {
        $filterData = $request->get('filter');

        if ($filterData) {
            foreach ($filterData as $field => $val) {
                if (is_string($val)) {
                    $val = trim($val);
                }
                if ($val) {
                    $getItemsSetup->filterItems($qb, $field, $val, $request, $this->requestFilter);
                }
            }
        }
    }

    protected function processSorting(Request $request, QueryBuilder $qb)
    {
        $sortBy    = $request->get('sortBy', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        $qb->orderBy('m.' . $sortBy, $sortOrder);
    }

    /**
     * @param Request $request
     * @param QueryBuilder $qb
     * @param BaseGetItemsSetup $getItemsSetup
     *
     * @return Paginator
     */
    protected function processPagination(Request $request, QueryBuilder $qb, BaseGetItemsSetup $getItemsSetup)
    {
        $currentPage  = $request->get('currentPage', 1);
        $itemsPerPage =  $request->get('itemsPerPage', 20);

        if ($itemsPerPage == -1) {
            $itemsPerPage = PHP_INT_MAX;
        }

        $paginator = new Paginator($qb, $currentPage, $itemsPerPage, $getItemsSetup->isFetchJoinCollection());

        return $paginator;
    }

    /**
     * @param Paginator $paginator
     * @return array
     */
    protected function formatPagerData(Paginator $paginator)
    {
        $result = [
            'currentPage' => $paginator->getCurrentPage(),
            'totalPages'  => $paginator->getTotalPages(),
            'totalItems'  => $paginator->getTotalItems(),
        ];

        if ($paginator->hasPrevPage()) {
            $request['prevPage'] = $paginator->getPrevPage();
        }

        if ($paginator->hasNextPage()) {
            $request['nextPage'] = $paginator->getNextPage();
        }

        return $result;
    }

    /**
     * @param string $itemClass
     * @param $data
     * @param string $method
     * @param array $additionalMethods
     * @param null|string $overrideMethod
     *
     * @return array|bool|float|int|string
     */
    protected function normalize(string $itemClass, $data, string $method, array $additionalMethods = [], ?string $overrideMethod = null)
    {
        // serialization groups
        $reflection = new \ReflectionClass($itemClass);
        $groups[]   = $overrideMethod ?? $reflection->getShortName() . $method;
        $groups     = array_merge($groups, $additionalMethods);

        $context = [
            'groups' => $groups,
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
            AbstractObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function($object, $format, $context) {
                if ($object instanceof CircularReferableInterface) {
                    return $object->representCircularDependency($format, $context);
                }

                throw new CircularReferenceException(sprintf('Provide CircularReferableInterface interface for %s entity', get_class($object)));
            }
        ];

        return $this->normalizer->normalize($data, null, $context);
    }

    protected function denormalize(array $data, string $itemClass, $existingObject, string $method, array $additionalMethods = [])
    {
        // deserialization groups
        $reflection = new \ReflectionClass($itemClass);
        $groups[]   = $reflection->getShortName() . $method;
        $groups     = array_merge($groups, $additionalMethods);

        $context = [
            'groups' => $groups
        ];

        if ($existingObject) {
            $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $existingObject;
        }

        return $this->denormalizer->denormalize($data, $itemClass, 'json', $context);
    }

    protected function successResponse($data = null)
    {
        $env  = $this->getParameter('kernel.environment');
        $data = ['status' => 'ok', 'result' => $data];

        $encodeFlags = JSON_UNESCAPED_UNICODE;

        if ($env === 'dev') {
            $encodeFlags  = $encodeFlags | JSON_PRETTY_PRINT;
        }

        $data = json_encode($data, $encodeFlags);

        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }
}