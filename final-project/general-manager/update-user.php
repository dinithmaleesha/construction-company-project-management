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
if (isset($_POST['updateUser'])) {
    $userId = mysqli_real_escape_string($conn, $_POST['user_id']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $tp = mysqli_real_escape_string($conn, $_POST['tp']);
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Call the updateUser function
    $updateUserResult = updateUser($conn, $username, $fname, $lname, $tp, $user_type, $company, $status, $userId);

    if ($updateUserResult) {
        $successMessage = "User updated successfully!";
        echo '<script>
            setTimeout(function() {
                window.location.href = "update-user.php?id=' . $user_id . '";
            }, 5000);
        </script>';
    } else {
        $errorMessage = "Error updating user. Please try again.";
    }
}

// Function to update user details
function updateUser($conn, $username, $fname, $lname, $tp, $user_type, $company, $status, $userId) {
    $updateQuery = "UPDATE `user` SET
                        `username`='$username',
                        `fname`='$fname',
                        `lname`='$lname',
                        `phone_number` = '$tp',
                        `user_type`='$user_type',
                        `company`='$company',
                        `status`='$status'
                    WHERE `user_id` = '$userId'";
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
    <title>Update User</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <?php include 'gm-header.php'; ?>
    <div class="container mt-5">
        <h3 class="mt-4">Update User</h3>
        <?php
            if (isset($successMessage)) {
                echo '<div class="alert alert-success">' . $successMessage . '</div>';
            } elseif (isset($errorMessage)) {
                echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
            }
        ?>

        <?php
        $selectQuery = "SELECT * FROM `user` WHERE `user_type` != 'gm'";
        $result = mysqli_query($conn, $selectQuery);

        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>User Type</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td>' . $row['user_id'] . '</td>
                    <td>' . $row['fname'] . '</td>
                    <td>' . $row['lname'] . '</td>
                    <td>' . $row['username'] . '</td>
                    <td>' . $row['phone_number'] . '</td>
                    <td>' . ($row['user_type'] === 'pm' ? 'Project Manager' : ($row['user_type'] === 'rm' ? 'Resource Manager' : ($row['user_type'] === 'spm' ? 'Senior Project Manager' : $row['user_type']))) . '</td>
                    <td>' . $row['company'] . '</td>
                    <td>' . $row['status'] . '</td>
                    <td>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editModal' . $row['user_id'] . '">
                            Update
                        </button>
                    </td>
                </tr>';
                // Modal for editing user details
                echo '<div class="modal fade" id="editModal' . $row['user_id'] . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel' . $row['user_id'] . '" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel' . $row['user_id'] . '">Edit User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" onsubmit="return validateForm()">
                                <input type="hidden" name="user_id" value="' . $row['user_id'] . '">
                                <div class="form-group">
                                    <label for="fname">First Name:</label>
                                    <input type="text" class="form-control" id="fname" name="fname" value="' . $row['fname'] . '" required>
                                </div>
                                <div class="form-group">
                                    <label for="lname">Last Name:</label>
                                    <input type="text" class="form-control" id="lname" name="lname" value="' . $row['lname'] . '" required>
                                </div>
                                <div class="form-group">
                                    <label for="username">Email:</label>
                                    <input type="text" class="form-control" id="username" name="username" value="' . $row['username'] . '" required>
                                </div>
                                <div class="form-group">
                                    <label for="tp">Phone Number:</label>
                                    <input type="text" class="form-control" id="tp" name="tp" value="' . $row['phone_number'] . '" required>
                                </div>
                                <div class="form-group">
                                    <label for="user_type">Role:</label>
                                    <select class="form-control" id="user_type" name="user_type" required>
                                        <option value="gm" ' . ($row['user_type'] == 'gm' ? 'selected' : '') . '>General Manager</option>
                                        <option value="spm" ' . ($row['user_type'] == 'spm' ? 'selected' : '') . '>Senior Project Manager</option>
                                        <option value="rm" ' . ($row['user_type'] == 'rm' ? 'selected' : '') . '>Resource Manager</option>
                                        <option value="pm" ' . ($row['user_type'] == 'pm' ? 'selected' : '') . '>Project Manager</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="company">Company:</label>
                                    <input type="text" class="form-control" id="company" name="company" value="' . $row['company'] . '" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="Active" ' . ($row['status'] == 'Active' ? 'selected' : '') . '>Active</option>
                                        <option value="Inactive" ' . ($row['status'] == 'Inactive' ? 'selected' : '') . '>In-active</option>
                                        <option value="Deleted" ' . ($row['status'] == 'Deleted' ? 'selected' : '') . '>Deleted</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary" name="updateUser">Update User</button>
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

    <script>
        function validateForm() {
            var fname = document.getElementById("fname").value;
            var lname = document.getElementById("lname").value;
            var email = document.getElementById("username").value;
            var tp = document.getElementById("tp").value;
            var user_type = document.getElementById("user_type").value;
            var company = document.getElementById("company").value;
            var status = document.getElementById("status").value;

            if (fname.trim() === '' || lname.trim() === '' || email.trim() === '' || tp.trim() === '' || user_type.trim() === '' || company.trim() === '' || status.trim() === '') {
                alert("All fields are required.");
                return false;
            }

            if (!/^[a-zA-Z]+$/.test(fname)) {
                alert("First name should only contain letters.");
                return false;
            }

            if (!/^[a-zA-Z]+$/.test(lname)) {
                alert("Last name should only contain letters.");
                return false;
            }

            if (!isValidEmail(email)) {
                alert("Please enter a valid email address.");
                return false;
            }
            if (!/^\d{10}$/.test(tp)) {
                alert("Please enter a valid phone number.");
                return false;
            }


            return true;
        }

        function isValidEmail(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>

    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
