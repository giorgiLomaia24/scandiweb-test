<?php

declare (strict_types = 1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\Product\AbstractProduct;
use App\Models\Product\ProductFactory;
use Exception;
use PDO;

/**
 * Class ProductRepository
 *
 * Handles product-related database queries.
 */
class ProductRepository
{
    /**
     * Retrieves all products, optionally filtered by category.
     *
     * @param string|null $categoryId The category ID, or null for all products.
     * @return AbstractProduct[] The list of products.
     * @throws Exception If a product is missing essential data.
     */
    public static function getAllProducts(?string $categoryId = null): array
    {
        $db = Database::connect();
        $query = "
            SELECT p.*, c.name AS category_name,
                COALESCE((SELECT JSON_ARRAYAGG(image_url) FROM product_images WHERE product_id = p.id), '[]') AS gallery,
                pr.amount, pr.currency_label, pr.currency_symbol
            FROM products p
            JOIN categories c ON p.category_id = c.id
            LEFT JOIN prices pr ON p.id = pr.product_id
        ";

        if ($categoryId && $categoryId !== "all") {
            $query .= " WHERE p.category_id = :category_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['category_id' => (int) $categoryId]);
        } else {
            $stmt = $db->query($query);
        }

        $productsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($productsData as $product) {
            if (!isset($product['id']) || empty($product['id'])) {
                throw new Exception("ProductRepository Error: Missing `id` in database query.");
            }
        }

        return array_map(fn($data) => ProductFactory::create(self::formatProductData($data)), $productsData);
    }

    /**
     * Retrieves a single product by its ID.
     *
     * @param string $id The product ID.
     * @return AbstractProduct|null The product object or null if not found.
     */
    public static function getProductById(string $id): ?AbstractProduct
    {
        $db = Database::connect();
        $stmt = $db->prepare("
            SELECT p.*, c.name AS category_name,
                (SELECT JSON_ARRAYAGG(image_url) FROM product_images WHERE product_id = p.id) AS gallery,
                pr.amount, pr.currency_label, pr.currency_symbol
            FROM products p
            JOIN categories c ON p.category_id = c.id
            LEFT JOIN prices pr ON p.id = pr.product_id
            WHERE p.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $productData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $productData ? ProductFactory::create(self::formatProductData($productData)) : null;
    }

    /**
     * Formats raw database data into a structured product array.
     *
     * @param array $data The raw product data.
     * @return array The formatted product data.
     * @throws Exception If gallery decoding fails.
     */
    private static function formatProductData(array $data): array
    {
        $data['description'] = $data['description'] ?? '';

        $rawGallery = $data['gallery'] ?? '[]';
        $decodedGallery = json_decode($rawGallery, true);

        if (!is_array($decodedGallery)) {
            throw new Exception("Gallery Decoding Error: " . json_encode($rawGallery));
        }

        $data['gallery'] = $decodedGallery;

        $data['prices'] = [
            [
                'amount' => $data['amount'],
                'currency_label' => $data['currency_label'],
                'currency_symbol' => $data['currency_symbol'],
            ],
        ];

        unset($data['amount'], $data['currency_label'], $data['currency_symbol']);

        // ✅ Ensure category_id is included
        $data['category_id'] = $data['category_id'] ?? null;

        return $data;
    }

}
