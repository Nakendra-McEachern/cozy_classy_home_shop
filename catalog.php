<?php
require_once "config/database.php";
include "includes/header.php";

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<h2 style="margin-bottom: 25px;">Shop Our Collection</h2>

<div style="
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
">

<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>

    <div style="
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    ">

        <h3>
            <?php echo htmlspecialchars($row["name"]); ?>
        </h3>

        <p style="margin-top: 10px;">
            <?php echo htmlspecialchars($row["description"]); ?>
        </p>

        <p style="margin-top: 10px;">
            <strong>Category:</strong>
            <?php echo htmlspecialchars($row["category"]); ?>
        </p>

        <p style="margin-top: 10px;">
            <strong>Price:</strong>
            $<?php echo number_format($row["price"], 2); ?>
        </p>

        <p style="margin-top: 10px;">
            <strong>Available:</strong>
            <?php echo $row["available_quantity"]; ?>
        </p>

        <!-- Add product to shopping cart -->
        <form
            method="post"
            action="cart.php"
            style="margin-top: 15px;"
        >

            <input
                type="hidden"
                name="product_id"
                value="<?php echo $row["product_id"]; ?>"
            >

            <label>Quantity:</label>

            <input
                type="number"
                name="quantity"
                value="1"
                min="1"
                max="<?php echo $row["available_quantity"]; ?>"
                style="width: 60px;"
                required
            >

            <button type="submit" name="add_to_cart">
                Add to Cart
            </button>

        </form>

    </div>

<?php
    }
} else {
    echo "<p>No products found.</p>";
}
?>

</div>

<?php
include "includes/footer.php";
?>