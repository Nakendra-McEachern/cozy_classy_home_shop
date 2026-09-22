<?php
session_start();

require_once "config/database.php";

// Create the cart session if it does not exist
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}


// ADD PRODUCT TO CART
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_to_cart"])) {

    $product_id = (int) $_POST["product_id"];
    $quantity = (int) $_POST["quantity"];

    if ($quantity < 1) {
        $quantity = 1;
    }

    // Check that the product exists
    $sql = "SELECT product_id, available_quantity
            FROM products
            WHERE product_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {

        // Do not allow more than the available inventory
        $quantity = min($quantity, (int) $product["available_quantity"]);

        if (isset($_SESSION["cart"][$product_id])) {
            $_SESSION["cart"][$product_id] += $quantity;

            $_SESSION["cart"][$product_id] = min(
                $_SESSION["cart"][$product_id],
                (int) $product["available_quantity"]
            );
        } else {
            $_SESSION["cart"][$product_id] = $quantity;
        }
    }

    $stmt->close();

    header("Location: cart.php");
    exit;
}


// UPDATE CART QUANTITIES
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_cart"])) {

    if (isset($_POST["quantities"])) {

        foreach ($_POST["quantities"] as $product_id => $quantity) {

            $product_id = (int) $product_id;
            $quantity = (int) $quantity;

            if ($quantity > 0) {
                $_SESSION["cart"][$product_id] = $quantity;
            } else {
                unset($_SESSION["cart"][$product_id]);
            }
        }
    }

    header("Location: cart.php");
    exit;
}


// REMOVE ONE PRODUCT
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["remove_product"])) {

    $product_id = (int) $_POST["product_id"];

    unset($_SESSION["cart"][$product_id]);

    header("Location: cart.php");
    exit;
}


include "includes/header.php";
?>

<h2>Your Cart</h2>

<?php if (empty($_SESSION["cart"])) { ?>

    <p style="margin-top: 15px;">
        Your shopping cart is currently empty.
    </p>

    <p style="margin-top: 15px;">
        <a href="catalog.php">Shop the Catalog</a>
    </p>

<?php } else { ?>

    <form method="post">

        <table
            border="1"
            cellpadding="10"
            cellspacing="0"
            style="width: 100%; background: white; margin-top: 20px;"
        >

            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>

            <?php
            $grand_total = 0;

            foreach ($_SESSION["cart"] as $product_id => $quantity) {

                $sql = "SELECT *
                        FROM products
                        WHERE product_id = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $product_id);
                $stmt->execute();

                $result = $stmt->get_result();
                $product = $result->fetch_assoc();

                if (!$product) {
                    continue;
                }

                $subtotal = $product["price"] * $quantity;
                $grand_total += $subtotal;
            ?>

                <tr>

                    <td>
                        <?php echo htmlspecialchars($product["name"]); ?>
                    </td>

                    <td>
                        $<?php echo number_format($product["price"], 2); ?>
                    </td>

                    <td>
                        <input
                            type="number"
                            name="quantities[<?php echo $product_id; ?>]"
                            value="<?php echo $quantity; ?>"
                            min="1"
                            max="<?php echo $product["available_quantity"]; ?>"
                            style="width: 60px;"
                        >
                    </td>

                    <td>
                        $<?php echo number_format($subtotal, 2); ?>
                    </td>

                    <td>
                        <button
                            type="submit"
                            name="remove_product"
                            value="1"
                            formaction="cart.php"
                            formmethod="post"
                            onclick="
                                this.form.product_id.value =
                                '<?php echo $product_id; ?>';
                            "
                        >
                            Remove
                        </button>
                    </td>

                </tr>

            <?php
                $stmt->close();
            }
            ?>

            <tr>
                <td colspan="3">
                    <strong>Cart Total</strong>
                </td>

                <td colspan="2">
                    <strong>
                        $<?php echo number_format($grand_total, 2); ?>
                    </strong>
                </td>
            </tr>

        </table>

        <input
            type="hidden"
            name="product_id"
            value=""
        >

        <p style="margin-top: 20px;">

            <button type="submit" name="update_cart">
                Update Cart
            </button>

            &nbsp;

            <a href="catalog.php">
                Continue Shopping
            </a>

            &nbsp;

            <a href="checkout.php">
                Checkout
            </a>

        </p>

    </form>

<?php } ?>

<?php
include "includes/footer.php";
?>