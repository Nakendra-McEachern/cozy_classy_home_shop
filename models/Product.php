<?php

class Product
{
    private $conn;
    private $table = "products";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all products
    public function getAllProducts()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY product_id ASC";
        return $this->conn->query($query);
    }

    // Get one product
    public function getProductById($product_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM " . $this->table . " WHERE product_id = ?"
        );

        $stmt->bind_param("i", $product_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Add a product
    public function createProduct($name, $description, $category, $price, $available_quantity)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO " . $this->table . "
            (name, description, category, price, available_quantity)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssdi",
            $name,
            $description,
            $category,
            $price,
            $available_quantity
        );

        return $stmt->execute();
    }

    // Update a product
    public function updateProduct($product_id, $name, $description, $category, $price, $available_quantity)
    {
        $stmt = $this->conn->prepare(
            "UPDATE " . $this->table . "
            SET name = ?, description = ?, category = ?, price = ?, available_quantity = ?
            WHERE product_id = ?"
        );

        $stmt->bind_param(
            "sssdii",
            $name,
            $description,
            $category,
            $price,
            $available_quantity,
            $product_id
        );

        return $stmt->execute();
    }

    // Delete a product
    public function deleteProduct($product_id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM " . $this->table . " WHERE product_id = ?"
        );

        $stmt->bind_param("i", $product_id);

        return $stmt->execute();
    }
}
?>