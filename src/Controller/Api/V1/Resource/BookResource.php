<?php

namespace App\Controller\Api\V1\Resource;

use App\Controller\Api\V1\ApiController;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseDeleteItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseGetItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseGetItemsSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BasePatchItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BasePostItemSetup;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class BookResource extends ApiController
{

    /**
     * @Route(path="/books", methods={"GET"}, name="get_books")
     *
     * @OA\Get(
     *     path="/books",
     *     operationId="getBooks",
     *     @OA\Parameter(in="query", name="currentPage",  schema={"type"="integer", "example"=1}),
     *     @OA\Parameter(in="query", name="itemsPerPage", schema={"type"="integer", "example"=10}),
     *     @OA\Parameter(in="query", name="sortBy", schema={"type"="string", "default"="id"}),
     *     @OA\Parameter(in="query", name="sortOrder", schema={"type"="string", "default"="asc"}),
     *     @OA\Parameter(in="query", name="filter", schema={"type"="object" }, style="deepObject", explode=true),
     *     @OA\Response(
     *         response="200",
     *         description="Books list",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", type="array", items=@OA\Items(ref="#/components/schemas/BookList"))
     *             )
     *         )
     *     ),
     *     tags={"Books"}
     * )
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getBooks(Request $request)
    {
        return $this->handleGetItemsOperation($request, Book::class, new class extends BaseGetItemsSetup {


        }, ['BookAuthorList']);
    }

    /**
     * @Route(path="/books/{id}", name="get_book", methods={"GET"})
     *
     * @OA\Get(
     *     path="/books/{id}",
     *     operationId="getBook",
     *     @OA\Parameter(in="path", name="id", schema={"type"="integer"}, required=true),
     *     @OA\Response(
     *         response="200",
     *         description="Book resource",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", ref="#/components/schemas/BookShow")
     *             )
     *         )
     *     ),
     *     tags={"Books"}
     * )
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getBook(string $id)
    {
        return $this->handleGetItemOperation($id, Book::class, new class extends BaseGetItemSetup {

        }, ['BookAuthorList']);
    }


    /**
     * @Route(path="/books", name="post_book", methods={"POST"})
     *
     * @OA\Post(
     *     path="/books",
     *     operationId="postBook",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/BookPost")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="New Book resource",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", ref="#/components/schemas/BookShow")
     *             )
     *         )
     *     ),
     *     tags={"Books"}
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function postBook(Request $request)
    {
        return $this->handlePostItemOperation($request, Book::class, new class extends BasePostItemSetup {


        });
    }

    /**
     * @Route(path="/books/{id}", name="patch_book", methods={"PATCH"})
     *
     * @OA\Patch(
     *     path="/books/{id}",
     *     operationId="patchBook",
     *     @OA\Parameter(in="path", name="id", schema={"type"="integer"}, required=true),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/BookPost")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Patch Book resource",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", ref="#/components/schemas/BookShow")
     *             )
     *         )
     *     ),
     *     tags={"Books"}
     * )
     *
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function patchBook(string $id, Request $request)
    {
        return $this->handlePatchItemOperation($id, $request, Book::class, new class extends BasePatchItemSetup {

        });
    }


    /**
    * @Route(path="/books/{id}", name="delete_book", methods={"DELETE"})
    *
    * @OA\Delete(
    *     path="/books/{id}",
    *     operationId="deleteBook",
    *     @OA\Parameter(in="path", name="id", schema={"type"="integer"}, required=true),
    *     @OA\Response(
    *         response="200",
    *         description="Delete Book resource",
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                  type="object",
    *                  @OA\Property(property="status", type="string", enum={"ok", "error"})
    *             )
    *         )
    *     ),
    *     tags={"Books"}
    * )
    *
    * @param string $id
    * @param Request $request
    * @return JsonResponse
    */
    public function deleteBook(string $id, Request $request)
    {
        return $this->handleDeleteItemOperation($id, Book::class, $request, new class extends BaseDeleteItemSetup {

        });
    }

}