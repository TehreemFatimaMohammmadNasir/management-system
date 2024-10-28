<?php
// Include the config file to connect to the database
include 'config.php';

// Query to retrieve all orders
$sql = "SELECT * FROM orders";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        // Start of the HTML table
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        echo "<tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>City</th>
                <th>Postal Code</th>
                <th>Total Amount</th>
                <th>Created At</th>
              </tr>";

        // Fetch and display each row of data
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['user_id']}</td>
                    <td>{$row['full_name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['address']}</td>
                    <td>{$row['city']}</td>
                    <td>{$row['postal_code']}</td>
                    <td>{$row['total_amount']}</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
        }

        echo "</table>"; // End of the HTML table
    } else {
        echo "No orders found.";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
