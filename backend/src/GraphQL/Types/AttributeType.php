<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class AttributeType
 *
 * Represents the GraphQL type definition for product attributes.
 */
class AttributeType extends ObjectType
{
    private static ?self $instance = null;

    /**
     * Provides a singleton instance of the AttributeType.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * AttributeType constructor.
     *
     * Defines the GraphQL fields for an attribute.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Attribute',
            'fields' => [
                'id' => Type::nonNull(Type::int()),
                'name' => Type::nonNull(Type::string()),
                'type' => Type::nonNull(Type::string()),
                'values' => Type::listOf(
                    new ObjectType([
                        'name' => 'AttributeValue',
                        'fields' => [
                            'id' => Type::nonNull(Type::int()),
                            'display_value' => Type::string(),
                            'value' => Type::string(),
                        ],
                    ])
                ),
            ],
        ]);
    }
}
