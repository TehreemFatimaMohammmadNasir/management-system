<?php
session_start();
include 'config.php';
include 'navbar.php';

// Check if user is logged in and cart is not empty
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// Initialize $total at the start
$total = 0;

// Sanitize and filter the cart to remove invalid IDs
$_SESSION['cart'] = array_filter($_SESSION['cart'], function($key) {
    return is_numeric($key) && $key > 0;
}, ARRAY_FILTER_USE_KEY);

// Calculate the total price
foreach ($_SESSION['cart'] as $id => $quantity) {
    $query = "SELECT price FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        // Remove the product from the cart if it doesn't exist
        unset($_SESSION['cart'][$id]);
        error_log("Product ID $id not found, removing from cart");
    } else {
        $product = mysqli_fetch_assoc($result);
        $total += $product['price'] * $quantity;
    }
}

$user_id = $_SESSION['user_id'];

// Debugging: Check session data
error_log("Session Data: " . print_r($_SESSION, true));

// Debugging: Check total amount
error_log("Total Amount: $total");

if ($total > 0) {
    // User information form processing
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {
        // Sanitize and capture form data
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);

        // Insert order into the orders table
        $query = "INSERT INTO orders (user_id, full_name, email, address, city, postal_code, total_amount, created_at)
                  VALUES ($user_id, '$full_name', '$email', '$address', '$city', '$postal_code', $total, NOW())";

        if (mysqli_query($conn, $query)) {
            $order_id = mysqli_insert_id($conn); // Get the order ID

            // Insert order items
            foreach ($_SESSION['cart'] as $id => $quantity) {
                $query = "SELECT price FROM products WHERE id = $id";
                $result = mysqli_query($conn, $query);
                
                if (!$result) {
                    die("Query failed: " . mysqli_error($conn) . " Query: $query");
                }

                if ($product = mysqli_fetch_assoc($result)) {
                    $price = $product['price'];
                    $query = "INSERT INTO order_items (order_id, product_id, quantity, price)
                              VALUES ($order_id, $id, $quantity, $price)";
                    if (!mysqli_query($conn, $query)) {
                        die("Order item insertion failed: " . mysqli_error($conn));
                    }
                } else {
                    die("Product not found for ID: $id");
                }
            }

            // Clear the cart
            $_SESSION['cart'] = []; // Clear the entire cart

            // Redirect to checkout success page
            header("Location: checkout_success.php?order_id=$order_id");
            exit();
        } else {
            die("Error inserting order: " . mysqli_error($conn));
        }
    }
} else {
    // If the total is 0, redirect to products
    header("Location: products.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baskervville+SC&family=DM+Serif+Text:ital@0;1&family=Nerko+One&display=swap" rel="stylesheet">
    <title>Checkout</title>
    <style>
        body {
            font-family: "DM Serif Text", system-ui;
  font-weight: 400;
  font-style: normal;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #1e130c;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.25rem;
            text-align: center;
            color: #555;
        }

        .btn-primary {
            display: block;
            width: 100%;
            padding: 15px;
            font-size: 1.2rem;
            background: #1e130c;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #9a8478, #1e130c);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #9a8478, #1e130c); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

            border: none;
            border-radius: 8px;
            color: white;
            margin-top: 20px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1e8c4a;
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 2rem;
            }

            p {
                font-size: 1.1rem;
            }

            .btn-primary {
                font-size: 1rem;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Checkout</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>
        <div class="form-group">
            <label for="postal_code">Postal Code</label>
            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
        </div>
        <div class="inputBox">
               <span>payment method</span>
               <select name="method">
                  <option value="cash on delivery" selected>cash on delivery</option>
                  <option value="credit card">credit card</option>
                  <option value="paypal">paypal</option>
               </select>
            </div>
        <button type="submit" name="submit_order" class="btn-primary">Place Order</button>
    </form>
</div>
</body>
</html>
