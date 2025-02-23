<?php

declare (strict_types = 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Database\Database;

/**
 * Class Seeder
 *
 * Seeds the database with initial data from a JSON file.
 */
class Seeder
{
    /**
     * @var PDO Holds the database connection instance.
     */
    private PDO $db;

    /**
     * Seeder constructor.
     * Initializes the database connection.
     */
    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Seeds the database with categories and products.
     *
     * @throws Exception If JSON file is missing or invalid.
     */
    public function seed(): void
    {
        $jsonFilePath = __DIR__ . '/data/data.json';

        if (!file_exists($jsonFilePath)) {
            throw new Exception("Error: data.json file not found at $jsonFilePath");
        }

        $jsonData = file_get_contents($jsonFilePath);
        $data = json_decode($jsonData, true);

        if (!$data || !isset($data['data'])) {
            throw new Exception("Error: Invalid data.json format");
        }

        $this->seedCategories($data['data']['categories']);
        $this->seedProducts($data['data']['products']);
    }

    /**
     * Seeds categories table.
     *
     * @param array $categories The categories to seed.
     */
    private function seedCategories(array $categories): void
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (:name)
                                        ON DUPLICATE KEY UPDATE name=name");
            foreach ($categories as $category) {
                $stmt->execute(['name' => $category['name']]);
            }
        } catch (PDOException $e) {
            error_log("Seeder Error in seedCategories: " . $e->getMessage());
        }
    }

    /**
     * Seeds products, including images, attributes, and prices.
     *
     * @param array $products The products to seed.
     */
    private function seedProducts(array $products): void
    {
        try {
            $this->db->beginTransaction();

            $productStmt = $this->db->prepare("
                INSERT INTO products (id, name, description, category_id, in_stock, brand)
                VALUES (:id, :name, :description, (SELECT id FROM categories WHERE name=:category), :in_stock, :brand)
            ");

            $imageStmt = $this->db->prepare("
                INSERT INTO product_images (product_id, image_url) VALUES (:product_id, :image_url)
            ");

            $attributeStmt = $this->db->prepare("
                INSERT INTO attributes (product_id, name, type) VALUES (:product_id, :name, :type)
            ");

            $attributeValueStmt = $this->db->prepare("
                INSERT INTO attribute_values (attribute_id, display_value, value)
                VALUES (:attribute_id, :display_value, :value)
            ");

            $priceStmt = $this->db->prepare("
                INSERT INTO prices (product_id, amount, currency_label, currency_symbol)
                VALUES (:product_id, :amount, :currency_label, :currency_symbol)
            ");

            foreach ($products as $product) {
                $productStmt->execute([
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'category' => $product['category'],
                    'in_stock' => $product['inStock'] ? 1 : 0,
                    'brand' => $product['brand'],
                ]);

                // Insert product images
                foreach ($product['gallery'] as $imageUrl) {
                    $imageStmt->execute(['product_id' => $product['id'], 'image_url' => $imageUrl]);
                }

                // Insert attributes
                foreach ($product['attributes'] as $attribute) {
                    $attributeStmt->execute([
                        'product_id' => $product['id'],
                        'name' => $attribute['id'],
                        'type' => $attribute['type'],
                    ]);

                    // Insert attribute values
                    foreach ($attribute['items'] as $item) {
                        $attributeValueStmt->execute([
                            'attribute_id' => $this->getAttributeId($product['id'], $attribute['id']),
                            'display_value' => $item['displayValue'],
                            'value' => $item['value'],
                        ]);
                    }
                }

                // Insert prices
                foreach ($product['prices'] as $price) {
                    $priceStmt->execute([
                        'product_id' => $product['id'],
                        'amount' => $price['amount'],
                        'currency_label' => $price['currency']['label'],
                        'currency_symbol' => $price['currency']['symbol'],
                    ]);
                }
            }

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Seeder Error in seedProducts: " . $e->getMessage());
        }
    }

    private function getAttributeId(string $productId, string $attributeName): ?int
    {
        $stmt = $this->db->prepare("
            SELECT id FROM attributes WHERE product_id = :product_id AND name = :name LIMIT 1
        ");
        $stmt->execute([
            'product_id' => $productId,
            'name' => $attributeName
        ]);
        return $stmt->fetchColumn() ?: null;
    }
}

// Run Seeder
try {
    $seeder = new Seeder();
    $seeder->seed();
    echo "Database seeded successfully!";
} catch (Exception $e) {
    error_log("Seeder Error: " . $e->getMessage());
    echo "Database seeding failed.";
}
