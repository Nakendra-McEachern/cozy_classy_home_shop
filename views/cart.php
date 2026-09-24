<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../controllers/CartController.php";

$cartController = new CartController($conn);

// ADD PRODUCT TO CART
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_to_cart"])) {

    $product_id = (int) $_POST["product_id"];
    $quantity = (int) $_POST["quantity"];

    $cartController->add($product_id, $quantity);

    header("Location: cart.php");
    exit;
}

// UPDATE CART QUANTITIES
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_cart"])) {

    if (isset($_POST["quantities"])) {
        $cartController->update($_POST["quantities"]);
    }

    header("Location: cart.php");
    exit;
}

// REMOVE PRODUCT
if (isset($_GET["remove"])) {

    $cartController->remove($_GET["remove"]);

    header("Location: cart.php");
    exit;
}

// Get cart information from the controller
$cart_items = $cartController->getCartItems();
$cart_total = $cartController->getCartTotal();

include __DIR__ . "/../includes/header.php";
?>

<h2 style="margin-bottom: 25px;">Your Shopping Cart</h2>

<?php if (empty($cart_items)) { ?>

    <div style="
        background: white;
        padding: 25px;
        border-radius: 12px;
    ">
        <p>Your cart is empty.</p>

        <a href="catalog.php">
            Continue Shopping
        </a>
    </div>

<?php } else { ?>

    <form method="POST" action="cart.php">

        <div style="overflow-x: auto;">

            <table style="
                width: 100%;
                border-collapse: collapse;
                background: white;
            ">

                <thead>
                    <tr>
                        <th style="padding: 12px; border: 1px solid #ddd;">
                            Product
                        </th>

                        <th style="padding: 12px; border: 1px solid #ddd;">
                            Price
                        </th>

                        <th style="padding: 12px; border: 1px solid #ddd;">
                            Quantity
                        </th>

                        <th style="padding: 12px; border: 1px solid #ddd;">
                            Subtotal
                        </th>

                        <th style="padding: 12px; border: 1px solid #ddd;">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($cart_items as $item) { ?>

                        <tr>

                            <td style="padding: 12px; border: 1px solid #ddd;">
                                <?php echo htmlspecialchars($item["name"]); ?>
                            </td>

                            <td style="padding: 12px; border: 1px solid #ddd;">
                                $<?php echo number_format($item["price"], 2); ?>
                            </td>

                            <td style="padding: 12px; border: 1px solid #ddd;">

                                <input
                                    type="number"
                                    name="quantities[<?php echo $item["product_id"]; ?>]"
                                    value="<?php echo $item["cart_quantity"]; ?>"
                                    min="0"
                                    max="<?php echo $item["available_quantity"]; ?>"
                                    style="width: 70px;"
                                >

                            </td>

                            <td style="padding: 12px; border: 1px solid #ddd;">
                                $<?php echo number_format($item["subtotal"], 2); ?>
                            </td>

                            <td style="padding: 12px; border: 1px solid #ddd;">

                                <a
                                    href="cart.php?remove=<?php echo $item["product_id"]; ?>"
                                    onclick="return confirm('Remove this product from your cart?');"
                                >
                                    Remove
                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

        <div style="
            background: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 12px;
        ">

            <h3>
                Cart Total:
                $<?php echo number_format($cart_total, 2); ?>
            </h3>

            <button type="submit" name="update_cart">
                Update Cart
            </button>

            <a
                href="checkout.php"
                style="margin-left: 15px;"
            >
                Proceed to Checkout
            </a>

        </div>

    </form>

<?php } ?>

<?php
include __DIR__ . "/../includes/footer.php";
?>