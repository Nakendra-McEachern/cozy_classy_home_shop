<?php

require_once __DIR__ . "/../models/Product.php";

class CartController
{
    private $product;

    public function __construct($conn)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }

        $this->product = new Product($conn);
    }

    // Add a product to the cart
    public function add($product_id, $quantity)
    {
        $product_id = (int) $product_id;
        $quantity = (int) $quantity;

        if ($quantity < 1) {
            $quantity = 1;
        }

        $product = $this->product->getProductById($product_id);

        if (!$product) {
            return false;
        }

        $available_quantity = (int) $product["available_quantity"];

        if ($available_quantity < 1) {
            return false;
        }

        $quantity = min($quantity, $available_quantity);

        if (isset($_SESSION["cart"][$product_id])) {
            $_SESSION["cart"][$product_id] += $quantity;

            $_SESSION["cart"][$product_id] = min(
                $_SESSION["cart"][$product_id],
                $available_quantity
            );
        } else {
            $_SESSION["cart"][$product_id] = $quantity;
        }

        return true;
    }

    // Update cart quantities
    public function update($quantities)
    {
        foreach ($quantities as $product_id => $quantity) {

            $product_id = (int) $product_id;
            $quantity = (int) $quantity;

            if ($quantity <= 0) {
                unset($_SESSION["cart"][$product_id]);
                continue;
            }

            $product = $this->product->getProductById($product_id);

            if ($product) {
                $available_quantity = (int) $product["available_quantity"];

                $_SESSION["cart"][$product_id] = min(
                    $quantity,
                    $available_quantity
                );
            }
        }

        return true;
    }

    // Remove one item
    public function remove($product_id)
    {
        $product_id = (int) $product_id;

        if (isset($_SESSION["cart"][$product_id])) {
            unset($_SESSION["cart"][$product_id]);
        }
    }

    // Get products currently in the cart
    public function getCartItems()
    {
        $items = [];

        foreach ($_SESSION["cart"] as $product_id => $quantity) {

            $product = $this->product->getProductById($product_id);

            if ($product) {
                $product["cart_quantity"] = $quantity;
                $product["subtotal"] =
                    $product["price"] * $quantity;

                $items[] = $product;
            }
        }

        return $items;
    }

    // Calculate cart total
    public function getCartTotal()
    {
        $total = 0;

        foreach ($this->getCartItems() as $item) {
            $total += $item["subtotal"];
        }

        return $total;
    }
}
?>