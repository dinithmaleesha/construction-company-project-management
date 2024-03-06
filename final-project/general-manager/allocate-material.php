<!-- Allocate Material to the PROJECT -->

<?php
session_start();
if (!isset($_SESSION['gm']) || $_SESSION['gm'] !== true) {
    header("Location: ../php/access-denied.php");
    exit();
}
if (isset($_GET['id']) && isset($_GET['project-id'])) {
    $user_id = $_GET['id'];
    $project_id = $_GET['project-id'];
} else {
    header("location: ../php/access-denied.php");
    exit();
}
@include '../php/config.php';


if (isset($_GET['id']) && isset($_GET['project-id'])) {
    $user_id = $_GET['id'];
    $project_id = $_GET['project-id'];

    $matQuery = "SELECT `mat_id`, `mat_name`, `mat_type`, `mat_quantity`, `mat_unit` FROM `material`";
    $materialResult = mysqli_query($conn, $matQuery);

    $material = [];
    while ($row = $materialResult->fetch_assoc()) {
        $material[] = $row;
    }

} else {
    header("location: home.php?id=<?php echo $user_id; ?>");
    exit();
}



if (isset($_POST['addMat'])) {
    $material_id = isset($_POST['materialDropdown']) ? $_POST['materialDropdown'] : null;
    $qt = isset($_POST['materialQuantity']) ? $_POST['materialQuantity'] : null;
    $note = isset($_POST['materialNote']) ? $_POST['materialNote'] : null;

    if (!empty($material_id) && !empty($qt) && !empty($note)) {
        $checkQuantityQuery = "SELECT mat_quantity FROM material WHERE mat_id = ?";
        $checkQuantityStatement = mysqli_prepare($conn, $checkQuantityQuery);
        mysqli_stmt_bind_param($checkQuantityStatement, "i", $material_id);
        mysqli_stmt_execute($checkQuantityStatement);

        $checkQuantityResult = mysqli_stmt_get_result($checkQuantityStatement);

        if ($checkQuantityResult) {
            $row = mysqli_fetch_assoc($checkQuantityResult);
            $currentQuantity = $row['mat_quantity'];

            if ($qt > $currentQuantity) {
                $errorMessage = "Entered quantity is greater than available quantity.";
            } else {
                // Start a database transaction
                mysqli_begin_transaction($conn);

                $insertMaterialAllocation = "INSERT INTO `material_allocation`(`allocation_description`) VALUES (?)";
                $insertMaterialAllocationStatement = mysqli_prepare($conn, $insertMaterialAllocation);
                mysqli_stmt_bind_param($insertMaterialAllocationStatement, "s", $note);
                $resultMaterialAllocation = mysqli_stmt_execute($insertMaterialAllocationStatement);

                if ($resultMaterialAllocation) {
                    $allocationId = mysqli_insert_id($conn);
                    $allocationStatus = "allocate";

                    $now = date("Y-m-d");

                    $insertProjectMaterialAllocation = "INSERT INTO `project_material_allocation`
                                                        (`project_id`, `mat_id`, `allocation_id`, `material_allocation_quantity`, `allocation_date`, `allocation_status`)
                                                        VALUES (?, ?, ?, ?, ?, ?)";
                    $insertProjectMaterialAllocationStatement = mysqli_prepare($conn, $insertProjectMaterialAllocation);
                    mysqli_stmt_bind_param($insertProjectMaterialAllocationStatement, "iiisss", $project_id, $material_id, $allocationId, $qt, $now, $allocationStatus);
                    $resultProjectMaterialAllocation = mysqli_stmt_execute($insertProjectMaterialAllocationStatement);

                    if ($resultProjectMaterialAllocation) {
                        // Update material quantity
                        $updateMatQt = "UPDATE material SET mat_quantity = mat_quantity - ? WHERE mat_id = ?";
                        $updateMatQtStatement = mysqli_prepare($conn, $updateMatQt);
                        mysqli_stmt_bind_param($updateMatQtStatement, "ii", $qt, $material_id);
                        $resultUpdateMatQt = mysqli_stmt_execute($updateMatQtStatement);

                        if ($resultUpdateMatQt) {
                            mysqli_commit($conn);

                            $successMessage = "Material Allocated";
                        } else {
                            mysqli_rollback($conn);
                        }
                    } else {
                        mysqli_rollback($conn);
                    }
                } else {
                    mysqli_rollback($conn);
                }
            }
        } else {
            echo "Error checking material quantity in the database.";
        }
    } else {
        $errorMessage = "Please fill out all the fields.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Material</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <!-- header -->
    <?php include "gm-header.php"; ?>


    <div class="container mt-5">
        <h2 class="mt-4">
            <a href="project.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            Add Material
        </h2>
        <?php
        if (isset($successMessage) || isset($errorMessage)) {
            echo '<div id="alertContainer">';
            if (isset($successMessage)) {
                echo '<div class="alert alert-success">' . $successMessage . '</div>';
            } elseif (isset($errorMessage)) {
                echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
            }
            echo '</div>';
        }
        ?>
        <script>
            // Automatically hide the alert messages after 5000 milliseconds (5 seconds)
            $(document).ready(function () {
                setTimeout(function () {
                    $("#alertContainer").fadeOut(1000);
                }, 5000);
            });
        </script>
        <form id="materialForm" action="" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="materialDropdown">Material Name:</label>
                    <select class="form-control" id="materialDropdown" name="materialDropdown" onchange="this.form.submit()">
                        <option value="">- Select Material -</option>
                        <?php
                        foreach ($material as $mat) {
                            echo '<option value="' . $mat['mat_id'] . '">' . $mat['mat_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="materialQuantity">Quantity:</label>
                    <input type="number" class="form-control" id="materialQuantity" name="materialQuantity" min="1" max="<?php echo $availableQuantity; ?>" required>
                </div>

                <?php

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $matId = isset($_POST['materialDropdown']) ? $_POST['materialDropdown'] : '';

                    if (!empty($matId)) {
                        $checkQuantityQuery = "SELECT `mat_quantity` FROM `material` WHERE `mat_id` = ?";
                        $checkQuantityStatement = mysqli_prepare($conn, $checkQuantityQuery);
                        mysqli_stmt_bind_param($checkQuantityStatement, "i", $matId);
                        mysqli_stmt_execute($checkQuantityStatement);
                        $checkQuantityResult = mysqli_stmt_get_result($checkQuantityStatement);

                        if ($checkQuantityResult) {
                            $row = mysqli_fetch_assoc($checkQuantityResult);
                            $availableQuantity = $row['mat_quantity'];

                            echo '<p class="text-danger">Available Quantity: ' . $availableQuantity . '</p>';
                        } else {
                            echo 'Error fetching available quantity';
                        }

                        mysqli_stmt_close($checkQuantityStatement);
                    }
                }
                ?>
            </div>
            <div class="form-group">
                <label for="materialNote">Note:</label>
                <input type="text" class="form-control" id="materialNote" name="materialNote" required>
            </div>
            <button type="submit" class="btn btn-success" name="addMat">Add Material</button>
        </form>

        <script>
        document.getElementById('materialDropdown').value = '<?php echo isset($_POST['materialDropdown']) ? $_POST['materialDropdown'] : ''; ?>';
        </script>


    </div>



    

</body>
</html>