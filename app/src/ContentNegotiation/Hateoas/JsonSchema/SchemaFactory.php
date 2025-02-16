<?php
declare(strict_types=1);

namespace App\ContentNegotiation\Hateoas\JsonSchema;

use ApiPlatform\JsonSchema\Schema;
use ApiPlatform\JsonSchema\SchemaFactoryAwareInterface;
use ApiPlatform\JsonSchema\SchemaFactoryInterface;
use ApiPlatform\Metadata\Operation;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(decorates: 'api_platform.json_schema.schema_factory', priority: 20)]
final readonly class SchemaFactory implements SchemaFactoryInterface, SchemaFactoryAwareInterface
{
    public function __construct(private SchemaFactoryInterface $schemaFactory)
    {
        if ($this->schemaFactory instanceof SchemaFactoryAwareInterface) {
            $this->schemaFactory->setSchemaFactory($this);
        }
    }

    public function setSchemaFactory(SchemaFactoryInterface $schemaFactory): void
    {
        if ($this->schemaFactory instanceof SchemaFactoryAwareInterface) {
            $this->schemaFactory->setSchemaFactory($schemaFactory);
        }
    }

    public function buildSchema(string $className, string $format = 'json', string $type = Schema::TYPE_OUTPUT, ?Operation $operation = null, ?Schema $schema = null, ?array $serializerContext = null, bool $forceCollection = false): Schema
    {
        $schema = $this->schemaFactory->buildSchema($className, $format, $type, $operation, $schema, $serializerContext, $forceCollection);

        if ('json' !== $format) {
            return $schema;
        }

        if ('input' === $type) {
            return $schema;
        }

        if (($schema['type'] ?? '') === 'array') {
            // collection
            $items = $schema['items'];
            unset($schema['items']);

            $schema['type'] = 'object';
            $schema['properties'] = [
                'data' => [
                    'type' => 'array',
                    'items' => $items,
                ],
                'meta' => [
                    'type' => 'object',
                    'properties' => [
                        'totalItems' => [
                            'type' => 'integer',
                            'minimum' => 0,
                        ],
                        'currentPage' => [
                            'type' => 'integer',
                            'minimum' => 0,
                        ],
                        'itemsPerPage' => [
                            'type' => 'integer',
                            'minimum' => 0,
                        ],
                    ],
                    'required' => ['totalItems', 'currentPage', 'itemsPerPage'],
                ],
            ];
            $schema['required'] = ['data', 'meta'];

            return $schema;
        }

        return $schema;
    }
}
