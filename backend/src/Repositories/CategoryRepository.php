<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\Category\Category;
use App\Models\Category\CategoryFactory;
use Exception;
use PDO;

/**
 * Class CategoryRepository
 *
 * Handles category-related database queries.
 */
class CategoryRepository
{
    /**
     * Retrieves all categories from the database.
     *
     * @return Category[] The list of category objects.
     */
    public static function getAllCategories(): array
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM categories");
        $categoriesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Convert raw database rows into Category objects
        return array_map(fn($data) => CategoryFactory::create($data), $categoriesData);
    }

    /**
     * Retrieves a category by ID.
     *
     * @param int $id The category ID.
     * @return Category|null The category object or null if not found.
     * @throws Exception If category is not found.
     */
    public static function getCategoryById(int $id): ?Category
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT * FROM categories WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$categoryData) {
                throw new Exception("Category not found with ID: $id");
            }

            return CategoryFactory::create($categoryData);
        } catch (Exception $e) {
            error_log("Error in getCategoryById(): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Retrieves a category ID by name.
     *
     * @param string $categoryName The category name.
     * @return int The category ID.
     * @throws Exception If category is not found.
     */
    public static function getCategoryIdByName(string $categoryName): int
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT id FROM categories WHERE name = :name");
        $stmt->execute(['name' => $categoryName]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$category) {
            throw new Exception("Category '$categoryName' not found in database.");
        }

        return (int) $category['id'];
    }
}
