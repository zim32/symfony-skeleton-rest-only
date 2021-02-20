<?php

namespace App\Controller\Api\V1;

use App\OpenApi\Processor\GroupSchemesProcessor;
use OpenApi\Analysis;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;

/**
 * @Route(path="/doc")
 */
class SwaggerController extends AbstractController
{
    /**
     * @Route(path="/", name="api_v1_doc")
     */
    public function doc()
    {
        $response = $this->render('Swagger/ui.html.twig', ['schemaUrl' => API_SCHEMA_URL]);
        $response->headers->set('content-type', 'text/html');

        return $response;
    }

    /**
     * @Route(path="/schema", name="api_v1_schema")
     * @param Request $request
     * @param ClassMetadataFactoryInterface $classMetadataFactory
     * @return Response
     */
    public function schema(Request $request, ClassMetadataFactoryInterface $classMetadataFactory)
    {
        $processors   = Analysis::processors();
        $processors[] = new GroupSchemesProcessor($classMetadataFactory);

        $openApi = \OpenApi\scan([__DIR__, __DIR__ . '/../../../Entity'], ['processors' => $processors]);

        $format = $request->query->get('format', 'yaml');

        switch ($format) {
            case 'yaml':
                $content = $openApi->toYaml();
                return new Response($content, 200, ['content-type' => 'text/plain']);
            case 'json':
                $content = $openApi->toJson();
                return new Response($content, 200, ['content-type' => 'application/json']);
        }
    }
}