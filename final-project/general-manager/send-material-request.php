<!-- Allocate Employee to the PROJECT -->

<?php
session_start();
if (!isset($_SESSION['gm']) || $_SESSION['gm'] !== true) {
    header("Location: ../php/access-denied.php");
    exit();
}
@include '../php/config.php';
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $matQuery = "SELECT `mat_id`, `mat_name`, `mat_type`, `mat_quantity`, `mat_unit` FROM `material`";
    $materialResult = mysqli_query($conn, $matQuery);

    $material = [];
    while ($row = $materialResult->fetch_assoc()) {
        $material[] = $row;
    }
} else {
    header("location: ../php/access-denied.php");
    exit();
}

if (isset($_POST['request'])) {
    $dropdown = $_POST['empDropdown'];
    $quantity = $_POST['empQuantity'];
    $status = 'Pending';

    if(strtolower($dropdown) === "other"){
        $role = $_POST['otherEmp'];
    } else {
        $role = $dropdown;
    }

    $sql = "INSERT INTO `employee_request`(`employee_role`, `emp_count`, `user_request_status`,  `user_id`)
            VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $role, $quantity, $status, $user_id);

    if ($stmt->execute()) {
        $successMessage = "Request submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_POST['reqMat'])) {
    $dropdown = $_POST['materialDropdown'];
    $quantity = $_POST['materialQuantity'];
    $otherName = null;
    $status = 'Pending';

    if (strtolower($dropdown) === "other") {
        $matName = "Other";
        $otherName = $_POST['otherMaterial'];
        
        // Check if otherName is empty
        if ($otherName === "") {
            $errorMessage = "Enter All Fields";
        } else {
            $sql = "INSERT INTO `material_request`(`mat_req_name`, `other_name`, `mat_count`, `user_mat_request_status`, `user_id`)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisi", $matName, $otherName, $quantity, $status, $user_id);

            if ($stmt->execute()) {
                $successMessage = "Material request submitted successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        $matName = $dropdown;
        
        $sql = "INSERT INTO `material_request`(`mat_req_name`, `other_name`, `mat_count`, `user_mat_request_status`, `user_id`)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", $matName, $otherName, $quantity, $status, $user_id);

        if ($stmt->execute()) {
            $successMessage = "Material request submitted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Request | Employee</title>
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
            <a href="home.php?id=<?php echo $user_id; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            Send Material Request to Resource Manager
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
            $(document).ready(function () {
                setTimeout(function () {
                    $("#alertContainer").fadeOut(1000);
                }, 5000);
            });
        </script>
        <div class="mt-4">
        <form id="materialForm" action="" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="materialDropdown">Material Name:</label>
                    <select class="form-control" id="materialDropdown" name="materialDropdown" >
                        <option value="">- Select Material -</option>
                        <?php
                        foreach ($material as $mat) {
                            echo '<option value="' . $mat['mat_name'] . '">' . $mat['mat_name'] . '</option>';
                        }
                        ?>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="materialQuantity">Quantity:</label>
                    <input type="number" class="form-control" id="materialQuantity" name="materialQuantity" min="1" max="<?php echo $availableQuantity; ?>" required>
                </div>
            </div>
            <div class="form-group" id="otherMatContainer" style="display: none;">
                <label for="otherMaterial">Other:</label>
                <input type="text" class="form-control" id="otherMaterial" name="otherMaterial">
            </div>
            <button type="submit" class="btn btn-success" name="reqMat">Request Material</button>
        </form>

        <script>
            $(document).ready(function () {
                $('#materialDropdown').change(function () {
                    if ($(this).val() == 'other') {
                        $('#otherMatContainer').show();
                    } else {
                        $('#otherMatContainer').hide();
                    }
                });
            });
        </script>

        <script>
        document.getElementById('materialDropdown').value = '<?php echo isset($_POST['materialDropdown']) ? $_POST['materialDropdown'] : ''; ?>';
        </script>
        </div>
        

        

    </div>

</body>
</html>