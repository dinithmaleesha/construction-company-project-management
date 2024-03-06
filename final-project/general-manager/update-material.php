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

// Check if the update form is submitted
if (isset($_POST['updateMat'])) {
    $mat_id = mysqli_real_escape_string($conn, $_POST['mat_id']);
    $mat_name = mysqli_real_escape_string($conn, $_POST['mat_name']);
    $mat_type = mysqli_real_escape_string($conn, $_POST['mat_type']);
    $mat_quantity = mysqli_real_escape_string($conn, $_POST['mat_quantity']);
    $mat_unit = mysqli_real_escape_string($conn, $_POST['mat_unit']);

    if ($mat_quantity < 1) {
        echo '<script>alert("Material quantity should be greater than 0.");</script>';
    }

    // Call the updateMat function
    $updateMatResult = updateMat($conn, $mat_name, $mat_type, $mat_quantity, $mat_unit, $mat_id);

    if ($updateMatResult) {
        $successMessage = "Material updated successfully!";
        echo '<script>
            setTimeout(function() {
                window.location.href = "update-material.php?id=' . $user_id . '";
            }, 5000);
        </script>';
    } else {
        $errorMessage = "Error updating Material. Please try again.";
        echo '<script>
            setTimeout(function() {
                window.location.href = "update-material.php?id=' . $user_id . '";
            }, 5000);
        </script>';
    }
}

// Function to update material details
function updateMat($conn, $mat_name, $mat_type, $mat_quantity, $mat_unit, $mat_id) {
    $updateQuery = "UPDATE `material` SET 
                    `mat_name`='$mat_name',
                    `mat_type`='$mat_type',
                    `mat_quantity`='$mat_quantity',
                    `mat_unit`='$mat_unit' 
                WHERE `mat_id` = $mat_id";
    $result = mysqli_query($conn, $updateQuery);

    return $result;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Material</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <?php include 'gm-header.php'; ?>
    <div class="container mt-5">
        <h3 class="mt-4">Update Material</h3>
        <?php
            if (isset($successMessage)) {
                echo '<div class="alert alert-success">' . $successMessage . '</div>';
            } elseif (isset($errorMessage)) {
                echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
            }
        ?>

        <?php
        $selectQuery = "SELECT * FROM `material`";
        $result = mysqli_query($conn, $selectQuery);

        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td>' . $row['mat_id'] . '</td>
                    <td>' . $row['mat_name'] . '</td>
                    <td>' . $row['mat_type'] . '</td>
                    <td>' . $row['mat_quantity'] . '</td>
                    <td>' . $row['mat_unit'] . '</td>
                    <td>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editModal' . $row['mat_id'] . '">
                            Update
                        </button>
                    </td>
                </tr>';
                // Modal for editing material details
                echo '<div class="modal fade" id="editModal' . $row['mat_id'] . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel' . $row['mat_id'] . '" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel' . $row['mat_id'] . '">Edit Material</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <input type="hidden" name="mat_id" value="' . $row['mat_id'] . '">
                                <div class="form-group">
                                    <label for="mat_name">Name:</label>
                                    <input type="text" class="form-control" id="mat_name" name="mat_name" value="' . $row['mat_name'] . '" required>
                                </div>
                                <div class="form-group">
                                    <label for="mat_type">Type:</label>
                                    <select class="form-control" id="mat_type" name="mat_type" required>
                                        <option value="Special" ' . ($row['mat_type'] == 'Special' ? 'selected' : '') . '>Special</option>
                                        <option value="Normal" ' . ($row['mat_type'] == 'Normal' ? 'selected' : '') . '>Normal</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="mat_quantity">Quantity:</label>
                                    <input type="number" class="form-control" id="mat_quantity" name="mat_quantity" value="' . $row['mat_quantity'] . '" required>
                                </div>
                                <div class="form-group">
                                    <label for="mat_unit">Unit:</label>
                                    <select class="form-control" id="mat_unit" name="mat_unit" required>
                                        <option value="nos" ' . ($row['mat_unit'] == 'nos' ? 'selected' : '') . '>nos</option>
                                        <option value="cube" ' . ($row['mat_unit'] == 'cube' ? 'selected' : '') . '>cube</option>
                                    </select>
                                </div>
                                

                                <button type="submit" class="btn btn-primary" name="updateMat">Update Material</button>
                            </form>
                        </div>
                    </div>
                </div>
                </div>';
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
