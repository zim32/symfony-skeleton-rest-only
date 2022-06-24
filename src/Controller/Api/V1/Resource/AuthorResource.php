<?php

namespace App\Controller\Api\V1\Resource;

use App\Entity\Author;
use App\Controller\Api\V1\ApiController;
use App\Entity\Book;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseDeleteItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseGetItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BaseGetItemsSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BasePatchItemSetup;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\Setup\BasePostItemSetup;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class AuthorResource extends ApiController
{
    const REQUIRED_GROUPS  = [
        'list'  => [Book::EmbeddedGroup],
        'show'  => [Book::EmbeddedGroup],
        'post'  => [Book::PostGroup],
        'patch' => [Book::PostGroup]
    ];
    
    #[Route(path: "/authors", methods: ["GET"], name: "get_authors")]
    #[OA\Get(
        path: "/authors", 
        operationId: "getAuthors",
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
                description: "Authors list", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"]),
                            new OA\Property(property: "result", type: "array", items: new OA\Items(ref: "#/components/schemas/AuthorList"))
                        ]
                    )
                )
            )
        ],
        tags: ["Authors"]
    )]
    public function getAuthors(Request $request): Response
    {
        return $this->handleGetItemsOperation($request, Author::class, new class extends BaseGetItemsSetup {

        }, self::REQUIRED_GROUPS['list']);
    }

    
    #[Route(path: "/authors/{id}", methods: ["GET"], name: "get_author", requirements: ["id"=>"\d+"])]
    #[OA\Get(
        path: "/authors/{id}", 
        operationId: "getAuthor", 
        parameters: [
            new OA\Parameter(in: "path", name: "id", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200, 
                description: "Author resource", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"]),
                            new OA\Property(property: "result", ref: "#/components/schemas/AuthorShow")
                        ]
                    )
                )
            )
        ],
        tags: ["Authors"]
    )]
    public function getAuthor(string $id, Request $request): Response
    {
        return $this->handleGetItemOperation($id, $request, Author::class, new class extends BaseGetItemSetup {

        }, self::REQUIRED_GROUPS['show']);
    }


    #[Route(path: "/authors", methods: ["POST"], name: "post_author")]
    #[OA\Post(
        path: "/authors", 
        operationId: "postAuthor",
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: "#/components/schemas/AuthorPost")
        ),
        responses: [
            new OA\Response(
                response: 200, 
                description: "New Author resource", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"]),
                            new OA\Property(property: "result", ref: "#/components/schemas/AuthorShow")
                        ]
                    )
                )
            )
        ],
        tags: ["Authors"]
    )]
    public function postAuthor(Request $request): Response
    {
        return $this->handlePostItemOperation($request, Author::class, new class extends BasePostItemSetup {

        }, self::REQUIRED_GROUPS['post'], self::REQUIRED_GROUPS['show']);
    }

    
    #[Route(path: "/authors/{id}", methods: ["PATCH"], name: "patch_author", requirements: ["id"=>"\d+"])]
    #[OA\Patch(
        path: "/authors/{id}", 
        operationId: "patchAuthor", 
        parameters: [
            new OA\Parameter(in: "path", name: "id", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: "#/components/schemas/AuthorPost")
        ),
        responses: [
            new OA\Response(
                response: 200, 
                description: "Modified Author resource", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"]),
                            new OA\Property(property: "result", ref: "#/components/schemas/AuthorShow")
                        ]
                    )
                )
            )
        ],     
        tags: ["Authors"]
    )]
    public function patchAuthor(string $id, Request $request): Response
    {
        return $this->handlePatchItemOperation($id, $request, Author::class, new class extends BasePatchItemSetup {
            public function requiredRole($entity)
            {
                return null;
            }
        }, self::REQUIRED_GROUPS['patch'], self::REQUIRED_GROUPS['show']);
    }


    #[Route(path: "/authors/{id}", methods: ["DELETE"], name: "delete_author", requirements: ["id"=>"\d+"])]
    #[OA\Delete(
        path: "/authors/{id}", 
        operationId: "deleteAuthor", 
        parameters: [
            new OA\Parameter(in: "path", name: "id", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200, 
                description: "Delete Author resource", 
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
        tags: ["Authors"]
    )]
    public function deleteAuthor(string $id, Request $request): Response
    {
        return $this->handleDeleteItemOperation($id, Author::class, $request, new class extends BaseDeleteItemSetup {

        });
    }

}