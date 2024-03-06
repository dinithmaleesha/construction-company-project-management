<!-- Allocate Employee to the PROJECT -->

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

    $empQuery = "SELECT DISTINCT `job_title` FROM `employee`
                WHERE `emp_status` = 'Active' AND `emp_availability` = 'Yes'";
    $empResult = mysqli_query($conn, $empQuery);

    $employee = [];
    while ($row = $empResult->fetch_assoc()) {
    $employee[] = $row;
    }

} else {
    header("location: home.php?id=<?php echo $user_id; ?>");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addEmp'])) {
        $job_title = isset($_POST['empDropdown']) ? $_POST['empDropdown'] : null;
        $qt = isset($_POST['empQuantity']) ? $_POST['empQuantity'] : null;
    
        if (!empty($job_title) && !empty($qt)) {
            $checkQuantityQuery = "SELECT COUNT(*) AS worker_count FROM `employee` WHERE `job_title` = ?";
            $checkQuantityStatement = mysqli_prepare($conn, $checkQuantityQuery);
            mysqli_stmt_bind_param($checkQuantityStatement, "s", $job_title);
            mysqli_stmt_execute($checkQuantityStatement);
            $checkQuantityResult = mysqli_stmt_get_result($checkQuantityStatement);
    
            if ($checkQuantityResult) {
                $row = mysqli_fetch_assoc($checkQuantityResult);
                $currentQuantity = $row['worker_count'];
    
                if ($qt > $currentQuantity) {
                    $errorMessage = "Entered quantity is greater than available quantity.";
                } else {
                    $getEmpIdQuery = "SELECT emp_id 
                         FROM `employee` 
                         WHERE `job_title` = ? 
                           AND `emp_status` = 'Active' 
                           AND `emp_availability` = 'Yes' 
                         ORDER BY RAND() 
                         LIMIT ?";
        
                    $getEmpIdStatement = mysqli_prepare($conn, $getEmpIdQuery);
                    mysqli_stmt_bind_param($getEmpIdStatement, "si", $job_title, $qt);
                    mysqli_stmt_execute($getEmpIdStatement);
    
                    $result = mysqli_stmt_get_result($getEmpIdStatement);
    
                    $empIds = array();
                    while ($row = mysqli_fetch_assoc($result)) {
                        $empIds[] = $row['emp_id'];
                    }
    
                    mysqli_stmt_close($getEmpIdStatement);
    
                    if (empty($empIds)) {
                        $errorMessage = "No available employees found for the specified criteria.";
                    } else {
    
                        $start_date = date("Y-m-d"); 
                        $end_date = NULL;
    
                        foreach ($empIds as $randomEmpId) {
                            $insertEmpAllocationQuery = "INSERT INTO `employee_allocation`(`project_id`, `employee_id`, `emp_allocation_start_date`, `emp_allocation_end_date`)
                                VALUES (?, ?, ?, ?)";
    
                            $insertEmpAllocationStatement = mysqli_prepare($conn, $insertEmpAllocationQuery);
                            mysqli_stmt_bind_param($insertEmpAllocationStatement, "ssss", $project_id, $randomEmpId, $start_date, $end_date);
                            $resultEmpAllocation = mysqli_stmt_execute($insertEmpAllocationStatement);
    
                        
                            if ($resultEmpAllocation) {
                                //$successMessage = "Employee Allocated successfully.";
                                $updateEmpAvailability = "UPDATE `employee` SET `emp_availability`='No' WHERE `emp_id`= $randomEmpId";
                                $resultupdateEmpAvailability = mysqli_query($conn, $updateEmpAvailability);
                                if(!$resultupdateEmpAvailability){
                                    $errorMessage = "Error allocating employee. Please try again.";
                                    break;
                                }
                            } else {
                                $errorMessage = "Error allocating employee. Please try again.";
                                break;
                            }
                        }
                        $successMessage = "Employee Allocated successfully.";
                    }
    
                }
            } else {
                echo "Error checking material quantity in the database.";
            }
        } else {
            $errorMessage = "Please fill out all the fields.";
        }
    }
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allocate Employee</title>
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
            Allocate Employee
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
        
        <form id="empForm" action="" method="post">
            <div class="form-group">
                <label for="empDropdown">Employee Type:</label>
                <select class="form-control" id="empDropdown" name="empDropdown" onchange="this.form.submit()">
                    <option value="">- Select Type -</option>
                    <?php
                    foreach ($employee as $emp) {
                        $selected = (isset($_POST['empDropdown']) && $_POST['empDropdown'] == $emp['job_title']) ? 'selected' : '';
                        echo '<option value="' . $emp['job_title'] . '" ' . $selected . '>' . $emp['job_title'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $jobTitle = isset($_POST['empDropdown']) ? $_POST['empDropdown'] : '';

                if (!empty($jobTitle)) {
                    $checkQuantityQuery = "SELECT COUNT(*) AS worker_count FROM `employee` WHERE `job_title` = ?";
                    $checkQuantityStatement = mysqli_prepare($conn, $checkQuantityQuery);
                    mysqli_stmt_bind_param($checkQuantityStatement, "s", $jobTitle);
                    mysqli_stmt_execute($checkQuantityStatement);
                    $checkQuantityResult = mysqli_stmt_get_result($checkQuantityStatement);

                    if ($checkQuantityResult) {
                        $row = mysqli_fetch_assoc($checkQuantityResult);
                        $availableQuantity = $row['worker_count'];

                        echo '<p class="text-danger">Available Quantity: ' . $availableQuantity . '</p>';
                    } else {
                        echo 'Error fetching available quantity';
                    }

                    mysqli_stmt_close($checkQuantityStatement);
                }
            }
            ?>

            <div class="form-group">
                <label for="empQuantity">Quantity:</label>
                <input type="number" class="form-control" id="empQuantity" name="empQuantity" min="1" required>
            </div>

            <button type="submit" class="btn btn-success" name="addEmp">Add</button>
        </form>


        <script>
        document.getElementById('empDropdown').value = '<?php echo isset($_POST['empDropdown']) ? $_POST['empDropdown'] : ''; ?>';
        </script>


    </div>



    

</body>
</html>