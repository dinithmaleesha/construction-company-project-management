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

    $empQuery = "SELECT DISTINCT `job_title` FROM `employee`
                WHERE `emp_status` = 'Active' AND `emp_availability` = 'Yes'";
    $empResult = mysqli_query($conn, $empQuery);

    $employee = [];
    while ($row = $empResult->fetch_assoc()) {
    $employee[] = $row;
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
            Send Employee Request to Resource Manager
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
        <form id="empForm" action="" method="post">
            <div class="form-group">
                <label for="empDropdown">Employee Type:</label>
                <select class="form-control" id="empDropdown" name="empDropdown">
                    <option value="">- Select Type -</option>
                    <?php
                    foreach ($employee as $emp) {
                        echo '<option value="' . $emp['job_title'] . '">' . $emp['job_title'] . '</option>';
                    }
                    ?>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group" id="otherEmpContainer" style="display: none;">
                <label for="Specify">Specify Other:</label>
                <input type="text" class="form-control" id="otherEmp" name="otherEmp">
            </div>

            <div class="form-group">
                <label for="empQuantity">Quantity:</label>
                <input type="number" class="form-control" id="empQuantity" name="empQuantity" min="1" required>
            </div>

            <button type="submit" class="btn btn-success" name="request">Request</button>
        </form>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#empDropdown').change(function () {
                    if ($(this).val() == 'other') {
                        $('#otherEmpContainer').show();
                    } else {
                        $('#otherEmpContainer').hide();
                    }
                });
            });
        </script>
        </div>
        

        

    </div>

</body>
</html>