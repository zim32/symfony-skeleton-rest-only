<?php

namespace App\Controller\Api\V1\Resource;

use App\Controller\Api\V1\ApiController;
use App\Entity\Book;
use App\Entity\BookAuthor;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Zim\Bundle\SymfonyRestHelperBundle\Component\RequestFilter\Filter\NumberFilter;
use Zim\Bundle\SymfonyRestHelperBundle\Component\RequestFilter\RequestFilterService;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseDeleteItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseGetItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseGetItemsSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BasePatchItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BasePostItemSetup;

class BookAuthorResource extends ApiController
{

    /**
     * @Route(path="/book-authors", methods={"GET"}, name="get_book_authors")
     *
     * @OA\Get(
     *     path="/book-authors",
     *     operationId="getBookAuthors",
     *     @OA\Parameter(in="query", name="currentPage",  schema={"type"="integer", "example"=1}),
     *     @OA\Parameter(in="query", name="itemsPerPage", schema={"type"="integer", "example"=10}),
     *     @OA\Parameter(in="query", name="sortBy", schema={"type"="string", "default"="id"}),
     *     @OA\Parameter(in="query", name="sortOrder", schema={"type"="string", "default"="asc"}),
     *     @OA\Parameter(in="query", name="filter", schema={"type"="object" }, style="deepObject", explode=true),
     *     @OA\Response(
     *         response="200",
     *         description="Book authorss list",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", type="array", items=@OA\Items(ref="#/components/schemas/BookAuthorList"))
     *             )
     *         )
     *     ),
     *     tags={"BookAuthors"}
     * )
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getBookAuthors(Request $request)
    {
        return $this->handleGetItemsOperation($request, BookAuthor::class, new class extends BaseGetItemsSetup {

            public function filterItems(QueryBuilder $qb, string $field, $value, Request $request, RequestFilterService $requestFilter)
            {
                switch ($field) {
                    case 'age':
                        $requestFilter->filter(NumberFilter::class, $qb, 'm.'.$field, $value);
                        break;
                }
            }

        });
    }

    /**
     * @Route(path="/book-authors/{id}", name="get_book_author", methods={"GET"})
     *
     * @OA\Get(
     *     path="/book-authors/{id}",
     *     operationId="getBookAuthor",
     *     @OA\Parameter(in="path", name="id", schema={"type"="integer"}, required=true),
     *     @OA\Response(
     *         response="200",
     *         description="Book author resource",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", ref="#/components/schemas/BookAuthorShow")
     *             )
     *         )
     *     ),
     *     tags={"BookAuthors"}
     * )
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getBookAuthor(string $id)
    {
        return $this->handleGetItemOperation($id, BookAuthor::class, new class extends BaseGetItemSetup {

        }, ['BookAuthorShow(Book)']);
    }

    /**
     * @Route(path="/book-authors/{id}/books", name="get_book_author_books", methods={"GET"})
     *
     * @OA\Get(
     *     path="/book-authors/{id}/books",
     *     operationId="getBookAuthorBooks",
     *     @OA\Parameter(in="path", name="id", schema={"type"="integer"}, required=true),
     *     @OA\Response(
     *         response="200",
     *         description="Book author resource",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", type="array", items=@OA\Items(ref="#/components/schemas/BookAuthorShow(Book)"))
     *             )
     *         )
     *     ),
     *     tags={"BookAuthors"}
     * )
     *
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function getBookAuthorBooks(string $id, Request $request)
    {
        return $this->handleGetItemsOperation($request, Book::class, new class($id) extends BaseGetItemsSetup {

            /**
             * @var string
             */
            private $authorID;

            public function __construct(string $authorID)
            {
                $this->authorID = $authorID;
            }

            public function modifyQueryBuilder(QueryBuilder $qb, Request $request)
            {
                $qb->andWhere('m.author = :author');
                $qb->setParameter('author', $this->authorID);
            }

            public function overrideGroup()
            {
                return 'BookAuthorShow(Book)';
            }

        });
    }

    /**
     * @Route(path="/book-authors", name="post_book_author", methods={"POST"})
     *
     * @OA\Post(
     *     path="/book-authors",
     *     operationId="postBookAuthor",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/BookAuthorPost")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="New Book author resource",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", ref="#/components/schemas/BookAuthorShow")
     *             )
     *         )
     *     ),
     *     tags={"BookAuthors"}
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function postBookAuthor(Request $request)
    {
        return $this->handlePostItemOperation($request, BookAuthor::class, new class extends BasePostItemSetup {

            public function requiredRole($entity)
            {
                return null;
            }

        });
    }

    /**
     * @Route(path="/book-authors/{id}", name="patch_book_author", methods={"PATCH"})
     *
     * @OA\Patch(
     *     path="/book-authors/{id}",
     *     operationId="patchBookAuthor",
     *     @OA\Parameter(in="path", name="id", schema={"type"="integer"}, required=true),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/BookAuthorPost")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Patch Book author resource",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", ref="#/components/schemas/BookAuthorShow")
     *             )
     *         )
     *     ),
     *     tags={"BookAuthors"}
     * )
     *
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function patchBookAuthor(string $id, Request $request)
    {
        return $this->handlePatchItemOperation($id, $request, BookAuthor::class, new class extends BasePatchItemSetup {



        });
    }


    /**
    * @Route(path="/book-authors/{id}", name="delete_book_author", methods={"DELETE"})
    *
    * @OA\Delete(
    *     path="/book-authors/{id}",
    *     operationId="deleteBookAuthor",
    *     @OA\Parameter(in="path", name="id", schema={"type"="integer"}, required=true),
    *     @OA\Response(
    *         response="200",
    *         description="Delete Book author resource",
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                  type="object",
    *                  @OA\Property(property="status", type="string", enum={"ok", "error"})
    *             )
    *         )
    *     ),
    *     tags={"BookAuthors"}
    * )
    *
    * @param string $id
    * @param Request $request
    * @return JsonResponse
    */
    public function deleteBookAuthor(string $id, Request $request)
    {
        return $this->handleDeleteItemOperation($id, BookAuthor::class, $request, new class extends BaseDeleteItemSetup {
            public function requiredRole($entity)
            {
                return null;
            }

        });
    }

}