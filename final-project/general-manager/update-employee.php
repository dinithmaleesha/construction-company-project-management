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
if (isset($_POST['updateEmp'])) {
    $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
    $emp_fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $emp_lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $job_title = mysqli_real_escape_string($conn, $_POST['job_title']);
    $emp_level = mysqli_real_escape_string($conn, $_POST['emp_level']);
    $emp_note = mysqli_real_escape_string($conn, $_POST['emp_note']);
    $emp_status = mysqli_real_escape_string($conn, $_POST['status']);

    if (!preg_match("/^[a-zA-Z]+$/", $emp_fname)) {
        $error_fname = "First name should only contain letters.";
    }
    
    if (!preg_match("/^[a-zA-Z]+$/", $emp_lname)) {
        $error_lname = "Last name should only contain letters.";
    }
    
    if (isset($error_fname) || isset($error_lname)) {
        echo '<script>alert("First name should only contain letters. Last name should only contain letters.");</script>';
    }

    // Call the updateEmp function
    $updateEmpResult = updateEmp($conn, $emp_fname, $emp_lname, $job_title, $emp_level, $emp_status, $emp_note, $emp_id);

    if ($updateEmpResult) {
        $successMessage = "Employee updated successfully!";
        // echo '<script>
        //     setTimeout(function() {
        //         window.location.href = "update-user.php?id=' . $user_id . '";
        //     }, 5000);
        // </script>';
    } else {
        $errorMessage = "Error updating Employee. Please try again.";
    }
}

// Function to update user details
function updateEmp($conn, $emp_fname, $emp_lname, $job_title, $emp_level, $emp_status, $emp_note, $emp_id) {
    $updateQuery = "UPDATE `employee` SET
                        `emp_fname`='$emp_fname',
                        `emp_lname`='$emp_lname',
                        `job_title`='$job_title',
                        `emp_level`='$emp_level',
                        `emp_status`='$emp_status',
                        `emp_note`='$emp_note' 
                    WHERE `emp_id`= $emp_id";
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
    <title>Update Employee</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <?php include 'gm-header.php'; ?>
    <div class="container mt-5">
        <h3 class="mt-4">Update Employee</h3>
        <?php
            if (isset($successMessage)) {
                echo '<div class="alert alert-success">' . $successMessage . '</div>';
            } elseif (isset($errorMessage)) {
                echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
            }
        ?>

        <?php
        $selectQuery = "SELECT * FROM `employee`";
        $result = mysqli_query($conn, $selectQuery);

        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Job Titile</th>
                        <th>Job Level</th>
                        <th>Note</th>
                        <th>Availability</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td>' . $row['emp_id'] . '</td>
                    <td>' . $row['emp_fname'] . '</td>
                    <td>' . $row['emp_lname'] . '</td>
                    <td>' . $row['job_title'] . '</td>
                    <td>' . $row['emp_level'] . '</td>
                    <td>' . $row['emp_note'] . '</td>
                    <td>' . $row['emp_availability'] . '</td>
                    <td>' . $row['emp_status'] . '</td>
                    <td>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editModal' . $row['emp_id'] . '">
                            Update
                        </button>
                    </td>
                </tr>';
                // Modal for editing emp details
                echo '<div class="modal fade" id="editModal' . $row['emp_id'] . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel' . $row['emp_id'] . '" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel' . $row['emp_id'] . '">Edit User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <input type="hidden" name="emp_id" value="' . $row['emp_id'] . '">
                                <div class="form-group">
                                    <label for="fname">First Name:</label>
                                    <input type="text" class="form-control" id="fname" name="fname" value="' . $row['emp_fname'] . '" required>
                                </div>
                                <div class="form-group">
                                    <label for="lname">Last Name:</label>
                                    <input type="text" class="form-control" id="lname" name="lname" value="' . $row['emp_lname'] . '" required>
                                </div>
                                <div class="form-group">
                                    <label for="job_title">Job Title:</label>
                                    <select class="form-control" id="job_title" name="job_title" required>
                                        <option value="Site Engineer" ' . ($row['job_title'] == 'Site Engineer' ? 'selected' : '') . '>Site Engineer</option>
                                        <option value="Carpenter" ' . ($row['job_title'] == 'Carpenter' ? 'selected' : '') . '>Carpenter</option>
                                        <option value="Electrician" ' . ($row['job_title'] == 'Electrician' ? 'selected' : '') . '>Electrician</option>
                                        <option value="Plumber" ' . ($row['job_title'] == 'Plumber' ? 'selected' : '') . '>Plumber</option>
                                        <option value="Mason" ' . ($row['job_title'] == 'Mason' ? 'selected' : '') . '>Mason</option>
                                        <option value="Worker" ' . ($row['job_title'] == 'Worker' ? 'selected' : '') . '>Worker</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="emp_level">Job Level:</label>
                                    <select class="form-control" id="emp_level" name="emp_level" required>
                                        <option value="Level 01" ' . ($row['emp_level'] == 'Level 01' ? 'selected' : '') . '>Level 01</option>
                                        <option value="Level 02" ' . ($row['emp_level'] == 'Level 02' ? 'selected' : '') . '>Level 02</option>
                                        <option value="Level 03" ' . ($row['emp_level'] == 'Level 03' ? 'selected' : '') . '>Level 03</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="emp_note">Note:</label>
                                    <input type="text" class="form-control" id="emp_note" name="emp_note" value="' . $row['emp_note'] . '" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="Active" ' . ($row['emp_status'] == 'Active' ? 'selected' : '') . '>Active</option>
                                        <option value="Inactive" ' . ($row['emp_status'] == 'Inactive' ? 'selected' : '') . '>In-active</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary" name="updateEmp">Update Employee</button>
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
