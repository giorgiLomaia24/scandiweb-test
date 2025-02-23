<?php

declare(strict_types=1);

namespace App\Models\Category;

use Exception;

/**
 * Class CategoryFactory
 *
 * Factory for creating Category instances.
 */
class CategoryFactory
{
    /**
     * Creates a new Category instance.
     *
     * @param array 
     * @return Category
     * @throws Exception 
     */
    public static function create(array $data): Category
    {
        if (!isset($data['id'], $data['name'])) {
            throw new Exception("CategoryFactory Error: Missing `id` or `name`. Data received: " . json_encode($data));
        }

        return new Category($data);
    }
}
