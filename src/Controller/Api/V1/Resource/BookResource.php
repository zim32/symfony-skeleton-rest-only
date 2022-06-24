<?php

namespace App\Controller\Api\V1\Resource;

use App\Entity\Book;
use App\Controller\Api\V1\ApiController;
use App\Entity\Author;
use App\Entity\Tag;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseDeleteItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseGetItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseGetItemsSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BasePatchItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BasePostItemSetup;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BookResource extends ApiController
{
    const REQUIRED_GROUPS  = [
        'list'  => [Author::EmbeddedGroup, Tag::EmbeddedGroup],
        'show'  => [Author::EmbeddedGroup, Tag::EmbeddedGroup],
        'post'  => [],
        'patch' => []
    ];
    
    #[Route(path: "/books", methods: ["GET"], name: "get_books")]
    #[OA\Get(
        path: "/books", 
        operationId: "getBooks",
        parameters: [
            new OA\Parameter(in: "query", name: "currentPage", schema: new OA\Schema(type: "integer", example: 1)),
            new OA\Parameter(in: "query", name: "itemsPerPage", schema: new OA\Schema(type: "integer", example: 10)),
            new OA\Parameter(in: "query", name: "sortBy", schema: new OA\Schema(type: "string", default: "id")),
            new OA\Parameter(in: "query", name: "sortOrder", schema: new OA\Schema(type: "string", default: "asc")),
            new OA\Parameter(in: "query", name: "filter", schema: new OA\Schema(type: "object"), style: "deepObject", explode: true)
        ],
        responses: [
            new OA\Response(
                response: 200, 
                description: "Books list", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"]),
                            new OA\Property(property: "result", type: "array", items: new OA\Items(ref: "#/components/schemas/BookList"))
                        ]
                    )
                )
            )
        ],
        tags: ["Books"]
    )]
    public function getBooks(Request $request): Response
    {
        return $this->handleGetItemsOperation($request, Book::class, new class extends BaseGetItemsSetup {
            public function getArrayResultFields(Request $request, AuthorizationCheckerInterface $authorizationChecker): array
            {
                return ['id', 'title'];
            }
        }, self::REQUIRED_GROUPS['list']);
    }

    
    #[Route(path: "/books/{id}", methods: ["GET"], name: "get_book")]
    #[OA\Get(
        path: "/books/{id}", 
        operationId: "getBook", 
        parameters: [
            new OA\Parameter(in: "path", name: "id", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200, 
                description: "Book resource", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"]),
                            new OA\Property(property: "result", ref: "#/components/schemas/BookShow")
                        ]
                    )
                )
            )
        ],
        tags: ["Books"]
    )]
    public function getBook(string $id, Request $request): Response
    {
        return $this->handleGetItemOperation($id, $request, Book::class, new class extends BaseGetItemSetup {
            
        }, self::REQUIRED_GROUPS['show']);
    }


    #[Route(path: "/books", methods: ["POST"], name: "post_book")]
    #[OA\Post(
        path: "/books", 
        operationId: "postBook",
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: "#/components/schemas/BookPost")
        ),
        responses: [
            new OA\Response(
                response: 200, 
                description: "New Book resource", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"]),
                            new OA\Property(property: "result", ref: "#/components/schemas/BookShow")
                        ]
                    )
                )
            )
        ],
        tags: ["Books"]
    )]
    public function postBook(Request $request): Response
    {
        return $this->handlePostItemOperation($request, Book::class, new class extends BasePostItemSetup {
            public function requiredRole($entity)
            {
                return null;
            }
        }, self::REQUIRED_GROUPS['post'], self::REQUIRED_GROUPS['show']);
    }

    
    #[Route(path: "/books/{id}", methods: ["PATCH"], name: "patch_book")]
    #[OA\Patch(
        path: "/books/{id}", 
        operationId: "patchBook", 
        parameters: [
            new OA\Parameter(in: "path", name: "id", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: "#/components/schemas/BookPost")
        ),
        responses: [
            new OA\Response(
                response: 200, 
                description: "Modified Book resource", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"]),
                            new OA\Property(property: "result", ref: "#/components/schemas/BookShow")
                        ]
                    )
                )
            )
        ],     
        tags: ["Books"]
    )]
    public function patchBook(string $id, Request $request): Response
    {
        return $this->handlePatchItemOperation($id, $request, Book::class, new class extends BasePatchItemSetup {
            public function requiredRole($entity)
            {
                return null;
            }
        }, self::REQUIRED_GROUPS['patch'], self::REQUIRED_GROUPS['show']);
    }


    #[Route(path: "/books/{id}", methods: ["DELETE"], name: "delete_book")]
    #[OA\Delete(
        path: "/books/{id}", 
        operationId: "deleteBook", 
        parameters: [
            new OA\Parameter(in: "path", name: "id", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200, 
                description: "Delete Book response", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"])
                        ]
                    )
                )
            )
        ],
        tags: ["Books"]
    )]
    public function deleteBook(string $id, Request $request): Response
    {
        return $this->handleDeleteItemOperation($id, Book::class, $request, new class extends BaseDeleteItemSetup {

        });
    }
}