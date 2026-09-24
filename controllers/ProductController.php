<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Product.php";

class ProductController
{
    private $product;

    public function __construct($conn)
    {
        $this->product = new Product($conn);
    }

    // Get all products
    public function index()
    {
        return $this->product->getAllProducts();
    }

    // Get one product by ID
    public function show($product_id)
    {
        return $this->product->getProductById($product_id);
    }

    // Add a new product
    public function create($name, $description, $category, $price, $available_quantity)
    {
        return $this->product->createProduct(
            $name,
            $description,
            $category,
            $price,
            $available_quantity
        );
    }

    // Update an existing product
    public function update($product_id, $name, $description, $category, $price, $available_quantity)
    {
        return $this->product->updateProduct(
            $product_id,
            $name,
            $description,
            $category,
            $price,
            $available_quantity
        );
    }

    // Delete a product
    public function delete($product_id)
    {
        return $this->product->deleteProduct($product_id);
    }
}
?>