<?php

namespace App\Controller\Api\V1\Resource;

use App\Entity\BookAuthor;
use App\Controller\Api\V1\ApiController;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseDeleteItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseGetItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseGetItemsSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BasePatchItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BasePostItemSetup;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Exception;

class BookAuthorResource extends ApiController
{
    const REQUIRED_GROUPS  = [
        'list'  => ['BookEmbedded'],
        'show'  => ['BookEmbedded'],
        'post'  => ['BookPost'],
        'patch' => ['BookPost']
    ];

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
     *         description="Book authors list",
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
     * @return Response
     */
    public function getBookAuthors(Request $request): Response
    {
        return $this->handleGetItemsOperation($request, BookAuthor::class, new class extends BaseGetItemsSetup {

        }, self::REQUIRED_GROUPS['list']);
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
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function getBookAuthor(string $id, Request $request): Response
    {
        return $this->handleGetItemOperation($id, $request, BookAuthor::class, new class extends BaseGetItemSetup {

        }, self::REQUIRED_GROUPS['show']);
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
     * @return Response
     * @throws Exception
     */
    public function postBookAuthor(Request $request): Response
    {
        return $this->handlePostItemOperation($request, BookAuthor::class, new class extends BasePostItemSetup {

        }, self::REQUIRED_GROUPS['post']);
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
     * @return Response
     * @throws Exception
     */
    public function patchBookAuthor(string $id, Request $request): Response
    {
        return $this->handlePatchItemOperation($id, $request, BookAuthor::class, new class extends BasePatchItemSetup {

        }, self::REQUIRED_GROUPS['patch'], self::REQUIRED_GROUPS['show']);
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
    * @return Response
    * @throws Exception
    */
    public function deleteBookAuthor(string $id, Request $request): Response
    {
        return $this->handleDeleteItemOperation($id, BookAuthor::class, $request, new class extends BaseDeleteItemSetup {

        });
    }

}