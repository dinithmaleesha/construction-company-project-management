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
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $phoneNumbers = mysqli_real_escape_string($conn, $_POST['tel']);
    $company = "In-company";
    $status = "Active";

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    if (isUsernameTaken($conn, $username)) {
        $errorMessage = "Email already exists. Please choose a different email.";
        echo '<script>
            setTimeout(function() {
                window.location.href = "add-user.php?id=' . $user_id . '";
            }, 5000);
        </script>';
        exit();
    }

    // Call the addUser function
    $addUserResult = addUser($conn, $username, $hashedPassword, $hashedPassword, $fname, $lname, $phoneNumbers, $role, $company, $status);
    $user_id_fk = mysqli_insert_id($conn);


    if ($addUserResult) {
        $successMessage = "User added successfully!";
        echo '<script>
            setTimeout(function() {
                window.location.href = "add-user.php?id=' . $user_id . '";
            }, 5000);
        </script>';
    } else {
        $errorMessage = "Error adding user. Please try again.";
        echo '<script>
            setTimeout(function() {
                window.location.href = "add-user.php?id=' . $user_id . '";
            }, 5000);
        </script>';
    }
}

// Function to add a new user
function addUser($conn, $username, $password, $confirmPassword, $fname, $lname, $phoneNumbers, $role, $company, $status) {
    $insertQuery = "INSERT INTO `user`(`username`, `user_password`, `confirm_password`, `fname`, `lname`, `phone_number`, `user_type`, `company`, `status`)
    VALUES ('$username','$password','$confirmPassword','$fname','$lname', '$phoneNumbers','$role','$company','$status')";
    
    $result = mysqli_query($conn, $insertQuery);
    return $result;
}
?>
<script>
    function validateForm() {
        var fname = document.getElementById("fname").value;
        var lname = document.getElementById("lname").value;
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirmPassword").value;
        var role = document.getElementById("role").value;
        var phoneInputs = document.getElementById("tel").value;


        if (fname === "" || lname === "" || username === "" || password === "" || confirmPassword === "" || role === "") {
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
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(username)) {
            alert("Invalid email address.");
            return false;
        }
        if (password.length < 8) {
            alert("Password should be at least 8 characters long.");
            return false;
        }
        if (password !== confirmPassword) {
            alert("Password and Confirm Password do not match. Please enter them again.");
            return false;
        }
        if (!/^(\+\d{11}|\d{10})$/.test(phoneInputs)) {
            alert("Please enter a valid phone number with or without the country code (e.g., +94750000000 or 0750000000).");
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
    <title>Add User</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script></head>

<body>
    <?php include "gm-header.php"; ?>
    <div class="container mt-5">
        <form action="" method="post" onsubmit="return validateForm();">
            <h3 class="mt-4">Add New User</h3>
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
                <label for="username">Email:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div class="form-group">
                <label for="tel">Phone Number:</label>
                <input type="text" class="form-control" id="tel" name="tel" required>
                <!-- <button type="button" class="btn btn-primary mt-2" onclick="addPhoneField()">Add Another Number</button> -->
            </div>
            <script>
                function addPhoneField() {
                    var container = document.getElementById('phoneNumbersContainer');
                    var input = document.createElement('input');
                    input.type = 'text';
                    input.className = "form-control";
                    input.name = 'tp[]';
                    input.placeholder = 'Enter phone number';
                    input.style.marginTop = '5px';

                    container.insertBefore(input, container.querySelector('button'));
                }
            </script>
            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="">- Select Role -</option>
                    <option value="gm">General Manager</option>
                    <option value="spm">Senior Project Manager</option>
                    <option value="rm">Resource Manager</option>
                    <option value="pm">Project Manager</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-success" name="addUser">Add User</button>
        </form>
    </div>
    <div class="container mt-5"></div>

</body>
</html>
