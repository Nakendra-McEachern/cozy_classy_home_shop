<?php
require_once "config/database.php";
include "includes/header.php";

$message = "";
$edit_product = null;

// CREATE - Add a new product
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_product"])) {

    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $category = trim($_POST["category"]);
    $price = $_POST["price"];
    $available_quantity = $_POST["available_quantity"];

    $sql = "INSERT INTO products
            (name, description, category, price, available_quantity)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssdi",
        $name,
        $description,
        $category,
        $price,
        $available_quantity
    );

    if ($stmt->execute()) {
        $message = "Product added successfully!";
    } else {
        $message = "Unable to add product.";
    }

    $stmt->close();
}


// UPDATE - Save changes to a product
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_product"])) {

    $product_id = (int) $_POST["product_id"];
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $category = trim($_POST["category"]);
    $price = $_POST["price"];
    $available_quantity = $_POST["available_quantity"];

    $sql = "UPDATE products
            SET name = ?, description = ?, category = ?,
                price = ?, available_quantity = ?
            WHERE product_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssdii",
        $name,
        $description,
        $category,
        $price,
        $available_quantity,
        $product_id
    );

    if ($stmt->execute()) {
        $message = "Product updated successfully!";
    } else {
        $message = "Unable to update product.";
    }

    $stmt->close();
}


// DELETE - Remove a product
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_product"])) {

    $product_id = (int) $_POST["product_id"];

    $sql = "DELETE FROM products WHERE product_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        $message = "Product deleted successfully!";
    } else {
        $message = "Unable to delete product.";
    }

    $stmt->close();
}


// EDIT - Load the selected product into the form
if (isset($_GET["edit"])) {

    $product_id = (int) $_GET["edit"];

    $sql = "SELECT * FROM products WHERE product_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $edit_product = $result->fetch_assoc();

    $stmt->close();
}


// READ - Get all products
$sql = "SELECT * FROM products ORDER BY product_id";
$products = $conn->query($sql);
?>

<h2>Manage Products</h2>

<?php if ($message != "") { ?>
    <p>
        <strong>
            <?php echo htmlspecialchars($message); ?>
        </strong>
    </p>
<?php } ?>


<h3>
    <?php echo $edit_product ? "Edit Product" : "Add New Product"; ?>
</h3>

<form method="post">

    <?php if ($edit_product) { ?>
        <input
            type="hidden"
            name="product_id"
            value="<?php echo $edit_product["product_id"]; ?>"
        >
    <?php } ?>

    <p>
        <label>Product Name:</label><br>
        <input
            type="text"
            name="name"
            value="<?php
                echo $edit_product
                    ? htmlspecialchars($edit_product["name"])
                    : "";
            ?>"
            required
        >
    </p>

    <p>
        <label>Description:</label><br>
        <textarea
            name="description"
            required
        ><?php
            echo $edit_product
                ? htmlspecialchars($edit_product["description"])
                : "";
        ?></textarea>
    </p>

    <p>
        <label>Category:</label><br>
        <input
            type="text"
            name="category"
            value="<?php
                echo $edit_product
                    ? htmlspecialchars($edit_product["category"])
                    : "";
            ?>"
            required
        >
    </p>

    <p>
        <label>Price:</label><br>
        <input
            type="number"
            name="price"
            step="0.01"
            min="0"
            value="<?php
                echo $edit_product
                    ? $edit_product["price"]
                    : "";
            ?>"
            required
        >
    </p>

    <p>
        <label>Available Quantity:</label><br>
        <input
            type="number"
            name="available_quantity"
            min="0"
            value="<?php
                echo $edit_product
                    ? $edit_product["available_quantity"]
                    : "";
            ?>"
            required
        >
    </p>

    <?php if ($edit_product) { ?>

        <button type="submit" name="update_product">
            Update Product
        </button>

        <a href="manage_products.php">Cancel</a>

    <?php } else { ?>

        <button type="submit" name="add_product">
            Add Product
        </button>

    <?php } ?>

</form>


<hr style="margin: 35px 0;">


<h3>Current Products</h3>

<table
    border="1"
    cellpadding="10"
    cellspacing="0"
    style="width: 100%; background: white;"
>

    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Category</th>
        <th>Price</th>
        <th>Available</th>
        <th>Actions</th>
    </tr>

    <?php if ($products && $products->num_rows > 0) { ?>

        <?php while ($row = $products->fetch_assoc()) { ?>

            <tr>

                <td>
                    <?php echo $row["product_id"]; ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row["name"]); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row["category"]); ?>
                </td>

                <td>
                    $<?php echo number_format($row["price"], 2); ?>
                </td>

                <td>
                    <?php echo $row["available_quantity"]; ?>
                </td>

                <td>

                    <a href="manage_products.php?edit=<?php
                        echo $row["product_id"];
                    ?>">
                        Edit
                    </a>

                    &nbsp;

                    <form
                        method="post"
                        style="display: inline;"
                        onsubmit="return confirm('Delete this product?');"
                    >

                        <input
                            type="hidden"
                            name="product_id"
                            value="<?php echo $row["product_id"]; ?>"
                        >

                        <button
                            type="submit"
                            name="delete_product"
                        >
                            Delete
                        </button>

                    </form>

                </td>

            </tr>

        <?php } ?>

    <?php } else { ?>

        <tr>
            <td colspan="6">No products found.</td>
        </tr>

    <?php } ?>

</table>


<?php
include "includes/footer.php";
?>