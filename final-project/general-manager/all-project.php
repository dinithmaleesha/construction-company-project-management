<?php

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Project</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <?php include 'gm-header.php'; ?>
    <div class="container mt-5">
        <h3 class="mt-4">All Project Details</h3>
        <?php
            if (isset($successMessage)) {
                echo '<div class="alert alert-success">' . $successMessage . '</div>';
            } elseif (isset($errorMessage)) {
                echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
            }
        ?>

        <?php
        $selectQuery = "SELECT 
                            p.project_id,
                            p.project_name,
                            p.description,
                            p.start_date,
                            p.end_date,
                            p.project_status,
                            p.pm_id,
                            CONCAT(u_client.fname, ' ', u_client.lname) AS client_fullname,
                            CONCAT(u_pm.fname, ' ', u_pm.lname) AS pm_fullname
                        FROM
                            project p
                        JOIN
                            user u_client ON p.client_id = u_client.user_id
                        JOIN
                            user u_pm ON p.pm_id = u_pm.user_id";

        $result = mysqli_query($conn, $selectQuery);

        if (mysqli_num_rows($result) > 0) {
            

            echo '<table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project Name</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Client Name</th>
                        <th>Project Manager</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td>' . $row['project_id'] . '</td>
                    <td>' . $row['project_name'] . '</td>
                    <td>' . $row['description'] . '</td>
                    <td>' . $row['start_date'] . '</td>
                    <td>' . $row['end_date'] . '</td>
                    <td>' . $row['client_fullname'] . '</td>
                    <td>' . $row['pm_fullname'] . '</td>
                    <td>' . $row['project_status'] . '</td>
                    <td>

                        <a href="update-project.php?id='. $user_id .'&project_id=' . $row['project_id'] . '" class="btn btn-success">
                            Update
                        </a>
                    </td>
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
