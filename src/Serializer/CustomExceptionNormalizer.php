<?php

namespace App\Serializer;

use App\Exception\Api\BaseApiException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Normalizer\ProblemNormalizer;

class CustomExceptionNormalizer extends ProblemNormalizer
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        parent::__construct($parameterBag->get('kernel.debug'), []);
        $this->parameterBag = $parameterBag;
    }

    /**
     * @inheritdoc
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $originalException = $context['exception'] ?? null;

        if ($originalException instanceof  BaseApiException) {
            $data = [
                'type'    => 'api-exception',
                'title'   => 'An error occurred',
                'status'  => $originalException->getStatusCode(),
                'code'    => $originalException->getCode(),
                'detail'  => $originalException->reason,
                'payload' => $originalException->payload
            ];

            $debug = $this->parameterBag->get('kernel.debug') && ($context['debug'] ?? true);

            if ($debug) {
                $data['class'] = $object->getClass();
                $data['trace'] = $object->getTrace();
            }

            return $data;
        } else {
            return parent::normalize($object, $format, $context);
        }
    }
}