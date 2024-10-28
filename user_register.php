<?php
include '../config.php'; // Make sure this file contains correct DB connection variables
include 'includes/header.php';

// Handle Create User
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO users (username, password, created_at) VALUES ('$username', '$password', '$created_at')";
    mysqli_query($conn, $sql);
    header("Location: user_registration.php"); // Redirect to prevent form resubmission
}

// Query to fetch all users
$sql = "SELECT id, username, created_at FROM users";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2, h3 {
            text-align: center;
            color: #333;
        }
        .form-container, .table-container {
            max-width: 600px;
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
    </style>
</head>
<body>

<h2>User Management</h2>

<div class="form-container">
    <h3>Register New User</h3>
    <form action="user_registration.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>
</div>

<div class="table-container">
    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Registered Date</th>
        </tr>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($user = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No users found.</td>
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
