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
    } else {
        header("location: home.php?id=<?php echo $user_id; ?>");
        exit();
    }

} else {
    header("location: home.php?id=<?php echo $user_id; ?>");
    exit();
}







?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $projectDetails['project_name']; ?></title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
    </style>
</head>
<body>
    <!-- header -->
    <?php include "gm-header.php"; ?>

    <div class="container mt-4">
            <a href="add-plan.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>" class="btn btn-primary mr-1">
                <i class="fas fa-plus"></i> Add Plan
            </a>
            <a href="allocate-material.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>" id="addMaterialBtn" class="btn btn-primary mr-1">
                <i class="fas fa-plus"></i> Add Material
            </a>
            <a href="allocate-employee.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>" id="addEmpBtn" class="btn btn-primary mr-1">
                <i class="fas fa-plus"></i> Add Employee
            </a>
            <a href="view-plan.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>" class="btn btn-warning mr-1">
                <i class="fas fa-eye"></i> View Plan
            </a>
            <!-- <a href="#" class="btn btn-warning mr-1">
                <i class="fas fa-pen"></i> View Material
            </a> -->
            <a href="view-material-in-project.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>" class="btn btn-warning mr-1">
                <i class="fas fa-eye"></i> View Material
            </a>
            <a href="view-employee-in-project.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>" class="btn btn-warning mr-1">
                <i class="fas fa-eye"></i> View Employee
            </a>
            <a href="#" class="btn btn-warning mr-1" data-toggle="modal" data-target="#clientFeedbackModal">
                <i class="far fa-comment-dots"></i> View Client FeedBack
            </a>
            <!-- <a href="all-project.php?id=<?php echo $user_id; ?>" class="btn btn-warning mr-1">
                <i class="fas fa-pen"></i> Update Project
            </a> -->

        </div>
    <div class="container mt-3">
        <!-- <div class="float-right">
            <a href="#" class="btn btn-primary mr-3">
                <i class="fas fa-plus"></i> Add Plan
            </a>
            <a href="#" id="addMaterialBtn" class="btn btn-primary mr-3">
                <i class="fas fa-plus"></i> Add Material
            </a>
            <a href="#" id="addEmpBtn" class="btn btn-primary mr-3">
                <i class="fas fa-plus"></i> Add Employee
            </a>
            <a href="all-project.php?id=<?php echo $user_id; ?>" class="btn btn-warning mr-3">
                <i class="fas fa-pen"></i> Update Project
            </a>

        </div> -->
        
        <!-- Display project details -->
        <h2><?php echo $projectDetails['project_name']; ?></h2>
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
        
    </div>
    <div class="container mt-4">
        <hr>
    </div>

    <div class="container mt-4">
        <h2>Project Plan</h2>
    </div>

    
    <div class="container mt-4">
        <hr>
    </div>

    <div class="container mt-4"> 
    <?php
            $selectPlanName = "SELECT p.plan_name, p.plan_description
                                FROM project pr
                                INNER JOIN plan p ON pr.plan_id = p.plan_id
                                WHERE pr.project_id = $project_id";

            $selectPlanNameQuery = mysqli_query($conn, $selectPlanName);

            if ($selectPlanNameQuery) {
                if (mysqli_num_rows($selectPlanNameQuery) > 0) {
                    $planData = mysqli_fetch_assoc($selectPlanNameQuery);
            
                    $planName = $planData['plan_name'];
                    $planDescription = $planData['plan_description'];
            
                    // Display Plan Name from Database
                    echo '<div class="mt-3">';
                    echo '<h4>Project Name: ' . $planName . '</h4>';
                    echo '<p>Description: ' . $planDescription . '</p>';
                    echo '</div>';
                    echo '<div class="mb-5">';
                    echo '</div>';
                } else {
                    echo '<div class="mt-3"><h4>No plan found for this project.</h4></div>';
                }
            } else {
                echo '<div class="mt-3"><h4>Loading...</h4></div>';
            }
            
            ?>

            <?php
                $checkPlanQuery = "SELECT `plan_id` FROM `project` WHERE `project_id` = $project_id";
                $checkPlanResult = mysqli_query($conn, $checkPlanQuery);

                if ($checkPlanResult) {
                    $row = mysqli_fetch_assoc($checkPlanResult);
                    if ($row && isset($row['plan_id'])) {
                        $planId = $row['plan_id'];

                        $selectPlanMainTask = "SELECT mt.task_id, mt.task_name, mt.task_description, pt.task_status
                                                FROM main_task mt
                                                JOIN plan_task pt ON mt.task_id = pt.task_id
                                                WHERE pt.plan_id = $planId;";

                        $selectPlanMainTaskQuery = mysqli_query($conn, $selectPlanMainTask);
                        $taskCounter = 1;

                        if ($selectPlanMainTaskQuery) {
                            while ($taskData = mysqli_fetch_assoc($selectPlanMainTaskQuery)) {
                                $taskId = $taskData['task_id'];
                                $taskName = $taskData['task_name'];
                                $taskDesc = $taskData['task_description'];
                                $taskStatus = $taskData['task_status'];
    
                                echo '<div class="mt-5">';
                                echo '<div class="d-flex justify-content-between align-items-start">';
                                echo '    <div>';
                                echo '        <h5>' . $taskCounter . '. Main Task: ' . $taskName . '</h5>';
                                echo '        <p>' . $taskDesc . '</p>';
                                echo '    </div>';
                                echo '    <div>';
                                echo '      <a href="view-image.php?id=' . $user_id . '&project-id=' . $project_id . '&task-id=' . $taskId . '" class="btn btn-success view-image-btn">';
                                echo '          <i class="fas fa-eye"></i> View Image';
                                echo '      </a>';
                                echo '    </div>';
                                echo '</div>';
                                echo '<table class="table">';
                                echo '<thead>';
                                echo '<tr>';
                                echo '<th style="width: 2%;">#</th>';
                                echo '<th style="width: 30%;">Sub Task Name</th>';
                                echo '<th style="width: 40%;">Sub Task Description</th>';
                                echo '<th style="width: 15%;">Sub Task Status</th>';
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';
    
                                $selectSubtasksQuery = "SELECT st.subtask_name, st.sub_description, pst.sub_task_status
                                                        FROM sub_task st
                                                        JOIN plan_sub_task pst ON st.subtask_id = pst.subtask_id
                                                        WHERE pst.plan_id = $planId AND st.task_id = {$taskData['task_id']}";
    
                                $selectSubtasksResult = mysqli_query($conn, $selectSubtasksQuery);
                                $subTaskCounter = 1;
                                while ($subtaskData = mysqli_fetch_assoc($selectSubtasksResult)) {
                                    $subtaskStatus = $subtaskData['sub_task_status'];
                                    $color = '';
    
                                    if ($subtaskStatus === 'Completed') {
                                        $color = 'green';
                                    } elseif ($subtaskStatus === 'Not_Yet') {
                                        $color = 'red';
                                    } else {
                                        $color = 'orange';
                                    }
                                    echo '<tr>';
                                    echo '<td>' . $subTaskCounter . '</td>';
                                    echo '<td>' . $subtaskData['subtask_name'] . '</td>';
                                    echo '<td>' . $subtaskData['sub_description'] . '</td>';
                                    echo '<td style="color: ' . $color . ';">' . $subtaskStatus . '</td>';
                                    echo '</tr>';
                                    $subTaskCounter++;
                                }
    
                                echo '</tbody>';
                                echo '</table>';
                                echo '<hr>';
                                echo '</div>';
    
                                $taskCounter++;
                            }
            } else {
                // echo '<div class="mt-3"><h4>Loading...</h4></div>';
            }
        } else {
            // echo '<div class="mt-3"><h4>No plan found for this project.</h4></div>';
        }
    } else {
        // Handle error
    }
    ?>


    </div>


    <!-- Modal for displaying client feedback -->
    <div class="modal fade" id="clientFeedbackModal" tabindex="-1" role="dialog" aria-labelledby="clientFeedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientFeedbackModalLabel">Client Feedback</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $getClientIdQuery = "SELECT `client_id` FROM `project` WHERE `project_id` = $project_id";
                    $clientResult = mysqli_query($conn, $getClientIdQuery);
                    
                    if ($clientResult && mysqli_num_rows($clientResult) > 0) {
                        $clientRow = mysqli_fetch_assoc($clientResult);
                        $client_id = $clientRow['client_id'];

                        $getFeedbackQuery = "SELECT `feedback_id`, `feedback_comment`, `feedback_date` FROM `feedback` WHERE `user_id` = $client_id";
                        $feedbackResult = mysqli_query($conn, $getFeedbackQuery);

                        if ($feedbackResult && mysqli_num_rows($feedbackResult) > 0) {
                            while ($feedbackRow = mysqli_fetch_assoc($feedbackResult)) {
                                echo '<p><strong>Comment:</strong> ' . $feedbackRow['feedback_comment'] . '<br>';
                                echo '<strong>Date:</strong> ' . $feedbackRow['feedback_date'] . '</p>';
                                echo '<hr>';
                            }
                        } else {
                            echo '<p>No feedback available for this client.</p>';
                        }
                    } else {
                        echo '<p>Error retrieving client data.</p>';
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    

</body>
</html>
