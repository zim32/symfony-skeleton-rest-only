<?php

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ResourceDenormalizer implements DenormalizerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return $this->em->find($type, $data);
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, string $type, string $format = null)
    {
        if (false === is_scalar($data)) {
            return false;
        }

        if (!class_exists($type)) {
            return false;
        }

        $metadata = $this->em->getClassMetadata($type);

        if (!$metadata) {
            return false;
        }

        return true;
    }
}