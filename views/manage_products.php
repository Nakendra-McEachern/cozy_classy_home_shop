<?php
require_once __DIR__ . "/../controllers/ProductController.php";

$controller = new ProductController($conn);

$message = "";
$edit_product = null;

// CREATE - Add a new product
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_product"])) {

    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $category = trim($_POST["category"]);
    $price = $_POST["price"];
    $available_quantity = $_POST["available_quantity"];

    if ($controller->create(
        $name,
        $description,
        $category,
        $price,
        $available_quantity
    )) {
        $message = "Product added successfully!";
    } else {
        $message = "Error adding product.";
    }
}

// UPDATE - Update an existing product
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_product"])) {

    $product_id = $_POST["product_id"];
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $category = trim($_POST["category"]);
    $price = $_POST["price"];
    $available_quantity = $_POST["available_quantity"];

    if ($controller->update(
        $product_id,
        $name,
        $description,
        $category,
        $price,
        $available_quantity
    )) {
        $message = "Product updated successfully!";
    } else {
        $message = "Error updating product.";
    }
}

// DELETE - Delete a product
if (isset($_GET["delete"])) {

    $product_id = $_GET["delete"];

    if ($controller->delete($product_id)) {
        $message = "Product deleted successfully!";
    } else {
        $message = "Error deleting product.";
    }
}

// EDIT - Get one product to place in the edit form
if (isset($_GET["edit"])) {

    $product_id = $_GET["edit"];
    $edit_product = $controller->show($product_id);
}

// READ - Get all products
$result = $controller->index();

include __DIR__ . "/../includes/header.php";
?>

<h2 style="margin-bottom: 25px;">Manage Products</h2>

<?php if (!empty($message)) { ?>
    <p style="
        background: #f2f2f2;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
    ">
        <?php echo htmlspecialchars($message); ?>
    </p>
<?php } ?>

<div style="
    background: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
">

    <?php if ($edit_product) { ?>

        <h3>Edit Product</h3>

        <form method="POST" action="manage_products.php">

            <input
                type="hidden"
                name="product_id"
                value="<?php echo $edit_product["product_id"]; ?>"
            >

            <p>
                <label>Product Name:</label><br>
                <input
                    type="text"
                    name="name"
                    value="<?php echo htmlspecialchars($edit_product["name"]); ?>"
                    required
                >
            </p>

            <p>
                <label>Description:</label><br>
                <textarea
                    name="description"
                    required
                ><?php echo htmlspecialchars($edit_product["description"]); ?></textarea>
            </p>

            <p>
                <label>Category:</label><br>
                <input
                    type="text"
                    name="category"
                    value="<?php echo htmlspecialchars($edit_product["category"]); ?>"
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
                    value="<?php echo $edit_product["price"]; ?>"
                    required
                >
            </p>

            <p>
                <label>Available Quantity:</label><br>
                <input
                    type="number"
                    name="available_quantity"
                    min="0"
                    value="<?php echo $edit_product["available_quantity"]; ?>"
                    required
                >
            </p>

            <button type="submit" name="update_product">
                Update Product
            </button>

            <a href="manage_products.php">Cancel</a>

        </form>

    <?php } else { ?>

        <h3>Add New Product</h3>

        <form method="POST" action="manage_products.php">

            <p>
                <label>Product Name:</label><br>
                <input
                    type="text"
                    name="name"
                    required
                >
            </p>

            <p>
                <label>Description:</label><br>
                <textarea
                    name="description"
                    required
                ></textarea>
            </p>

            <p>
                <label>Category:</label><br>
                <input
                    type="text"
                    name="category"
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
                    required
                >
            </p>

            <p>
                <label>Available Quantity:</label><br>
                <input
                    type="number"
                    name="available_quantity"
                    min="0"
                    required
                >
            </p>

            <button type="submit" name="add_product">
                Add Product
            </button>

        </form>

    <?php } ?>

</div>

<h3>Current Products</h3>

<div style="overflow-x: auto;">

    <table style="
        width: 100%;
        border-collapse: collapse;
        background: white;
    ">

        <thead>
            <tr>
                <th style="padding: 12px; border: 1px solid #ddd;">ID</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Name</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Description</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Category</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Price</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Quantity</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php
        if ($result && $result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
        ?>

            <tr>

                <td style="padding: 12px; border: 1px solid #ddd;">
                    <?php echo $row["product_id"]; ?>
                </td>

                <td style="padding: 12px; border: 1px solid #ddd;">
                    <?php echo htmlspecialchars($row["name"]); ?>
                </td>

                <td style="padding: 12px; border: 1px solid #ddd;">
                    <?php echo htmlspecialchars($row["description"]); ?>
                </td>

                <td style="padding: 12px; border: 1px solid #ddd;">
                    <?php echo htmlspecialchars($row["category"]); ?>
                </td>

                <td style="padding: 12px; border: 1px solid #ddd;">
                    $<?php echo number_format($row["price"], 2); ?>
                </td>

                <td style="padding: 12px; border: 1px solid #ddd;">
                    <?php echo $row["available_quantity"]; ?>
                </td>

                <td style="padding: 12px; border: 1px solid #ddd;">

                    <a href="manage_products.php?edit=<?php echo $row["product_id"]; ?>">
                        Edit
                    </a>

                    |

                    <a
                        href="manage_products.php?delete=<?php echo $row["product_id"]; ?>"
                        onclick="return confirm('Are you sure you want to delete this product?');"
                    >
                        Delete
                    </a>

                </td>

            </tr>

        <?php
            }

        } else {
        ?>

            <tr>
                <td colspan="7" style="padding: 15px;">
                    No products found.
                </td>
            </tr>

        <?php
        }
        ?>

        </tbody>

    </table>

</div>

<?php
include __DIR__ . "/../includes/footer.php";
?>