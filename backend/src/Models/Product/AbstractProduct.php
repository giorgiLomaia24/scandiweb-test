<?php

declare (strict_types = 1);

namespace App\Models\Product;

use App\Models\Attribute\AbstractAttribute;
use App\Repositories\CategoryRepository;
use Exception;

abstract class AbstractProduct
{
    protected string $id;
    protected string $name;
    protected string $description;
    protected int $categoryId;
    protected bool $inStock;
    protected string $brand;
    protected array $gallery = [];
    protected array $attributes = [];
    protected array $prices = [];

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->categoryId = $data['category_id']; 
        $this->inStock = (bool) $data['in_stock'];
        $this->brand = $data['brand'] ?? '';
        $this->gallery = isset($data['gallery']) && is_array($data['gallery']) ? $data['gallery'] : [];
        $this->setPrices($data['prices']);
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function isInStock(): bool
    {
        return $this->inStock;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getGallery(): array
    {
        return $this->gallery;
    }

    // Business Logic
    public function canBeSold(): bool
    {
        return $this->inStock && count($this->prices) > 0;
    }

    public function getFormattedPrice(): string
    {
        if (empty($this->prices)) {
            return "N/A";
        }

        $price = $this->prices[0];

        return $price['currency_symbol'] . number_format($price['amount'], 2);
    }

    public function addAttribute(AbstractAttribute $attribute): void
    {
        $this->attributes[] = $attribute;
    }

    public function setAttributes(array $attributes): void
    {
        foreach ($attributes as $attribute) {
            if (!$attribute instanceof AbstractAttribute) {
                throw new Exception("Invalid attribute type: Expected instance of AbstractAttribute.");
            }
        }

        $this->attributes = $attributes;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setPrices(array $prices): void
    {
        foreach ($prices as $price) {
            if (
                !isset($price['amount'], $price['currency_label'], $price['currency_symbol'])
                || $price['amount'] <= 0
            ) {
                throw new Exception("Invalid price format: Amount, currency_label, and currency_symbol are required.");
            }
        }

        $this->prices = $prices;
    }

    public function getPrices(): array
    {
        return $this->prices;
    }

    abstract public function getProductType(): string;
}
