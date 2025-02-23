<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Models\Product\AbstractProduct;
use App\Repositories\ProductRepository;
use Exception;

/**
 * Class ProductResolver
 *
 * Resolves product-related GraphQL queries.
 */
class ProductResolver
{
    /**
     * Resolves all products, optionally filtering by category.
     *
     * @param mixed $root The root query context.
     * @param array $args The GraphQL arguments, expecting 'category_id' as an optional filter.
     * @return array The list of products or an error response.
     */
    public static function resolveAllProducts(mixed $root, array $args): array
    {
        try {
            $products = ProductRepository::getAllProducts($args['category_id'] ?? null);

            // Check if the repository returned an error
            if (isset($products['error']) && $products['error'] === true) {
                throw new Exception($products['message']);
            }

            return $products;
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => "GraphQL Resolver Error in resolveAllProducts(): " . $e->getMessage(),
            ];
        }
    }

    /**
     * Resolves a single product by ID.
     *
     * @param mixed $root The root query context.
     * @param array $args The GraphQL arguments, expecting 'id' as a required parameter.
     * @return AbstractProduct|null The product object or null if not found.
     */
    public static function resolveProductById(mixed $root, array $args): ?AbstractProduct
    {
        try {
            if (!isset($args['id']) || empty($args['id'])) {
                throw new Exception("Product ID is required.");
            }

            $product = ProductRepository::getProductById($args['id']);

            if (!$product) {
                throw new Exception("Product not found with ID: " . $args['id']);
            }

            return $product;
        } catch (Exception $e) {
            return null;
        }
    }
}
