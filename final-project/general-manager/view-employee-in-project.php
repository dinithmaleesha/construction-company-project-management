<!-- View And update material in Project -->

<?php
@include '../php/config.php';

session_start();
if (!isset($_SESSION['gm']) || $_SESSION['gm'] !== true) {
    header("Location: ../php/access-denied.php");
    exit();
}
if (isset($_GET['id']) && isset($_GET['project-id'])) {
    $user_id = $_GET['id'];
    $project_id = $_GET['project-id'];

    $selectProject = "SELECT * FROM `project` WHERE `project_id` = $project_id";
    $selectProjectResult = mysqli_query($conn, $selectProject);

    if ($selectProjectResult) {
        $row = mysqli_fetch_assoc($selectProjectResult);
        $projectName = $row['project_name'];
    } else {
        $projectName = "";
    }
} else {
    header("location: ../php/access-denied.php");
    exit();
}


// Check if the update form is submitted
if (isset($_POST['deallocateEmp'])) {
    mysqli_autocommit($conn, false);

    $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
    $emp_allocation_id = mysqli_real_escape_string($conn, $_POST['emp_allocation_id']);

    $emp_availability = "Yes";
    $deallocatedDate = date("Y-m-d");

    $insetMatUpdationQuery = "UPDATE `employee_allocation`
                            SET `emp_allocation_end_date` = '$deallocatedDate'
                            WHERE `employee_allocation_id` = $emp_allocation_id";
    $insetMatUpdationResult = mysqli_query($conn, $insetMatUpdationQuery);

    if($insetMatUpdationResult){
        $updateEmpAvailability = "UPDATE `employee` SET `emp_availability`='Yes' WHERE `emp_id`= $emp_id";
        $updateEmpAvailabilityResult = mysqli_query($conn, $updateEmpAvailability);

        if($updateEmpAvailabilityResult) {
            mysqli_commit($conn);
            $successMessage = "Employee Deallocated successfully!";
        } else {
            mysqli_rollback($conn);
            $errorMessage = "Error Deallocate Employee. Please try again.";
        }
    } else {
        mysqli_rollback($conn);
        $errorMessage = "Error Deallocate Employee. Please try again.";
    }

    mysqli_autocommit($conn, true);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employee | <?php echo $projectName; ?></title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <?php include 'gm-header.php'; ?>
    <div class="container mt-5">
        <h2 class="mt-4">
            <a href="project.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            View Employee | <?php echo $projectName; ?>
        </h2>

        <?php
        if (isset($successMessage)) {
            echo '<div class="alert alert-success">' . $successMessage . '</div>';
        } elseif (isset($errorMessage)) {
            echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
        }
        ?>

        <?php
        $selectQuery = "SELECT e.`job_title`, COUNT(ea.`employee_allocation_id`) AS total_allocations
                        FROM `employee_allocation` ea
                        JOIN `employee` e ON ea.`employee_id` = e.`emp_id`
                        WHERE ea.`project_id` = $project_id
                            AND ea.`emp_allocation_end_date` IS NULL
                        GROUP BY e.`job_title`";
        $result = mysqli_query($conn, $selectQuery);

        if (mysqli_num_rows($result) > 0) {
            echo '<h4 class="mt-4">
                Employee Count Table
            </h4>';
            echo '<table class="table mt-2">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                        <td>' . $row['job_title'] . '</td>
                        <td>' . $row['total_allocations'] . '</td>
                    </tr>';
            }

            echo '</tbody>
                  </table>';
        } else {
            echo '<p>No users found.</p>';
        }
        ?>
    </div>
    <hr>
    <div class="container mt-5">
        <h4 class="mt-4">All Employee Details</h4>
        
        <form action="" method="post" class="mb-3">
            <div class="form-group">
                <label for="searchName">Search by Name:</label>
                <input type="text" class="form-control" id="searchName" name="searchName" placeholder="Enter employee name">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
            
            <a href="view-employee-in-project.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>" class="btn btn-secondary ml-2">Reset</a>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchName'])) {
            $searchName = mysqli_real_escape_string($conn, $_POST['searchName']);
            $selectEmpQuery = "SELECT 
                                ea.`employee_allocation_id`, 
                                ea.`employee_id`,
                                CONCAT(e.`emp_fname`, ' ', e.`emp_lname`) AS full_name, 
                                e.`job_title`, 
                                ea.`emp_allocation_start_date`
                            FROM 
                                `employee_allocation` ea
                            JOIN 
                                `employee` e ON ea.`employee_id` = e.`emp_id`
                            WHERE 
                                ea.`project_id` = $project_id
                                AND CONCAT(e.`emp_fname`, ' ', e.`emp_lname`)  LIKE '%$searchName%'
                                AND ea.`emp_allocation_end_date` IS NULL";
        } else {
            $selectEmpQuery = "SELECT 
                                ea.`employee_allocation_id`, 
                                ea.`employee_id`,
                                CONCAT(e.`emp_fname`, ' ', e.`emp_lname`) AS full_name, 
                                e.`job_title`, 
                                ea.`emp_allocation_start_date`
                            FROM 
                                `employee_allocation` ea
                            JOIN 
                                `employee` e ON ea.`employee_id` = e.`emp_id`
                            WHERE 
                                ea.`project_id` = $project_id  AND ea.`emp_allocation_end_date` IS NULL";
        }

        $result = mysqli_query($conn, $selectEmpQuery);

        if (mysqli_num_rows($result) > 0) {
            
            
            echo '<table class="table mt-2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Job Title</th>
                            <th>Allocated Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                        <td>' . $row['employee_allocation_id'] . '</td>
                        <td>' . $row['full_name'] . '</td>
                        <td>' . $row['job_title'] . '</td>
                        <td>' . $row['emp_allocation_start_date'] . '</td>
                        <td>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#openModal' . $row['employee_id'] . '">
                                <i class="fas fa-trash"></i> Deallocate
                            </button>
                        </td>
                    </tr>';

                echo '<div class="modal fade" id="openModal' . $row['employee_id'] . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel' . $row['employee_id'] . '" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel' . $row['employee_id'] . '">Deallocate Employee</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <p>Are you sure you want to deallocate employee: ' . $row['full_name'] . '?</p>
                                    <input type="hidden" name="emp_allocation_id" value="' . $row['employee_allocation_id'] . '">
                                    <input type="hidden" name="emp_id" value="' . $row['employee_id'] . '">

                                    <button type="submit" class="btn btn-danger" name="deallocateEmp">Yes</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
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

    <div class="container mt-5"></div>


    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
