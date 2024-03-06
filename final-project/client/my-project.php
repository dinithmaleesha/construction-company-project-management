<?php
session_start();
if (!isset($_SESSION['client']) || $_SESSION['client'] !== true) {
    header("Location: ../php/access-denied.php");
    exit();
}
@include '../php/config.php';
if (isset($_GET['id']) || isset($_GET['pid'])) {
    $user_id = $_GET['id'];
    $project_id = $_GET['pid'];

    // get project id
    $selectProjectId = "SELECT `project_id` FROM `project` WHERE `pm_id` = $user_id";
    $resultselectProjectId = mysqli_query($conn, $selectProjectId);

    if ($resultselectProjectId) {
        $rowProjectId = mysqli_fetch_assoc($resultselectProjectId);

        if ($rowProjectId) {
            $projectId = $rowProjectId['project_id'];

        } else {
            echo '<script>console.error("No project found for the given user ID.");</script>';
        }
    } else {
        echo '<script>console.error("Error executing the query: ' . mysqli_error($conn) . '");</script>';
    }

} else {
    header("location: ../php/access-denied.php");
    exit();
}


if (isset($_POST['sendFeedback'])) {
    $feedbackMessage = mysqli_real_escape_string($conn, $_POST["feedbackMessage"]);
    $currentDate = date("Y-m-d");

    $sendFeedbackQuery = "INSERT INTO `feedback`(`feedback_comment`, `feedback_date`, `user_id`)
    VALUES ('$feedbackMessage','$currentDate','$user_id')";

    if (mysqli_query($conn, $sendFeedbackQuery)) {
        echo '<script>showFloatingAlert("Success! Feedback Added", "success");</script>';
    } else {
        echo '<script>showFloatingAlert("Error! Something went wrong.", "danger");</script>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Project</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .project-details {
            display: grid;
            grid-template-columns: 150px auto;
            gap: 10px;
        }

        .project-details p {
            margin: 0;
        }

        .project-details p:nth-child(odd) {
            font-weight: bold;
        }
        .floating-alert {
            position: fixed;
            bottom: 10px;
            right: 10px;
            display: none;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <!-- header -->
    <?php include "client-header.php"; ?>
    
    <?php
    if (isset($successMessage)) {
        echo '<div class="alert alert-success">' . $successMessage . '</div>';
    } elseif (isset($errorMessage)) {
        echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
    }
    ?>

    <div id="floatingAlert" class="floating-alert"></div>

    <div class="container mt-3">
        <!-- Display project details -->
        <?php
        $query = "SELECT 
                    p.project_id, 
                    p.project_name, 
                    p.description, 
                    p.start_date, 
                    p.end_date, 
                    p.project_status, 
                    p.client_id, 
                    CONCAT(u_client.fname, ' ', u_client.lname) AS client_fullname,
                    CONCAT(u_pm.fname, ' ', u_pm.lname) AS pm_fullname,
                    p.pm_id, 
                    p.plan_id
                FROM 
                    project p
                JOIN 
                    user u_client ON p.client_id = u_client.user_id
                JOIN 
                    user u_pm ON p.pm_id = u_pm.user_id
                WHERE 
                    p.project_id = $project_id";

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $projectDetails = mysqli_fetch_assoc($result);
            // echo '<h2>This is your project</h2>';
            ?>
            <div class="mt-4">
                <a href="view-plan.php?id=<?php echo $user_id; ?>&pid=<?php echo $project_id; ?>" class="btn btn-primary mr-1">
                    <i class="fas fa-eye"></i> View Plan
                </a>
                <a href="#" class="btn btn-primary mr-1" data-toggle="modal" data-target="#feedbackModal">
                    <i class="fas fa-comment"></i> Send Feedback
                </a>
                <!-- <a href="request-material.php?id=<?php echo $user_id; ?>&project-id=<?php echo $projectId; ?>" class="btn btn-primary mr-1">
                    <i class="far fa-envelope"></i> View Responses
                </a> -->
                <!-- <a href="add-plan.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>" class="btn btn-warning mr-1">
                    <i class="fas fa-clipboard-list"></i> Add Plan
                </a>
                <a href="allocate-material.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>" id="addMaterialBtn" class="btn btn-primary mr-1">
                    <i class="fas fa-truck-loading"></i> Add Material
                </a>
                <a href="allocate-employee.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>" id="addEmpBtn" class="btn btn-primary mr-1">
                    <i class="fas fa-shopping-cart"></i> Add Employee
                </a> -->
                
                <!-- <a href="all-project.php?id=<?php echo $user_id; ?>" class="btn btn-warning mr-1">
                    <i class="fas fa-pen"></i> Update Project
                </a> -->
            </div>
            <h2 class="mt-4"><?php echo $projectDetails['project_name']; ?></h2>
            <div class="project-details">
                <p>Description:</p>
                <p><?php echo $projectDetails['description']; ?></p>

                <p>Start Date:</p>
                <p><?php echo $projectDetails['start_date']; ?></p>

                <p>End Date:</p>
                <p><?php echo $projectDetails['end_date']; ?></p>

                <p>Client Name:</p>
                <p><?php echo $projectDetails['client_fullname']; ?></p>

                <p>Project Manager:</p>
                <p><?php echo $projectDetails['pm_fullname']; ?></p>

                <p>Status:</p>
                <p><?php echo $projectDetails['project_status']; ?></p>
            </div>
        <?php
        } else {
            ?>
            <!-- <h2>You are not allocated to the Project</h2> -->
        <?php
        }
        ?>
    </div>
    <hr>

    <div class="container mt-4" style="min-height: 300px;">
        <?php
        $selectEmployeeData = "SELECT 
                                    e.`job_title`, 
                                    COUNT(e.`emp_id`) AS employee_count
                                FROM 
                                    `employee_allocation` ea
                                JOIN 
                                    `employee` e ON ea.`employee_id` = e.`emp_id`
                                JOIN 
                                    `project` p ON ea.`project_id` = p.`project_id`
                                WHERE 
                                    p.`client_id` = $user_id
                                    AND ea.`emp_allocation_end_date` IS NULL
                                GROUP BY 
                                    e.`job_title`";
        $selectEmployeeDataQuery = mysqli_query($conn, $selectEmployeeData);

        if ($selectEmployeeDataQuery && mysqli_num_rows($selectEmployeeDataQuery) > 0) {
            echo '<h2 class="mt-4">Currently Allocated Employees</h2>';
            echo '<h2 class="mt-4"></h2>';
            echo '<ul>';
            while ($row = mysqli_fetch_assoc($selectEmployeeDataQuery)) {
                echo '<li>';
                echo '<strong>' . $row['job_title'] . '</strong> - ' . $row['employee_count'] . ' employee(s)';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<div class="mt-4"><h2>No employees are currently allocated to this projects.</h2></div>';
        }
        ?>
    </div>



<hr>

    <div class="container mt-4" style="min-height: 300px;">
        <?php
        $selectMaterialData = "SELECT 
                                    m.`mat_name`, 
                                    m.`mat_unit`, 
                                    pma.`material_allocation_quantity`
                                FROM 
                                    `material` m
                                JOIN 
                                    `project_material_allocation` pma ON m.`mat_id` = pma.`mat_id`
                                JOIN 
                                    `project` p ON pma.`project_id` = p.`project_id`
                                WHERE 
                                    p.`client_id` = $user_id
                                    AND pma.`allocation_status` = 'allocate';
                                ";
        $selectMaterialDataQuery = mysqli_query($conn, $selectMaterialData);

        if ($selectMaterialDataQuery && mysqli_num_rows($selectMaterialDataQuery) > 0) {
            echo '<h2 class="mt-4">Currently Allocated Materials</h2>';
            echo '<h2 class="mt-4"></h2>';
            echo '<ul>';
            while ($row = mysqli_fetch_assoc($selectMaterialDataQuery)) {
                echo '<li>';
                echo '<strong>' . $row['mat_name'] . '</strong> - ' . $row['material_allocation_quantity'] . ' ' . $row['mat_unit'];
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<div class="mt-4"><h2>No Materials are currently allocated to this project.</h2></div>';
        }
        ?>
    </div>


    <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Send Feedback</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Feedback Form -->
                    <form method="post">
                        <div class="form-group">
                            <label for="feedbackMessage">Feedback Message:</label>
                            <textarea class="form-control" id="feedbackMessage" name="feedbackMessage" rows="3" required></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary" name="sendFeedback">Submit Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function showFloatingAlert(message, type) {
            var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                            message +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span>' +
                            '</button>' +
                            '</div>';

            $('#floatingAlert').html(alertHtml).fadeIn();

            setTimeout(function () {
                $('#floatingAlert').fadeOut();
            }, 5000); // Adjust the timeout (in milliseconds) based on your preference
        }
    </script>

</body>

</html>
