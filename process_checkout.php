<?php
session_start();
include 'config.php';
include 'navbar.php';

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate the form inputs
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email = mysqli_real_escape_string($conn, filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $city = mysqli_real_escape_string($conn, trim($_POST['city']));
    $postal_code = mysqli_real_escape_string($conn, trim($_POST['postal_code']));
    
    // Check if any required field is empty
    if (empty($full_name) || empty($email) || empty($address) || empty($city) || empty($postal_code)) {
        echo "All fields are required!";
        exit();
    }

    // Calculate the total amount again to ensure it's correct
    $total = 0;
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $query = "SELECT price FROM products WHERE id = $id";
        $result = mysqli_query($conn, $query);
        if ($product = mysqli_fetch_assoc($result)) {
            $total += $product['price'] * $quantity;
        }
    }

    // If the total is greater than zero, proceed with storing the order
    if ($total > 0) {
        $user_id = $_SESSION['user_id'];

        // Insert the order into the 'orders' table
        $query = "INSERT INTO orders (user_id, full_name, email, address, city, postal_code, total_amount) 
                  VALUES ('$user_id', '$full_name', '$email', '$address', '$city', '$postal_code', '$total')";

        if (mysqli_query($conn, $query)) {
            $order_id = mysqli_insert_id($conn); // Get the newly created order ID

            // Insert each item in the order into the 'order_items' table
            foreach ($_SESSION['cart'] as $id => $quantity) {
                $query = "SELECT price FROM products WHERE id = $id";
                $result = mysqli_query($conn, $query);

                if ($product = mysqli_fetch_assoc($result)) {
                    $price = $product['price'];
                    $query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                              VALUES ('$order_id', '$id', '$quantity', '$price')";
                    mysqli_query($conn, $query);
                }
            }

            // Clear the cart after successful order
            $_SESSION['cart'] = [];

            // Redirect to the success page or show a success message
            header("Location: checkout_success.php?order_id=$order_id");
            exit();
        } else {
            die("Error placing order: " . mysqli_error($conn));
        }
    } else {
        echo "Your cart is empty.";
    }
} else {
    echo "Invalid request.";
}

// Close the database connection
mysqli_close($conn);
?>
