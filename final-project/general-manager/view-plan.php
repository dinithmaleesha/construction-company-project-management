<!-- Add Plan to the PROJECT -->

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



if (isset($_POST['updatePlanNameForm'])) {
    $updatedPlanName = mysqli_real_escape_string($conn, $_POST['updatePlanName']);
    $updatedPlanDescription = mysqli_real_escape_string($conn, $_POST['updatePlanDescription']);
    $planId = mysqli_real_escape_string($conn, $_POST['planId']);

    $updatePlanQuery = "UPDATE `plan` SET `plan_name` = ?, `plan_description` = ? WHERE `plan_id` = ?";
    $stmt = mysqli_prepare($conn, $updatePlanQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $updatedPlanName, $updatedPlanDescription, $planId);
        $updateSuccess = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($updateSuccess) {
            header("Location: view-plan.php?id=$user_id&project-id=$project_id");
            exit();
        } else {
            $updateErrorMessage = "Error updating plan information. Please try again.";
        }
    }
}

if (isset($_POST['updateMainTask'])) {
    $updateMainTaskName = mysqli_real_escape_string($conn, $_POST['updateMainTaskName']);
    $updateMainTaskDescription = mysqli_real_escape_string($conn, $_POST['updateMainTaskDescription']);
    $mainTaskId = mysqli_real_escape_string($conn, $_POST['updateMainTaskId']);

    $updateQuery = "UPDATE `main_task` SET `task_name`= ?, `task_description`= ? WHERE `task_id`= ?";
    
    $stmt = mysqli_prepare($conn, $updateQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $updateMainTaskName, $updateMainTaskDescription, $mainTaskId);
        $updateSuccess = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($updateSuccess) {
            header("Location: view-plan.php?id=$user_id&project-id=$project_id");
            exit();
        } else {
            $updateErrorMessage = "Error updating plan information. Please try again.";
        }
    }
}


if (isset($_POST['updateSubTask'])) {
    $updateSubTaskId = mysqli_real_escape_string($conn, $_POST['updateSubTaskId']);
    $updateSubTaskName = mysqli_real_escape_string($conn, $_POST['updateSubTaskName']);
    $updateSubTaskDescription = mysqli_real_escape_string($conn, $_POST['updateSubTaskDescription']);
    $updateSubTaskStatus = mysqli_real_escape_string($conn, $_POST['updateSubTaskStatus']);

    $allowedStatuses = ['Not_Yet', 'In_Progress', 'Completed'];
    if (!in_array($updateSubTaskStatus, $allowedStatuses)) {
        $updateErrorMessage = "Invalid subtask status. Please choose a valid status.";
    } else {
        $updateSubTaskQuery = "UPDATE `sub_task` SET `subtask_name`=?, `sub_description`=? WHERE `subtask_id`=?";
        $stmt = mysqli_prepare($conn, $updateSubTaskQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssi", $updateSubTaskName, $updateSubTaskDescription, $updateSubTaskId);
            $updateSuccess = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($updateSuccess) {
                $updateSubTaskStatusQuery = "UPDATE `plan_sub_task` SET `sub_task_status`=? WHERE `subtask_id`=?";
                $stmt = mysqli_prepare($conn, $updateSubTaskStatusQuery);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "si", $updateSubTaskStatus, $updateSubTaskId);
                    $updateSuccess = mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    if ($updateSuccess) {
                        header("Location: view-plan.php?id=$user_id&project-id=$project_id");
                        exit();
                    } else {
                        $updateErrorMessage = "Error updating subtask status. Please try again.";
                    }
                }
            } else {
                $updateErrorMessage = "Error updating subtask information. Please try again.";
            }
        }
    }
}


if (isset($_POST['addNewSubTask'])) {
    // Retrieve form data
    $newSubTaskName = mysqli_real_escape_string($conn, $_POST['newSubTaskName']);
    $newSubTaskDescription = mysqli_real_escape_string($conn, $_POST['newSubTaskDescription']);
    $addNewSubTaskMainTaskId = mysqli_real_escape_string($conn, $_POST['addNewSubTaskMainTaskId']);
    $addNewSubTaskPlanId = mysqli_real_escape_string($conn, $_POST['addNewSubTaskPlanId']);

    // // Print data to console
    // echo '<script>';
    // echo 'console.log("New Subtask Name: ' . $newSubTaskName . '");';
    // echo 'console.log("New Subtask Description: ' . $newSubTaskDescription . '");';
    // echo 'console.log("Main Task ID: ' . $addNewSubTaskMainTaskId . '");';
    // echo 'console.log("Plan ID: ' . $addNewSubTaskPlanId . '");';
    // echo '</script>';

    // Insert new subtask into the database
    $insertSubTaskQuery = "INSERT INTO `sub_task` (`subtask_name`, `sub_description`, `task_id`) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insertSubTaskQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $newSubTaskName, $newSubTaskDescription, $addNewSubTaskMainTaskId);
        $insertSuccess = mysqli_stmt_execute($stmt);
        $lastInsertedId = mysqli_insert_id($conn); 
        mysqli_stmt_close($stmt);
    
        if ($insertSuccess) {
            $insertPlanSubTaskQuery = "INSERT INTO `plan_sub_task`(`subtask_id`, `sub_task_status`, `plan_id`)
                                       VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertPlanSubTaskQuery);
    
            if ($stmt) {
                $subTaskStatus = "Not_Yet";
                mysqli_stmt_bind_param($stmt, "iss", $lastInsertedId, $subTaskStatus, $addNewSubTaskPlanId);
                $insertPlanSubTaskSuccess = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
    
                if ($insertPlanSubTaskSuccess) {
                    header("Location: view-plan.php?id=$user_id&project-id=$project_id");
                    exit();
                } else {
                    $insertErrorMessage = "Error inserting into plan_sub_task table. Please try again.";
                }
            }
        } else {
            $insertErrorMessage = "Error inserting new subtask. Please try again.";
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
    <title>View Plan | <?php echo $projectName; ?></title>
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
            View Plan | <?php echo $projectName; ?>
        </h2>

        <?php
        if (isset($successMessage)) {
            echo '<div class="alert alert-success">' . $successMessage . '</div>';
        } elseif (isset($errorMessage)) {
            echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
        }
        ?>
        
        <?php
            $selectPlanName = "SELECT p.plan_name, p.plan_description, p.plan_id
                                FROM project pr
                                INNER JOIN plan p ON pr.plan_id = p.plan_id
                                WHERE pr.project_id = $project_id";

            $selectPlanNameQuery = mysqli_query($conn, $selectPlanName);

            if ($selectPlanNameQuery) {
                if(mysqli_num_rows($selectPlanNameQuery) > 0) {
                    $planData = mysqli_fetch_assoc($selectPlanNameQuery);

                    $planName = $planData['plan_name'];
                    $planDescription = $planData['plan_description'];

                    echo '<div class="mt-3 d-flex justify-content-between">';
                    echo '    <div>';
                    echo '        <h3 id="planName">' . $planName . '</h3>';
                    echo '        <p id="planDescription">' . $planDescription . '</p>';
                    echo '    </div>';
                    echo '    <div>';
                    echo '        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#updatePlanModal">';
                    echo '            <i class="fas fa-pen"></i> Edit Name';
                    echo '        </button>';
                    echo '    </div>';
                    echo '</div>';
                    echo '<div class="mb-5"></div>';
                } else {
                    
                }
            } else {
                
            }
        ?>


            <?php
            $checkPlanQuery = "SELECT `plan_id` FROM `project` WHERE `project_id` = $project_id";
            $checkPlanResult = mysqli_query($conn, $checkPlanQuery);
            
            if ($checkPlanResult && mysqli_num_rows($checkPlanResult) > 0) {
                $row = mysqli_fetch_assoc($checkPlanResult);
                $planId = $row['plan_id'];

                if ($planId !== null) {
                    $selectPlanMainTask = "SELECT mt.task_id, mt.task_name, mt.task_description, pt.task_status
                                        FROM main_task mt
                                        JOIN plan_task pt ON mt.task_id = pt.task_id
                                        WHERE pt.plan_id = $planId;";

                    $selectPlanMainTaskQuery = mysqli_query($conn, $selectPlanMainTask);
                    $taskCounter = 1;

                    if ($selectPlanMainTaskQuery) {
                        if (mysqli_num_rows($selectPlanMainTaskQuery) > 0) {
                            while ($taskData = mysqli_fetch_assoc($selectPlanMainTaskQuery)) {
                            $taskId = $taskData['task_id'];
                            $taskName = $taskData['task_name'];
                            $taskDesc = $taskData['task_description'];
                            $taskStatus = $taskData['task_status'];

                            echo '<div class="mt-5">';
                            echo '<div class="d-flex justify-content-between align-items-start">'; // Added a container for flex alignment
                            echo '    <div>';
                            echo '        <h5>' . $taskCounter . '. Main Task: ' . $taskName . '</h5>';
                            echo '        <p>' . $taskDesc . '</p>';
                            echo '    </div>';
                            echo '    <div>';
                            echo '  <button type="button" class="btn btn-success edit-main-task"
                                        data-toggle="modal" data-target="#updateMainTaskModal"
                                        data-task-id="' . $taskId . '"
                                        data-task-name="' . htmlspecialchars($taskName) . '"
                                        data-task-description="' . htmlspecialchars($taskDesc) . '">
                                        <i class="fas fa-pen"></i> Edit Task Name
                                    </button>';

                            echo '    </div>';
                            echo '</div>';
                            echo '<table class="table">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th style="width: 30%;">Sub Task Name</th>';
                            echo '<th style="width: 40%;">Sub Task Description</th>';
                            echo '<th style="width: 15%;">Sub Task Status</th>';
                            echo '<th style="width: 15%;">Action</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';


                            $selectSubtasksQuery = "SELECT st.subtask_id, st.subtask_name, st.sub_description, pst.sub_task_status
                                                    FROM sub_task st
                                                    JOIN plan_sub_task pst ON st.subtask_id = pst.subtask_id
                                                    WHERE pst.plan_id = $planId AND st.task_id = {$taskData['task_id']}";

                            $selectSubtasksResult = mysqli_query($conn, $selectSubtasksQuery);

                            while ($subtaskData = mysqli_fetch_assoc($selectSubtasksResult)) {
                                echo '<tr>';
                                echo '<td>' . $subtaskData['subtask_name'] . '</td>';
                                echo '<td>' . $subtaskData['sub_description'] . '</td>';
                                echo '<td>' . $subtaskData['sub_task_status'] . '</td>';
                                echo '<td class="text-right"> 
                                        <button type="button" class="btn btn-warning edit-sub-task" 
                                            data-toggle="modal" data-target="#updateSubTaskModal"
                                            data-subtask-id="' . htmlspecialchars($subtaskData['subtask_id']) . '"
                                            data-subtask-name="' . htmlspecialchars($subtaskData['subtask_name']) . '"
                                            data-subtask-description="' . htmlspecialchars($subtaskData['sub_description']) . '"
                                            data-subtask-status="' . $subtaskData['sub_task_status'] . '"
                                            onclick="setSubTaskId(' . $taskId . ')">
                                            <i class="fas fa-pen"></i> Edit Subtask
                                        </button> 
                                    </td>';

                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                            echo '<td class="text-right">';
                            echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNewSubTask" onclick="prepareAddNewSubTaskModal(' . $taskId . ', ' . $planId . ')">';
                            echo 'Add New Sub Task';
                            echo '</button>';
                            echo '</td>';

                            echo '<hr>';
                            echo '</div>';

                            $taskCounter++;
                        }
                    } else {
                        echo '<div class="mt-3"><h4>No main tasks found for this plan.</h4></div>';
                    }
                } else {
                    echo '<div class="mt-3"><h4>No plan found for this project.</h4></div>';
                }

                } else {
                    echo '<div class="mt-3"><h4>No plan found for this project.</h4></div>';
                    echo '<button type="button" class="btn btn-primary mt-4" onclick="location.href=\'add-plan.php?id=' . $user_id . '&project-id=' . $project_id . '\';">Add Plan</button></div>';
                }
            } else {
                echo '<div class="mt-3"><h4>Error checking plan for this project.</h4></div>';
            }
            ?>


    </div>


    <!-- Update Plan Modal -->
    <div class="modal fade" id="updatePlanModal" tabindex="-1" role="dialog" aria-labelledby="updatePlanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePlanModalLabel">Update Plan Name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Update Plan Form -->
                    <form id="updatePlanForm" action="" method="post">
                        <div class="form-group">
                            <label for="updatePlanName">Plan Name:</label>
                            <input type="text" class="form-control" id="updatePlanName" name="updatePlanName" value="<?php echo $planName; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="updatePlanDescription">Plan Description:</label>
                            <textarea class="form-control" id="updatePlanDescription" name="updatePlanDescription" rows="3" required><?php echo $planDescription; ?></textarea>
                        </div>
                        <input type="hidden" name="planId" value="<?php echo $planData['plan_id']; ?>">
                        <button type="submit" class="btn btn-primary" name="updatePlanNameForm">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Main Task Modal -->
    <div class="modal fade" id="updateMainTaskModal" tabindex="-1" role="dialog" aria-labelledby="updateMainTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateMainTaskModalLabel">Update Main Task Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Update Main Task Form -->
                    <form id="updateMainTaskForm" action="" method="post">
                        <div class="form-group">
                            <label for="updateMainTaskName">Main Task Name:</label>
                            <input type="text" class="form-control" id="updateMainTaskName" name="updateMainTaskName" value="<?php echo htmlspecialchars($currentTaskName); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="updateMainTaskDescription">Main Task Description:</label>
                            <textarea class="form-control" id="updateMainTaskDescription" name="updateMainTaskDescription" rows="3" required><?php echo htmlspecialchars($currentTaskDescription); ?></textarea>
                        </div>
                        <input type="hidden" name="updateMainTaskId" value="<?php echo htmlspecialchars($currentTaskId); ?>">
                        <button type="submit" class="btn btn-primary" name="updateMainTask">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Subtask Modal -->
    <div class="modal fade" id="updateSubTaskModal" tabindex="-1" role="dialog" aria-labelledby="updateSubTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateSubTaskModalLabel">Update Subtask Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Update Subtask Form -->
                    <form id="updateSubTaskForm" action="" method="post">
                        <div class="form-group">
                            <label for="updateSubTaskName">Subtask Name:</label>
                            <input type="text" class="form-control" id="updateSubTaskName" name="updateSubTaskName" required>
                        </div>
                        <div class="form-group">
                            <label for="updateSubTaskDescription">Subtask Description:</label>
                            <textarea class="form-control" id="updateSubTaskDescription" name="updateSubTaskDescription" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="updateSubTaskStatus">Subtask Status:</label>
                            <select class="form-control" id="updateSubTaskStatus" name="updateSubTaskStatus" required>
                                <option value="Not_Yet">Not Yet</option>
                                <option value="In_Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <input type="hidden" name="updateSubTaskId" value="">
                        <button type="submit" class="btn btn-primary" name="updateSubTask">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Add New Subtask Modal -->
    <div class="modal fade" id="addNewSubTask" tabindex="-1" role="dialog" aria-labelledby="addNewSubTaskLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewSubTaskLabel">Add New Subtask</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add New Subtask Form -->
                    <form id="addNewSubTaskForm" action="" method="post">
                        <div class="form-group">
                            <label for="newSubTaskName">Subtask Name:</label>
                            <input type="text" class="form-control" id="newSubTaskName" name="newSubTaskName" required>
                        </div>
                        <div class="form-group">
                            <label for="newSubTaskDescription">Subtask Description:</label>
                            <textarea class="form-control" id="newSubTaskDescription" name="newSubTaskDescription" rows="3" required></textarea>
                        </div>
                        <input type="hidden" name="addNewSubTaskMainTaskId" id="addNewSubTaskMainTaskId" value="">
                        <input type="hidden" name="addNewSubTaskPlanId" id="addNewSubTaskPlanId" value="">
                        <button type="submit" class="btn btn-primary" name="addNewSubTask">Add Subtask</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () {
        $('.edit-main-task').click(function () {
            var taskId = $(this).data('task-id');
            var taskName = $(this).data('task-name');
            var taskDescription = $(this).data('task-description');

            $('#updateMainTaskName').val(taskName);
            $('#updateMainTaskDescription').val(taskDescription);
            $('input[name="updateMainTaskId"]').val(taskId);

            $('#updateMainTaskModal').modal('show');
        });
    });
    </script>
    
    <script>
        $(document).ready(function () {
            $('.edit-sub-task').click(function () {
                var subtaskId = $(this).data('subtask-id');
                var subtaskName = $(this).data('subtask-name');
                var subtaskDescription = $(this).data('subtask-description');
                var subtaskStatus = $(this).data('subtask-status');

                $('#updateSubTaskName').val(subtaskName);
                $('#updateSubTaskDescription').val(subtaskDescription);
                $('#updateSubTaskStatus').val(subtaskStatus);
                $('input[name="updateSubTaskId"]').val(subtaskId);

                $('#updateSubTaskModal').modal('show');
            });
        });
    </script>

    <script>
        function setSubTaskId(subtaskId) {
            document.getElementById('updateSubTaskId').value = subtaskId;
        }
    </script>

    <script>
        function prepareAddNewSubTaskModal(taskId, planId) {
            document.getElementById('addNewSubTaskMainTaskId').value = taskId;
            document.getElementById('addNewSubTaskPlanId').value = planId;
        }
    </script>



    

</body>

</html>