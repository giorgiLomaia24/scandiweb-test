<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Repositories\CategoryRepository;
use Exception;

/**
 * Class CategoryResolver
 *
 * Resolves category-related GraphQL queries.
 */
class CategoryResolver
{
    /**
     * Resolves all categories.
     *
     * @return array The list of categories or an error message.
     */
    public static function resolveAllCategories(): array
    {
        try {
            return CategoryRepository::getAllCategories();
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => "GraphQL Error in resolveAllCategories: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Resolves a category by ID.
     *
     * @param mixed $root The root query context.
     * @param array $args The GraphQL arguments, expecting an 'id'.
     * @return object|null The category object or null if not found.
     * @throws Exception If the 'id' argument is missing.
     */
    public static function resolveCategoryById($root, array $args): ?object
    {
        try {
            if (!isset($args['id'])) {
                throw new Exception("Category ID is required.");
            }

            $category = CategoryRepository::getCategoryById((int) $args['id']);

            if (!$category) {
                throw new Exception("Category not found with ID: " . $args['id']);
            }

            return $category;
        } catch (Exception $e) {
            return (object) [
                'error' => true,
                'message' => "GraphQL Error in resolveCategoryById: " . $e->getMessage(),
            ];
        }
    }
}
