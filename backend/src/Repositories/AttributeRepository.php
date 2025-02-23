<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use PDO;

/**
 * Class AttributeRepository
 *
 * Handles attribute-related database queries.
 */
class AttributeRepository
{
    /**
     * Retrieves attributes for a given product by its ID.
     *
     * @param string $productId The product ID.
     * @return array The list of attributes with values.
     */
    public static function getAttributesByProductId(string $productId): array
    {
        $db = Database::connect();

        // Fetch attributes for the product
        $stmt = $db->prepare("SELECT id, name, type FROM attributes WHERE product_id = ?");
        $stmt->execute([$productId]);
        $attributes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepare statement for fetching attribute values
        $valueStmt = $db->prepare("SELECT id, display_value, value FROM attribute_values WHERE attribute_id = ?");

        foreach ($attributes as &$attribute) {
            $valueStmt->execute([$attribute['id']]);
            $attribute['values'] = $valueStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        }

        return $attributes;
    }
}
