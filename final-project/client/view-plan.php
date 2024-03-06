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

} else {
    header("location: ../php/access-denied.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
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
    <?php include "client-header.php"; ?>
    
    <?php
    if (isset($successMessage)) {
        echo '<div class="alert alert-success">' . $successMessage . '</div>';
    } elseif (isset($errorMessage)) {
        echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
    }
    ?>




    <div class="container mt-4"> 
        <?php
        $selectProjectId = "SELECT `project_id`
                            FROM `project`
                            WHERE `client_id` = $user_id";
        $selectProjectIdQuery = mysqli_query($conn, $selectProjectId);

        if ($selectProjectIdQuery && mysqli_num_rows($selectProjectIdQuery) > 0) {
            $row = mysqli_fetch_assoc($selectProjectIdQuery);
            $project_id = $row['project_id'];

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
                    echo '<div class="container mt-5"></div>';
                    echo '<h2><a href="my-project.php?id=' . $user_id . '&pid=' . $project_id . '" class="btn btn-secondary"><i class="fas fa-arrow-left"></i></a> Project Plan</h2>';
                    echo '<div class="container mt-5"></div>';
                    echo '<div class="mt-3">';
                    echo '<h4>Project Name: ' . $planName . '</h4>';
                    echo '<p>Description: ' . $planDescription . '</p>';
                    echo '</div>';
                    echo '<div class="mb-5"></div>';
                } else {
                    echo '<div class="mt-3"><h4>No plan found for this project.</h4></div>';
                }
            } else {
                echo '<div class="mt-3"><h4>Loading...</h4></div>';
            }

            $checkPlanQuery = "SELECT `plan_id` FROM `project` WHERE `project_id` = $project_id";
            $checkPlanResult = mysqli_query($conn, $checkPlanQuery);

            if ($checkPlanResult) {
                $row = mysqli_fetch_assoc($checkPlanResult);
                if ($row && isset($row['plan_id'])) {
                    $planId = $row['plan_id'];

                    $selectPlanMainTask = "SELECT mt.task_id, mt.task_name, mt.task_description, pt.task_status
                                            FROM main_task mt
                                            JOIN plan_task pt ON mt.task_id = pt.task_id
                                            WHERE pt.plan_id = $planId";

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
                            echo '      <a href="view-image.php?id=' . $user_id . '&pid=' . $project_id . '&task-id=' . $taskId . '" class="btn btn-success view-image-btn">';
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
                        echo '<div class="mt-3"><h4>Loading...</h4></div>';
                    }
                } else {
                    echo '<div class="mt-3"><h4>No plan found for this project.</h4></div>';
                }
            } else {
                // Handle error
            }
        } else {
            echo '<div class="mt-4"><h2>You are not allocated to any projects.</h2></div>';
        }
        ?>
    </div>






    
</body>

</html>
