<?php

namespace App\Controller\Api\V1\Resource;

use App\Entity\Keyword;
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

class KeywordResource extends ApiController
{
    const REQUIRED_GROUPS  = [
        'list'  => ['BookEmbedded'],
        'show'  => ['BookEmbedded'],
        'post'  => [],
        'patch' => []
    ];

    /**
     * @Route(path="/keywords", methods={"GET"}, name="get_keywords")
     *
     * @OA\Get(
     *     path="/keywords",
     *     operationId="getKeywords",
     *     @OA\Parameter(in="query", name="currentPage",  schema={"type"="integer", "example"=1}),
     *     @OA\Parameter(in="query", name="itemsPerPage", schema={"type"="integer", "example"=10}),
     *     @OA\Parameter(in="query", name="sortBy", schema={"type"="string", "default"="id"}),
     *     @OA\Parameter(in="query", name="sortOrder", schema={"type"="string", "default"="asc"}),
     *     @OA\Parameter(in="query", name="filter", schema={"type"="object" }, style="deepObject", explode=true),
     *     @OA\Response(
     *         response="200",
     *         description="Keywords list",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", type="array", items=@OA\Items(ref="#/components/schemas/KeywordList"))
     *             )
     *         )
     *     ),
     *     tags={"Keywords"}
     * )
     * @param Request $request
     *
     * @return Response
     */
    public function getKeywords(Request $request): Response
    {
        return $this->handleGetItemsOperation($request, Keyword::class, new class extends BaseGetItemsSetup {

        }, self::REQUIRED_GROUPS['list']);
    }

    /**
     * @Route(path="/keywords/{id}", name="get_keyword", methods={"GET"})
     *
     * @OA\Get(
     *     path="/keywords/{id}",
     *     operationId="getKeyword",
     *     @OA\Parameter(in="path", name="id", schema={"type"="integer"}, required=true),
     *     @OA\Response(
     *         response="200",
     *         description="Keyword resource",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", ref="#/components/schemas/KeywordShow")
     *             )
     *         )
     *     ),
     *     tags={"Keywords"}
     * )
     *
     * @param string $id
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function getKeyword(string $id, Request $request): Response
    {
        return $this->handleGetItemOperation($id, $request, Keyword::class, new class extends BaseGetItemSetup {

        }, self::REQUIRED_GROUPS['show']);
    }


    /**
     * @Route(path="/keywords", name="post_keyword", methods={"POST"})
     *
     * @OA\Post(
     *     path="/keywords",
     *     operationId="postKeyword",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/KeywordPost")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="New Keyword resource",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", ref="#/components/schemas/KeywordShow")
     *             )
     *         )
     *     ),
     *     tags={"Keywords"}
     * )
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function postKeyword(Request $request): Response
    {
        return $this->handlePostItemOperation($request, Keyword::class, new class extends BasePostItemSetup {

        }, self::REQUIRED_GROUPS['post']);
    }

    /**
     * @Route(path="/keywords/{id}", name="patch_keyword", methods={"PATCH"})
     *
     * @OA\Patch(
     *     path="/keywords/{id}",
     *     operationId="patchKeyword",
     *     @OA\Parameter(in="path", name="id", schema={"type"="integer"}, required=true),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/KeywordPost")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Patch Keyword resource",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="status", type="string", enum={"ok", "error"}),
     *                  @OA\Property(property="result", ref="#/components/schemas/KeywordShow")
     *             )
     *         )
     *     ),
     *     tags={"Keywords"}
     * )
     *
     * @param string $id
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function patchKeyword(string $id, Request $request): Response
    {
        return $this->handlePatchItemOperation($id, $request, Keyword::class, new class extends BasePatchItemSetup {

        }, self::REQUIRED_GROUPS['patch']);
    }


    /**
    * @Route(path="/keywords/{id}", name="delete_keyword", methods={"DELETE"})
    *
    * @OA\Delete(
    *     path="/keywords/{id}",
    *     operationId="deleteKeyword",
    *     @OA\Parameter(in="path", name="id", schema={"type"="integer"}, required=true),
    *     @OA\Response(
    *         response="200",
    *         description="Delete Keyword resource",
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                  type="object",
    *                  @OA\Property(property="status", type="string", enum={"ok", "error"})
    *             )
    *         )
    *     ),
    *     tags={"Keywords"}
    * )
    *
    * @param string $id
    * @param Request $request
    * @return Response
    * @throws Exception
    */
    public function deleteKeyword(string $id, Request $request): Response
    {
        return $this->handleDeleteItemOperation($id, Keyword::class, $request, new class extends BaseDeleteItemSetup {

        });
    }

}