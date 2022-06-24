<?php

namespace App\Controller\Api\V1\Resource;

use App\Entity\Tag;
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

class TagResource extends ApiController
{
    const REQUIRED_GROUPS  = [
        'list'  => [Book::EmbeddedGroup],
        'show'  => [Book::EmbeddedGroup],
        'post'  => [],
        'patch' => []
    ];
    
    #[Route(path: "/tags", methods: ["GET"], name: "get_tags")]
    #[OA\Get(
        path: "/tags", 
        operationId: "getTags",
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
                description: "Tags list", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"]),
                            new OA\Property(property: "result", type: "array", items: new OA\Items(ref: "#/components/schemas/TagList"))
                        ]
                    )
                )
            )
        ],
        tags: ["Tags"]
    )]
    public function getTags(Request $request): Response
    {
        return $this->handleGetItemsOperation($request, Tag::class, new class extends BaseGetItemsSetup {

        }, self::REQUIRED_GROUPS['list']);
    }

    
    #[Route(path: "/tags/{id}", methods: ["GET"], name: "get_tag", requirements: ["id"=>"\d+"])]
    #[OA\Get(
        path: "/tags/{id}", 
        operationId: "getTag", 
        parameters: [
            new OA\Parameter(in: "path", name: "id", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200, 
                description: "Tag resource", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"]),
                            new OA\Property(property: "result", ref: "#/components/schemas/TagShow")
                        ]
                    )
                )
            )
        ],
        tags: ["Tags"]
    )]
    public function getTag(string $id, Request $request): Response
    {
        return $this->handleGetItemOperation($id, $request, Tag::class, new class extends BaseGetItemSetup {

        }, self::REQUIRED_GROUPS['show']);
    }


    #[Route(path: "/tags", methods: ["POST"], name: "post_tag")]
    #[OA\Post(
        path: "/tags", 
        operationId: "postTag",
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: "#/components/schemas/TagPost")
        ),
        responses: [
            new OA\Response(
                response: 200, 
                description: "New Tag resource", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"]),
                            new OA\Property(property: "result", ref: "#/components/schemas/TagShow")
                        ]
                    )
                )
            )
        ],
        tags: ["Tags"]
    )]
    public function postTag(Request $request): Response
    {
        return $this->handlePostItemOperation($request, Tag::class, new class extends BasePostItemSetup {
            public function requiredRole($entity)
            {
                return null;
            }
        }, self::REQUIRED_GROUPS['post'], self::REQUIRED_GROUPS['show']);
    }

    
    #[Route(path: "/tags/{id}", methods: ["PATCH"], name: "patch_tag", requirements: ["id"=>"\d+"])]
    #[OA\Patch(
        path: "/tags/{id}", 
        operationId: "patchTag", 
        parameters: [
            new OA\Parameter(in: "path", name: "id", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: "#/components/schemas/TagPost")
        ),
        responses: [
            new OA\Response(
                response: 200, 
                description: "Modified Tag resource", 
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "status", type: "string", enum: ["ok", "error"]),
                            new OA\Property(property: "result", ref: "#/components/schemas/TagShow")
                        ]
                    )
                )
            )
        ],     
        tags: ["Tags"]
    )]
    public function patchTag(string $id, Request $request): Response
    {
        return $this->handlePatchItemOperation($id, $request, Tag::class, new class extends BasePatchItemSetup {
            public function requiredRole($entity)
            {
                return null;
            }
        }, self::REQUIRED_GROUPS['patch'], self::REQUIRED_GROUPS['show']);
    }


    #[Route(path: "/tags/{id}", methods: ["DELETE"], name: "delete_tag", requirements: ["id"=>"\d+"])]
    #[OA\Delete(
        path: "/tags/{id}", 
        operationId: "deleteTag", 
        parameters: [
            new OA\Parameter(in: "path", name: "id", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200, 
                description: "Delete Tag resource", 
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
        tags: ["Tags"]
    )]
    public function deleteTag(string $id, Request $request): Response
    {
        return $this->handleDeleteItemOperation($id, Tag::class, $request, new class extends BaseDeleteItemSetup {

        });
    }

}