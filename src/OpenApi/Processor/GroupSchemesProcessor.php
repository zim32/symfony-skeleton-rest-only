<?php

namespace App\OpenApi\Processor;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;
use OpenApi\Analysis;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;


class GroupSchemesProcessor
{
    /**
     * @var ClassMetadataFactoryInterface
     */
    private $classMetadataFactory;

    public function __construct(ClassMetadataFactoryInterface $classMetadataFactory)
    {
        $this->classMetadataFactory = $classMetadataFactory;
    }

    function __invoke(Analysis $analysis)
    {
        if (false === is_object($analysis->openapi->components)) {
            return;
        }

        $schemas = $analysis->openapi->components->schemas;
        $inflector = new Inflector(new NoopWordInflector(), new NoopWordInflector());

        if (!is_array($schemas)) {
            return;
        }

        $newSchemas = [];

        foreach ($schemas as $schema) {
            if (false === is_array($schema->properties)) {
                continue;
            }

            $x = $schema->x;
            $fqcn = $x['fqcn'] ?? null;

            if (!$fqcn) {
                throw new \Exception(sprintf('Please specify x={"fqcn"="..."} parameter for schema %s', $schema->schema));
            }

            $classMetadata = $this->classMetadataFactory->getMetadataFor($fqcn);
            $attributesMetadata = $classMetadata->getAttributesMetadata();

            if (!$classMetadata) {
                throw new \Exception(sprintf('Can not get serializer metadata for class ', $fqcn));
            }

            if (isset($x['groups'])) {
                foreach ($x['groups'] as $group) {
                    $newSchema         = clone $schema;
                    $newSchemaName     = $inflector->classify($group);

                    $newSchema->schema = $newSchemaName;

                    $newProps = [];
                    foreach ($schema->properties as $property) {
                        $propX        = $property->x;
                        $propXGroups  = $propX['groups'] ?? [];
                        $propMetadata = $attributesMetadata[$property->property] ?? null;

                        if (!$propMetadata) {
                            throw new \Exception(sprintf('Can not get serializer groups metadata for property %s::%s', $fqcn, $property->property));
                        }

                        $groups = $propMetadata->getGroups();

                        if (
                            (!$groups || in_array($group, $groups)) &&
                            (!$propXGroups || in_array($group, $propXGroups))
                        ) {
                            $newProp = clone $property;
                            $newProps[] = $newProp;
                        }
                    }

                    $newSchema->properties = $newProps;
                    $newSchemas[] = $newSchema;
                }
            }
        }

        $analysis->openapi->components->schemas = $newSchemas;
    }
}