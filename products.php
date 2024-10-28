<?php
include '../config.php'; // Ensure config.php has correct DB variables
include 'includes/header.php';

// Handle Create Product
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO products (name, description, price, quantity) VALUES ('$name', '$description', '$price', '$quantity')";
    mysqli_query($conn, $sql);
    header("Location: products.php"); // Redirect to prevent form resubmission
}

// Handle Update Product
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $sql = "UPDATE products SET name='$name', description='$description', price='$price', quantity='$quantity' WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: products.php");
}

// Handle Delete Product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM products WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: products.php");
}

// Query to fetch all product details
$sql = "SELECT id, name, description, price, quantity FROM products";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2, h3 {
            text-align: center;
            color: #333;
        }
        .form-container, .table-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        form input, form button {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        form button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        form button:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        .action-buttons button, .action-buttons a {
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .update-btn {
            background-color: #ffc107;
        }
        .update-btn:hover {
            background-color: #e0a800;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<h2>Products Management</h2>

<div class="form-container">
    <h3>Add New Product</h3>
    <form action="products.php" method="POST">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="text" name="description" placeholder="Description" required>
        <input type="number" name="price" placeholder="Price" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <div class="form-group">
            <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" name="create">Add Product</button>
    </form>
</div>

<div class="table-container">
    <table>
        <tr>
            <th>Product ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Actions</th>
        </tr>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td>Rs <?= htmlspecialchars($product['price']) ?></td>
                    <td><?= htmlspecialchars($product['quantity']) ?></td>
                    <td class="action-buttons">
                        <form action="products.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                            <input type="text" name="description" value="<?= htmlspecialchars($product['description']) ?>" required>
                            <input type="number" name="price" value="<?= $product['price'] ?>" required>
                            <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>
                            <button type="submit" name="update" class="update-btn">Update</button>
                        </form>
                        <a href="products.php?delete=<?= $product['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?');" class="delete-btn">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No product data found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<?php
// Close the database connection
mysqli_close($conn);
include 'includes/footer.php';
?>

</body>
</html>
