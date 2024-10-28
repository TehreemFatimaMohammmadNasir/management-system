<?php
session_start();
include 'config.php';
include 'navbar.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['action']) && $_GET['action'] == "add") {
    $product_id = intval($_GET['id']); // Ensure the ID is an integer
    $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;
}

$total = 0;
$product_ids = array_keys($_SESSION['cart']); // Collect product IDs

if (!empty($product_ids)) {
    // Make sure product IDs are sanitized and formatted correctly
    $product_ids = array_map('intval', $product_ids);
    $product_ids_list = implode(',', $product_ids); // Create a comma-separated list

    $query = "SELECT * FROM products WHERE id IN ($product_ids_list)";
    
    // Execute the query and check for errors
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    $products = [];
    while ($product = mysqli_fetch_assoc($result)) {
        $products[$product['id']] = $product;
    }

    foreach ($_SESSION['cart'] as $id => $quantity) {
        if (isset($products[$id])) {
            $total += $products[$id]['price'] * $quantity;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baskervville+SC&family=DM+Serif+Text:ital@0;1&family=Nerko+One&display=swap" rel="stylesheet">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: "DM Serif Text", system-ui;
  font-weight: 400;
  font-style: normal;
            background-color: #f9f9f9;
            color: #333;
        }

        .cart-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #1e130c;
            font-size: 2.5rem;
            letter-spacing: 1px;
        }

        table {
            width: 100%;
        }

        table thead th {
            background: #1e130c;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #9a8478, #1e130c);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #9a8478, #1e130c); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

            color: #fff;
            padding: 15px;
            text-align: left;
            font-size: 1.1rem;
        }

        table tbody td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-size: 1.05rem;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .cart-total {
            text-align: right;
            font-size: 1.75rem;
            color: #1e130c;
            margin-top: 30px;
            border-top: 2px solid #ddd;
            padding-top: 10px;
        }

        .checkout-btn {
            width: 100%;
            padding: 10px;
            font-size: 1.5rem;
            color: #fff;
            background: #1e130c;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #9a8478, #1e130c);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #9a8478, #1e130c); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

            border: none;
            border-radius: 8px;
            text-align: center;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        

        .checkout-btn:hover {
            background-color: #9a8478;
        }

        .checkout-btn:active {
            background-color: #9a8478;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            h2 {
                font-size: 2rem;
            }

            .checkout-btn {
                font-size: 1.25rem;
            }

            table thead th, table tbody td {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    
<div class="cart-container">
    <h2>Your Cart</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $id => $quantity): ?>
                <?php if (isset($products[$id])): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($products[$id]['name']); ?></td>
                        <td>$<?php echo htmlspecialchars($products[$id]['price']); ?></td>
                        <td><?php echo htmlspecialchars($quantity); ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="cart-total">
        <strong>Total: $<?php echo htmlspecialchars($total); ?></strong>
    </div>

    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
</div>

</body>
</html>




