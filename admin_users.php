<?php
// Include the config file to connect to the database
include 'config.php';

// Query to count the number of admin users
$sql = "SELECT COUNT(*) AS total_admins FROM admin_users";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_admins = $row['total_admins'];
    echo "Total Admin Users: " . $total_admins;
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
