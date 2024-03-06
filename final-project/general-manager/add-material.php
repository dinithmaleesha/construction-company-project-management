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

if (isset($_POST['addMaterial'])) {
    $mat_name = mysqli_real_escape_string($conn, $_POST['mat_name']);
    $mat_type = mysqli_real_escape_string($conn, $_POST['mat_type']);
    $mat_qt = mysqli_real_escape_string($conn, $_POST['mat_qt']);
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);

    mysqli_begin_transaction($conn);

    try {
        // Call the addMaterial function
        $addUserResult = addMaterial($conn, $mat_name, $mat_type, $mat_qt, $unit);

        if (!$addUserResult) {
            throw new Exception("Error adding Material. Please try again.");
        }

        $mat_id = mysqli_insert_id($conn);

        // Call the addMaterialUpdation function
        $matUpdationResult = addMaterialUpdation($conn, $mat_qt, $mat_id, $user_id);

        if (!$matUpdationResult) {
            throw new Exception("Error adding Material Updation. Please try again.");
        }

        mysqli_commit($conn);
        $successMessage = "Material added successfully!";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $errorMessage = $e->getMessage();
    }
}

function addMaterialUpdation($conn, $mat_qt, $mat_id, $user_id) {
    $mat_status = "new";
    $currentDate = date("Y-m-d");

    $query = "INSERT INTO `material_updation`(`mat_update_quantity`, `mat_status`, `mat_update_date`, `mat_id`, `user_id`)
    VALUES ('$mat_qt','$mat_status','$currentDate','$mat_id','$user_id')";

    $insertResult = mysqli_query($conn, $query);
    return $insertResult;
}

// Function to addMaterial
function addMaterial($conn, $mat_name, $mat_type, $mat_qt, $unit) {
    $insertQuery = "INSERT INTO `material`(`mat_name`, `mat_type`, `mat_quantity`, `mat_unit`)
                    VALUES ('$mat_name','$mat_type','$mat_qt','$unit')";
    
    $result = mysqli_query($conn, $insertQuery);
    return $result;
}

?>
<script>
    function validateForm() {
        var mat_name = document.getElementById("mat_name").value;
        var mat_type = document.getElementById("mat_type").value;
        var mat_qt = document.getElementById("mat_qt").value;
        var unit = document.getElementById("unit").value;


        if (mat_name === "" || mat_type === "" || mat_qt === "" || unit === "" || ) {
            alert("All fields are required. Please fill them out.");
            return false;
        }
        if (mat_qt < 1) {
            alert("Material quantity should be greater than 0.");
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
    <title>Add Material</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script></head>

<body>
    <?php include "gm-header.php"; ?>
    <div class="container mt-5">
        <form action="" method="post" onsubmit="return validateForm();">
            <h3 class="mt-4">Add New Material</h3>
            <?php
                if (isset($successMessage)) {
                    echo '<div class="alert alert-success">' . $successMessage . '</div>';
                } elseif (isset($errorMessage)) {
                    echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
                }
            ?>
            <div class="form-group">
                <label for="mat_name">Name:</label>
                <input type="text" class="form-control" id="mat_name" name="mat_name" required>
            </div>
            <div class="form-group">
                <label for="mat_type">Type:</label>
                <select class="form-control" id="mat_type" name="mat_type" required>
                    <option value="">- Select Type -</option>
                    <option value="special">Special</option>
                    <option value="normal">Normal</option>
                </select>
            </div>
            <div class="form-group">
                <label for="mat_qt">Quantity</label>
                <input type="number" class="form-control" id="mat_qt" name="mat_qt" required>
            </div>
            <div class="form-group">
                <label for="unit">Unit:</label>
                <select class="form-control" id="unit" name="unit" required>
                    <option value="">- Select Unit -</option>
                    <option value="nos">nos</option>
                    <option value="cube">cube</option>
                </select>
            </div>
            
            
            <button type="submit" class="btn btn-success" name="addMaterial">Add Material</button>
        </form>
    </div>
    <div class="container mt-5"></div>

</body>
</html>
