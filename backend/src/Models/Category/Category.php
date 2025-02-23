<?php

declare(strict_types=1);

namespace App\Models\Category;

/**
 * Class Category
 *
 * Represents a product category.
 */
class Category
{
    /** @var int The unique ID of the category */
    private int $id;

    /** @var string The name of the category */
    private string $name;

    /**
     * Category constructor.
     *
     * @param array 
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
    }

    /**
     * Get the category ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the category name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
