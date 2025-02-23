<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\GraphQL\Resolvers\CategoryResolver;
use App\GraphQL\Resolvers\ProductResolver;
use App\GraphQL\Types\CategoryType;
use App\GraphQL\Types\ProductType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class QueryType
 *
 * Defines GraphQL queries for fetching products and categories.
 */
class QueryType extends ObjectType
{
    /**
     * QueryType constructor.
     *
     * Defines queries for products and categories.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Query',
            'fields' => [
                'products' => [
                    'type' => Type::listOf(ProductType::getInstance()),
                    'args' => [
                        'category_id' => Type::string(),
                    ],
                    'resolve' => fn($root, $args) => ProductResolver::resolveAllProducts($root, $args),
                ],
                'product' => [
                    'type' => ProductType::getInstance(),
                    'args' => [
                        'id' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => fn($root, $args) => ProductResolver::resolveProductById($root, $args),
                ],
                'categories' => [
                    'type' => Type::listOf(CategoryType::getInstance()), 
                    'resolve' => fn() => CategoryResolver::resolveAllCategories(),
                ],
            ],
        ]);
    }
}
