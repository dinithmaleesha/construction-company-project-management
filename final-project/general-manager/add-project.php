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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$projectManagersQuery = "SELECT `user_id`, CONCAT(`fname`, ' ', `lname`) AS `full_name` 
                         FROM `user` 
                         WHERE `user_type` = 'pm' AND `user_id` NOT IN (
                             SELECT DISTINCT `pm_id` FROM `project` WHERE `project_status` = 'Ongoing'
                         )";

$projectManagersResult = $conn->query($projectManagersQuery);

$projectManagers = [];
while ($row = $projectManagersResult->fetch_assoc()) {
    $projectManagers[] = $row;
}


function addProject($conn, $projectName, $projectDescription, $startDate, $endDate, $projectStatus, $userId,  $projectManagerId) {
    $insertQuery = "INSERT INTO `project`(`project_name`, `description`, `start_date`, `end_date`, `project_status`, `client_id`, `pm_id`)
                    VALUES ('$projectName','$projectDescription','$startDate','$endDate','$projectStatus','$userId','$projectManagerId')";
    $resultProject = mysqli_query($conn, $insertQuery);

    if(!$resultProject){
        console.error("Error adding project. Please try again.");
        $errorMessage = "Error adding project. Please try again.";
        echo '<script>
                setTimeout(function() {
                    window.location.href = "add-project.php?id=' . $user_id . '";
                }, 5000);
            </script>';
        exit();
    }

    return $resultProject;
}

function addUser($conn, $username, $password, $confirmPassword, $fname, $lname, $tp, $clientType, $company, $clientStatus) {
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    $insertQuery = "INSERT INTO `user`(`username`, `user_password`, `confirm_password`, `fname`, `lname`, `phone_number`, `user_type`, `company`, `status`)
    VALUES ('$username','$hashPassword','$hashPassword','$fname','$lname', '$tp', '$clientType','$company','$clientStatus')";
    
    $result = mysqli_query($conn, $insertQuery);
    return $result;
}

function getUserIdByUsername($conn, $username) {
    $selectQuery = "SELECT user_id FROM user WHERE username = '$username' AND user_type = 'Client'";
    $result = mysqli_query($conn, $selectQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['user_id'];
    }

    return false;
}


if (isset($_POST['addProject'])) {
    $projectName = mysqli_real_escape_string($conn, $_POST['projectName']);
    $projectDescription = mysqli_real_escape_string($conn, $_POST['projectDescription']);
    $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
    $endDate = mysqli_real_escape_string($conn, $_POST['endDate']);
    $projectManagerId = mysqli_real_escape_string($conn, $_POST['projectManager']);

    $clientType = "Client";
    $clientStatus = "Active";
    $projectStatus = "Ongoing";

    // Check if the client is new or existing
    $clientType = $_POST['clientStatus'];
    $selectClientId = null;

    if ($clientType === 'new') {
        // New client, add the user
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);
        $tp = mysqli_real_escape_string($conn, $_POST['tp']);
        $company = mysqli_real_escape_string($conn, $_POST['company']);

        $addUserResult = addUser($conn, $username, $password, $confirmPassword, $fname, $lname, $tp, $clientType, $company, $clientStatus);

        if ($addUserResult) {
            $selectClientId = mysqli_insert_id($conn);
        } else {
            $errorMessage = "Error adding user. Please try again.";
            exit();
        }
    } else {
        // Existing client, get the client ID
        $selectClientId = mysqli_real_escape_string($conn, $_POST['existingClientSelect']);
    }

    // Add the project
    $addProjectResult = addProject($conn, $projectName, $projectDescription, $startDate, $endDate, $projectStatus, $selectClientId,  $projectManagerId);

    if ($addProjectResult) {
        $successMessage = "Project added successfully!";
        echo '<script>
            setTimeout(function() {
                window.location.href = "add-project.php?id=' . $user_id . '";
            }, 5000);
        </script>';
    } else {
        $errorMessage = "Error adding project. Please try again.";
        echo '<script>
            setTimeout(function() {
                window.location.href = "add-project.php?id=' . $user_id . '";
            }, 5000);
        </script>';
    }
}
?>
<script>
    function validateForm() { 
        var clientStatus = document.getElementById('clientStatus').value;

        if (clientStatus === 'existing') {
            var projectName = document.getElementById('projectName').value;
            var projectDescription = document.getElementById('projectDescription').value;
            var startDate = document.getElementById('startDate').value;
            var endDate = document.getElementById('endDate').value;
            var projectManager = document.getElementById('projectManager').value;

            if (!projectName || !projectDescription || !startDate || !endDate || !projectManager) {
                alert("Alllll fields are required. Please fill them out.");
                return false;
            }
            
            if (!isValidDate(startDate)) {
                alert("Invalid start date format. Please enter a valid date.");
                return false;
            }

        } else {
            var projectName = document.getElementById('projectName').value;
            var projectDescription = document.getElementById('projectDescription').value;
            var startDate = document.getElementById('startDate').value;
            var endDate = document.getElementById('endDate').value;
            var projectManager = document.getElementById('projectManager').value;

            var clientFname = document.getElementById('fname').value;
            var clientLname = document.getElementById('lname').value;
            var clientUsername = document.getElementById('username').value;
            var clientPassword = document.getElementById('password').value;
            var clientConfirmPassword = document.getElementById('confirmPassword').value;
            var clientTp = document.getElementById('tp').value;
            var clientCompany = document.getElementById('company').value;

            if (!projectName || !projectDescription || !startDate || !endDate || !projectManager ||
                !clientFname || !clientLname || !clientUsername || !clientPassword || !clientConfirmPassword || !clientTp || !clientCompany) {
                alert("All fields are required. Please fill them out.");
                return false;
            }
            
            if (!isValidDate(startDate)) {
                alert("Invalid start date format. Please enter a valid date.");
                return false;
            }
            if (/\d/.test(clientFname) || /\d/.test(clientLname)) {
                alert("Client name should not contain numbers.");
                return false;
            }
            
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(clientUsername)) {
                alert("Invalid email address.");
                return false;
            }
            if (clientPassword.length < 8) {
                alert("Client Password should be at least 8 characters long.");
                return false;
            }

            if (clientPassword !== clientConfirmPassword) {
                alert("Client Password and Confirm Password do not match. Please enter them again.");
                return false;
            }
            if (clientPassword.trim() === '') {
                alert('Please enter a valid login ID (password).');
                return false;
            }
            if (!/^(\+\d{11}|\d{10})$/.test(clientTp)) {
                alert("Please enter a valid phone number with or without the country code (e.g., +94750000000 or 0750000000).");
                return false;
            }
            return true;

        }
        

        
    }
</script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php include "gm-header.php"; ?>
    <div class="container mt-5">
        <form action="" method="post" onsubmit="return validateForm()">
            <h3 class="mt-4">Create New Project</h3>
            <?php
            if (isset($successMessage)) {
                echo '<div class="alert alert-success">' . $successMessage . '</div>';
            } elseif (isset($errorMessage)) {
                echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
            }
            ?>
            <div class="form-group">
                <label for="projectName">Project Name:</label>
                <input type="text" class="form-control" id="projectName" name="projectName" required>
            </div>

            <div class="form-group">
                <label for="projectDescription">Project Description:</label>
                <textarea class="form-control" id="projectDescription" name="projectDescription" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="startDate">Start Date:</label>
                <input type="date" class="form-control" id="startDate" name="startDate" required>
            </div>

            <div class="form-group">
                <label for="endDate">End Date:</label>
                <input type="date" class="form-control" id="endDate" name="endDate" required>
            </div>

            <div class="form-group">
                <label for="projectManager">Project Manager:</label>
                <select class="form-control" id="projectManager" name="projectManager" required>
                    <option value="">- Select Project Manager -</option>
                    <?php foreach ($projectManagers as $manager): ?>
                        <option value="<?php echo $manager['user_id']; ?>"><?php echo $manager['full_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>


            <hr>
            <h4>Client Details</h4>

            <div class="form-group">
                <label for="clientStatus">Select Client Type:</label>
                <select class="form-control" id="clientStatus" name="clientStatus">
                    <option value="new">New Client</option>
                    <option value="existing">Existing Client</option>
                </select>
            </div>

            <div id="newClientDetails">
                
            <div class="form-group">
                <label for="fname">Client First Name:</label>
                    <input type="text" class="form-control" id="fname" name="fname" >
                </div>
                <div class="form-group">
                    <label for="lname">Client Last Name:</label>
                    <input type="text" class="form-control" id="lname" name="lname" >
                </div>
                <div class="form-group">
                    <label for="username">Email:</label>
                    <input type="text" class="form-control" id="username" name="username" >
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" >
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" >
                </div>
                <div class="form-group">
                    <label for="tp">Phone Number:</label>
                    <input type="text" class="form-control" id="tp" name="tp" >
                </div>
                <div class="form-group">
                    <label for="company">Company:</label>
                    <input type="text" class="form-control" id="company" name="company" >
                </div>
            </div>
            
            


            <div id="existingClientDetails" style="display: none;">
            <?php
                $sql = "SELECT `user_id`, `username`, `user_password`, `confirm_password`, `fname`, `lname`, `phone_number`,  `company` FROM `user` WHERE user_type = 'Client'";
                $result = mysqli_query($conn, $sql);

                // Check for errors
                if (!$result) {
                    die("Error fetching clients: " . mysqli_error($conn));
                }
            ?>
                <div class="form-group">
                    <label for="existingClientSelect">Select Existing Client:</label>
                    <select class="form-control" id="existingClientSelect" name="existingClientSelect">
                        <option value="">- Select Existing Client -</option>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['user_id'] . '">' . $row['fname'] . ' ' . $row['lname'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" name="addProject">Add Project</button>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var clientStatusDropdown = document.getElementById('clientStatus');
                var newClientDetails = document.getElementById('newClientDetails');
                var existingClientDetails = document.getElementById('existingClientDetails');

                clientStatusDropdown.addEventListener('change', function () {
                    var selectedOption = clientStatusDropdown.value;

                    if (selectedOption === 'new') {
                        newClientDetails.style.display = 'block';
                        existingClientDetails.style.display = 'none';
                    } else if (selectedOption === 'existing') {
                        newClientDetails.style.display = 'none';
                        existingClientDetails.style.display = 'block';
                    } else {
                        newClientDetails.style.display = 'none';
                        existingClientDetails.style.display = 'none';
                    }
                });
            });
        </script>

    </div>
    <div class="container mt-5"> </div>
</body>
</html>
