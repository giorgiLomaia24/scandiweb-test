<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Category\Category;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class CategoryType
 *
 * Represents the GraphQL type definition for a category.
 */
class CategoryType extends ObjectType
{
    /**
     * CategoryType constructor.
     *
     * Defines the GraphQL fields for the category.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Category',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::int()),
                    'resolve' => fn(Category $category) => $category->getId(),
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn(Category $category) => $category->getName(),
                ],
            ],
        ]);
    }

    /**
     * Provides a singleton instance of the CategoryType.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }
}
