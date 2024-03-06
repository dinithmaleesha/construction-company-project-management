<?php
// update-user.php

session_start();
if (!isset($_SESSION['gm']) || $_SESSION['gm'] !== true) {
    header("Location: ../php/access-denied.php");
    exit();
}
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    header("location: ../php/access-denied.php");
    exit();
}
@include '../php/config.php';

function getUserRoleLabel($roleCode) {
    switch ($roleCode) {
        case 'rm':
            return 'Resource Manager';
        case 'gm':
            return 'General Manager';
        case 'pm':
            return 'Project Manager';
        case 'spm':
            return 'Senior Project Manager';
        default:
            return 'Unknown Role';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <?php include 'gm-header.php'; ?>
    <div class="container mt-5">
        <?php
            // Display success or error messages
            if (isset($successMessage)) {
                echo '<div class="alert alert-success">' . $successMessage . '</div>';
            } elseif (isset($errorMessage)) {
                echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
            }
        ?>
        
        <h3>User Login Activity</h3>

        <?php
        $selectQuery = "SELECT l.log_id, l.user_id, l.date, l.login_time, l.logout_time, u.full_name, u.user_role
                        FROM user_login_activity l
                        JOIN tb_user u ON l.user_id = u.id";
        $result = mysqli_query($conn, $selectQuery);

        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User_ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Date</th>
                        <th>Login</th>
                        <th>Logout</th>
                    </tr>
                </thead>
                <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                        <td>' . $row['log_id'] . '</td>
                        <td>' . $row['user_id'] . '</td>
                        <td>' . $row['full_name'] . '</td>
                        <td>' . getUserRoleLabel($row['user_role']) . '</td>
                        <td>' . $row['date'] . '</td>
                        <td>' . $row['login_time'] . '</td>
                        <td>' . $row['logout_time'] . '</td>
                    </tr>';
            }
            echo '</tbody>
                  </table>';
        } else {
            echo '<p>No users found.</p>';
        }
        ?>
    </div>

    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
