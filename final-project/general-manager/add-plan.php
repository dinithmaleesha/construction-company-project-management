

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

    // Check if the project has a plan
    $checkPlanQuery = "SELECT `plan_id` FROM `project` WHERE `project_id` = $project_id";
    $checkPlanResult = mysqli_query($conn, $checkPlanQuery);

    if ($checkPlanResult) {
        $row = mysqli_fetch_assoc($checkPlanResult);
        $hasPlan = !is_null($row['plan_id']);
    } else {
        $hasPlan = false;
    }
} else {
    header("location: ../php/access-denied.php");
    exit();
}

if (isset($_POST['addPlan'])) {
    $planName = mysqli_real_escape_string($conn, $_POST['planName']);
    $planDescription = mysqli_real_escape_string($conn, $_POST['planDescription']);

    $insertPlanQuery = "INSERT INTO `plan`(`plan_name`, `plan_description`) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $insertPlanQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $planName, $planDescription);
        $success = mysqli_stmt_execute($stmt);
        
        $planId = mysqli_insert_id($conn);
        
        mysqli_stmt_close($stmt);
    }

    if ($success) {
        // Update project table with the plan ID
        $updateProjectQuery = "UPDATE `project` SET `plan_id` = ? WHERE `project_id` = ?";
        $stmt = mysqli_prepare($conn, $updateProjectQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $planId, $project_id);
            $updateSuccess = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($updateSuccess) {
                header("Location: add-plan.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>");
                exit();
            } else {
                $errorMessage = "Error updating project table. Please try again.";
            }
        }
    } else {
        $errorMessage = "Error adding plan. Please try again.";
    }
}


if (isset($_POST['addTask'])) {
    $mainTask = $_POST['mainTask'];
    $mainTaskDesc = $_POST['mainTaskDescription'];
    $subTasks = $_POST['subtasks'];
    $subTaskDesc = $_POST['subTaskDescriptions'];

    echo '<script>';
    echo 'console.log(' . json_encode($mainTask) . ');';
    echo 'console.log(' . json_encode($mainTaskDesc) . ');';
    echo 'console.log("Sub Tasks:");';
    foreach ($subTasks as $subTask) {
        echo 'console.log(' . json_encode($subTask) . ');';
    }
    echo 'console.log("Sub Tasks Description:");';
    foreach ($subTaskDesc as $subTaskDes) {
        echo 'console.log(' . json_encode($subTaskDes) . ');';
    }
    echo '</script>';


    mysqli_begin_transaction($conn);

    try {

        $insertMainTaskQuery = "INSERT INTO `main_task`(`task_name`, `task_description`)
                                VALUES ('$mainTask','$mainTaskDesc')";
        $resultMainTask = mysqli_query($conn, $insertMainTaskQuery);
        $mainTaskId = mysqli_insert_id($conn);

        if ($resultMainTask) {
            // add Plan_task
            $getPlanId = "SELECT `plan_id` FROM `project` WHERE `project_id` = $project_id";
            $resultGetPlanId = mysqli_query($conn, $getPlanId);

            if ($resultGetPlanId) {
                $row = mysqli_fetch_assoc($resultGetPlanId);
                $planId = $row['plan_id'];
                $taskStatus = "Not_Yet";

                $insertPlanTask = "INSERT INTO `plan_task`(`plan_id`, `task_id`, `task_status`)
                                VALUES ('$planId','$mainTaskId','$taskStatus')";
                $resultPlanTask = mysqli_query($conn, $insertPlanTask);

                if ($resultPlanTask) {
                    $count = count($subTasks);

                    for ($i = 0; $i < $count; $i++) {
                        $currentSubTask = $subTasks[$i];            // name
                        $currentSubTaskDesc = $subTaskDesc[$i];     // description

                        $insertSubTaskQuery = "INSERT INTO `sub_task`(`subtask_name`, `sub_description`, `task_id`)
                                            VALUES ('$currentSubTask','$currentSubTaskDesc','$mainTaskId')";
                        $resultSubTask = mysqli_query($conn, $insertSubTaskQuery);

                        $subTaskId = mysqli_insert_id($conn);

                        if ($resultSubTask) {
                            $subTaskStatus = "Not_Yet";
                            $insertPlanSubTask = "INSERT INTO `plan_sub_task`(`plan_id`, `subtask_id`, `sub_task_status`)
                                                VALUES ('$planId','$subTaskId','$subTaskStatus')";
                            $resultinsertPlanSubTask = mysqli_query($conn, $insertPlanSubTask);

                            if (!$resultinsertPlanSubTask) {
                                throw new Exception("Error inserting plan_sub_task at index $i");
                            }
                        } else {
                            throw new Exception("Error inserting sub-task at index $i");
                        }
                    }

                    mysqli_commit($conn);
                    $successMessage = "Task Added successfully!";
                    header("Location: add-plan.php?id=<?php echo $user_id; ?>&project-id=<?php echo $project_id; ?>");
                    exit();
                } else {
                    throw new Exception("Error inserting plan_task");
                }
            } else {
                $planId = null;
            }
        } else {
            throw new Exception("Error inserting main_task");
        }
    } catch (Exception $e) {
        echo '<script>';
        echo 'console.error("Transaction failed: ' . $e->getMessage() . '");';
        echo '</script>';

        mysqli_rollback($conn);
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Plan | <?php echo $projectName; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
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
            Add Task | <?php echo $projectName; ?>
        </h2>

        <?php
        if (isset($successMessage)) {
            echo '<div class="alert alert-success">' . $successMessage . '</div>';
        } elseif (isset($errorMessage)) {
            echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
        }
        ?>
        <?php if (!$hasPlan): ?>
            <form action="" method="post">
                <label for="planName">Plan Name:</label>
                <input type="text" class="form-control" name="planName" placeholder="Enter Plan Name" required>

                <label for="planDescription">Plan Description:</label>
                <textarea class="form-control" name="planDescription" placeholder="Enter Plan Description" rows="4" required></textarea>

                <button type="submit" class="btn btn-success mt-3" name="addPlan">Add Plan</button>
            </form>
        <?php else: ?>
            <form action="" method="post">
                <div id="dynamicPlan">
                    <div class="form-group">
                        <div class="text-right">
                            <button type="button" class="btn btn-primary" onclick="addSubTask()">Add Sub Task</button>
                            <button type="button" class="btn btn-danger" onclick="removeSubTask()">Remove Added Sub Task</button>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="mainTaskDropdown">Main Task:</label>
                                <input type="text" class="form-control" name="mainTask" placeholder="Enter Main Task" required>
                            </div>

                            <div class="col-md-6">
                                <label for="mainTaskDescription">Description:</label>
                                <input type="text" class="form-control" name="mainTaskDescription" placeholder="Enter Main Task Description" required>
                            </div>
                        </div>

                        <div class="sub-tasks">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success" name="addTask">Add Task</button>
            </form>


            <hr>
            <!-- <div class="mt-3">
                <h3>Plan of the <?php echo $projectName; ?></h3>
            </div> -->

            <?php
            $selectPlanName = "SELECT p.plan_name, p.plan_description
                                FROM project pr
                                INNER JOIN plan p ON pr.plan_id = p.plan_id
                                WHERE pr.project_id = $project_id";

            $selectPlanNameQuery = mysqli_query($conn, $selectPlanName);

            if ($selectPlanNameQuery) {
                $planData = mysqli_fetch_assoc($selectPlanNameQuery);

                $planName = $planData['plan_name'];
                $planDescription = $planData['plan_description'];

                echo '<div class="mt-3">';
                echo '<h3>' . $planName . '</h3>';
                echo '<p>' . $planDescription . '</p>';
                echo '</div>';
                echo '<div class="mb-5">';
                echo '</div>';
            } else {
                echo '<div class="mt-3"><h4>Loading...</h4></div>';
            }
            ?>

            <?php
            $checkPlanQuery = "SELECT `plan_id` FROM `project` WHERE `project_id` = $project_id";
            $checkPlanResult = mysqli_query($conn, $checkPlanQuery);

            if ($checkPlanResult) {
                $row = mysqli_fetch_assoc($checkPlanResult);
                $planId = $row['plan_id'];

                $selectPlanMainTask = "SELECT mt.task_id, mt.task_name, mt.task_description, pt.task_status
                                        FROM main_task mt
                                        JOIN plan_task pt ON mt.task_id = pt.task_id
                                        WHERE pt.plan_id = $planId;";

                $selectPlanMainTaskQuery = mysqli_query($conn, $selectPlanMainTask);
                $taskCounter = 1;
                if ($selectPlanMainTaskQuery) {
                    while ($taskData = mysqli_fetch_assoc($selectPlanMainTaskQuery)) {
                        $taskName = $taskData['task_name'];
                        $taskDesc = $taskData['task_description'];
                        $taskStatus = $taskData['task_status'];

                        echo '<div class="mt-5">';
                        echo '<h5>' . $taskCounter . '. Main Task: ' . $taskName . '<h6> • ' . $taskDesc . '</h6></h5>';
                        echo '<table class="table">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Sub Task Name</th>';
                        echo '<th>Sub Task Description</th>';
                        echo '<th>Sub Task Status</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        $selectSubtasksQuery = "SELECT st.subtask_name, st.sub_description, pst.sub_task_status
                                                FROM sub_task st
                                                JOIN plan_sub_task pst ON st.subtask_id = pst.subtask_id
                                                WHERE pst.plan_id = $planId AND st.task_id = {$taskData['task_id']}";

                        $selectSubtasksResult = mysqli_query($conn, $selectSubtasksQuery);

                        while ($subtaskData = mysqli_fetch_assoc($selectSubtasksResult)) {
                            echo '<tr>';
                            echo '<td>' . $subtaskData['subtask_name'] . '</td>';
                            echo '<td>' . $subtaskData['sub_description'] . '</td>';
                            echo '<td>' . $subtaskData['sub_task_status'] . '</td>';
                            echo '</tr>';
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
                // Handle error
            }
            ?>



            

            
        <?php endif; ?>



        <script>
        document.addEventListener("DOMContentLoaded", function () {
            addSubTask();
        });

        function addSubTask() {
            var newSubTaskRow = document.createElement('div');
            newSubTaskRow.className = 'row sub-task-row';

            var subTaskInput = document.createElement('div');
            subTaskInput.className = 'col-md-6';
            subTaskInput.innerHTML = '<label for="subTaskDropdown">Sub Task:</label>' +
                '<input type="text" class="form-control" name="subtasks[]" placeholder="Enter Sub Task">';

            var subTaskDescriptionInput = document.createElement('div');
            subTaskDescriptionInput.className = 'col-md-6';
            subTaskDescriptionInput.innerHTML = '<label for="subTaskDescription">Description:</label>' +
                '<input type="text" class="form-control" name="subTaskDescriptions[]" placeholder="Enter Sub Task Description">';

            newSubTaskRow.appendChild(subTaskInput);
            newSubTaskRow.appendChild(subTaskDescriptionInput);

            document.querySelector('.sub-tasks').appendChild(newSubTaskRow);
        }

        function removeSubTask() {
            var subTasksDiv = document.querySelector('.sub-tasks');

            if (subTasksDiv.children.length > 1) {
                subTasksDiv.removeChild(subTasksDiv.lastChild);
            }
        }
    </script>


    </div>
</body>

</html>