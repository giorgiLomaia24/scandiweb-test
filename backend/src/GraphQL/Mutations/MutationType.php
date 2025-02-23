<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\GraphQL\Resolvers\OrderResolver;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class MutationType
 *
 * Defines GraphQL mutations for the application.
 */
class MutationType extends ObjectType
{
    /**
     * MutationType constructor.
     *
     * Defines the createOrder mutation and input types.
     */
    public function __construct()
    {
        // Define input type for order items
        $orderItemInput = new InputObjectType([
            'name' => 'OrderItemInput',
            'fields' => [
                'product_id' => Type::nonNull(Type::string()),
                'quantity' => Type::nonNull(Type::int()),
                'attributes' => Type::listOf(new InputObjectType([
                    'name' => 'OrderAttributeInput',
                    'fields' => [
                        'name' => Type::nonNull(Type::string()),
                        'value' => Type::nonNull(Type::string()),
                    ],
                ])),
            ],
        ]);

        // Define the mutation type
        parent::__construct([
            'name' => 'Mutation',
            'fields' => [
                'createOrder' => [
                    'type' => new ObjectType([
                        'name' => 'OrderResponse',
                        'fields' => [
                            'order_id' => Type::int(),
                            'message' => Type::string(),
                        ],
                    ]),
                    'args' => [
                        'items' => Type::listOf($orderItemInput),
                    ],
                    'resolve' => fn($root, $args) => OrderResolver::createOrder($root, $args),
                ],
            ],
        ]);
    }
}
