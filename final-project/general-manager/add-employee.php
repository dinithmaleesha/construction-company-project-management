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

if (isset($_POST['addUser'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $jobTitle = mysqli_real_escape_string($conn, $_POST['jobTitle']);
    $empLevel = mysqli_real_escape_string($conn, $_POST['empLevel']);
    $note = isset($_POST['note']) ? mysqli_real_escape_string($conn, $_POST['note']) : "No Note";
    $emp_status = "Active";
    $emp_availability = "Yes";

    // Call the addUser function
    $addUserResult = addEmp($conn, $fname, $lname, $jobTitle, $empLevel, $emp_status, $emp_availability, $note);

    if ($addUserResult) {
        $successMessage = "Employee added successfully!";
        
    } else {
        $errorMessage = "Error adding Employee. Please try again.";
        
    }
}

// Function to add a new employee
function addEmp($conn, $fname, $lname, $jobTitle, $empLevel, $emp_status, $emp_availability, $note) {
    $insertQuery = "INSERT INTO `employee`(`emp_fname`, `emp_lname`, `job_title`, `emp_level`, `emp_status`, `emp_availability`, `emp_note`)
                    VALUES ('$fname','$lname','$jobTitle','$empLevel','$emp_status','$emp_availability','$note')";
    
    $result = mysqli_query($conn, $insertQuery);
    return $result;
}
?>
<script>
    function validateForm() {
        var fname = document.getElementById("fname").value;
        var lname = document.getElementById("lname").value;
        var jobTitle = document.getElementById("jobTitle").value;
        var empLevel = document.getElementById("empLevel").value;


        if (fname === "" || lname === "" || jobTitle === "" || empLevel === "" || ) {
            alert("All fields are required. Please fill them out.");
            return false;
        }

        if (/\d/.test(fname)) {
            alert("First name should not contain numbers.");
            return false;
        }
        if (/\d/.test(lname)) {
            alert("Last name should not contain numbers.");
            return false;
        }
        
        return true;
    }
</script>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script></head>

<body>
    <?php include "gm-header.php"; ?>
    <div class="container mt-5">
        <form action="" method="post" onsubmit="return validateForm();">
            <h3 class="mt-4">Add New Employee</h3>
            <?php
                if (isset($successMessage)) {
                    echo '<div class="alert alert-success">' . $successMessage . '</div>';
                } elseif (isset($errorMessage)) {
                    echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
                }
            ?>
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" class="form-control" id="fname" name="fname" required>
            </div>
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" class="form-control" id="lname" name="lname" required>
            </div>
            <div class="form-group">
                <label for="jobTitle">Job Title:</label>
                <select class="form-control" id="jobTitle" name="jobTitle" required>
                    <option value="">- Select Role -</option>
                    <option value="Site Engineer">Site Engineer</option>
                    <option value="Carpenter">Carpenter</option>
                    <option value="Electrician">Electrician</option>
                    <option value="Plumber">Plumber</option>
                    <option value="Mason">Mason</option>
                    <option value="Worker">Worker</option>
                </select>
            </div>
            <div class="form-group">
                <label for="empLevel">Employee Level:</label>
                <select class="form-control" id="empLevel" name="empLevel" required>
                    <option value="Level 01">Level 01</option>
                    <option value="Level 02">Level 02</option>
                    <option value="Level 03">Level 03</option>
                </select>
            </div>
            <div class="form-group">
                <label for="note">Note:</label>
                <input type="text" class="form-control" id="note" name="note">
            </div>
            
            <button type="submit" class="btn btn-success" name="addUser">Add Employee</button>
        </form>
    </div>
    <div class="container mt-5"></div>

</body>
</html>
