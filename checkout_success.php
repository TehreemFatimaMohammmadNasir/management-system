<?php
session_start();
include 'config.php';
include 'navbar.php';
// Check if an order ID was passed in the URL
if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = intval($_GET['order_id']);

// Retrieve the order details from the database
$query = "SELECT * FROM orders WHERE id = $order_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    // If the order doesn't exist, redirect to the products page
    header("Location: index.php");
    exit();
}

// Fetch the order information
$order = mysqli_fetch_assoc($result);

// Retrieve the order items
$query = "SELECT oi.*, p.name AS product_name 
          FROM order_items oi 
          JOIN products p ON oi.product_id = p.id 
          WHERE oi.order_id = $order_id";
$order_items_result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baskervville+SC&family=DM+Serif+Text:ital@0;1&family=Nerko+One&display=swap" rel="stylesheet">
    <title>Order Success</title>
    <style>
        body {
            font-family: "DM Serif Text", system-ui;
  font-weight: 400;
  font-style: normal;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 900px;
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

        .order-summary {
            margin: 20px 0;
        }

        .order-summary h3 {
            font-size: 1.75rem;
            margin-bottom: 15px;
        }

        .order-summary table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .order-summary table th, .order-summary table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .btn-primary, .btn-success {
            display: block;
            width: 100%;
            padding: 15px;
            font-size: 1.2rem;
            border: none;
            border-radius: 8px;
            color: white;
            margin-top: 20px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background: #1e130c;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #9a8478, #1e130c);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #9a8478, #1e130c); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background: #5D4157;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #A8CABA, #5D4157);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #A8CABA, #5D4157); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        }

        .btn-success:hover {
            background-color: #218838;
        }

        /* Media queries for responsiveness */
        @media (max-width: 768px) {
            h2 {
                font-size: 2rem;
            }

            p, .order-summary h3 {
                font-size: 1.1rem;
            }

            .btn-primary, .btn-success {
                font-size: 1rem;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Thank You for Your Order!</h2>
    <p>Your order has been placed successfully! Here are your order details:</p>

    <div class="order-summary">
        <h3>Order #<?php echo htmlspecialchars($order['id']); ?></h3>
        <table>
            <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($item = mysqli_fetch_assoc($order_items_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
    </div>

    <a href="index.php" class="btn-primary">Continue Shopping</a>
    <a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="btn-success">View Order Details</a>
</div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
