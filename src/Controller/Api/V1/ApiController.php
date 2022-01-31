<?php

namespace App\Controller\Api\V1;

use OpenApi\Annotations as OA;
use Zim\Bundle\SymfonyRestHelperBundle\Controller\BaseCrudController;

/**
 * @OA\OpenApi(
 *     openapi="3.0.0",
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
class ApiController extends BaseCrudController
{



}