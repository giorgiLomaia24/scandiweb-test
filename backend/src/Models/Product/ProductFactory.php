<?php

declare(strict_types=1);

namespace App\Models\Product;

use Exception;

/**
 * Factory class for creating Product instances.
 */
class ProductFactory
{
    /**
     * Creates a Product instance based on category.
     *
     * @param array 
     * @return AbstractProduct
     * @throws Exception 
     */
    public static function create(array $data): AbstractProduct
    {
        if (!isset($data['id']) || empty($data['id'])) {
            throw new Exception("ProductFactory Error: Missing `id`. Data received: " . json_encode($data));
        }

        if (!isset($data['name']) || empty($data['name'])) {
            throw new Exception("ProductFactory Error: Missing `name`. Data received: " . json_encode($data));
        }

        $category = strtolower($data['category_name']);

        $product = match ($category) {
            'clothes' => new Clothes($data),
            'tech' => new Tech($data),
            default => throw new Exception("Unknown product category: $category")
        };

        if (!$product instanceof AbstractProduct) {
            throw new Exception("ProductFactory Error: Expected AbstractProduct, got " . gettype($product));
        }

        return $product;
    }
}
