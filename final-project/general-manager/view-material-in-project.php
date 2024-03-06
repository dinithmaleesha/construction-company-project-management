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
if (isset($_POST['deallocateMat'])) {
    // Start the transaction
    mysqli_autocommit($conn, false);

    $mat_id = mysqli_real_escape_string($conn, $_POST['mat_id']);
    $mat_quantity = mysqli_real_escape_string($conn, $_POST['mat_quantity']); // deallocated qt
    $updation_reason = "return";
    $now = date("Y-m-d");

    try {
        if ($updation_reason == "return") {
            // Insert to Material Updation Table
            $insetMatUpdationQuery = "INSERT INTO `material_updation`(`mat_update_quantity`, `mat_status`, `mat_update_date`, `mat_id`, `user_id`)
            VALUES ('$mat_quantity','$updation_reason','$now','$mat_id', '$user_id')";
            $insetMatUpdationResult = mysqli_query($conn, $insetMatUpdationQuery);

            if (!$insetMatUpdationResult) {
                throw new Exception("Error inserting into Material Updation Table");
            }

            $updateMatQtQuery = "UPDATE `material` SET `mat_quantity` = `mat_quantity` + $mat_quantity WHERE `mat_id` = $mat_id";
            $updateMatQtResult = mysqli_query($conn, $updateMatQtQuery);

            if (!$updateMatQtResult) {
                throw new Exception("Error updating material quantity");
            }

            $updateQuery = "UPDATE `project_material_allocation`
                            SET `allocation_status` = 'deallocated'
                            WHERE `mat_id` = $mat_id
                            AND `project_id` = $project_id";

            $result = mysqli_query($conn, $updateQuery);

            if (!$result) {
                throw new Exception("Error updating project_material_allocation");
            }

            mysqli_commit($conn);
            $successMessage = "Material Deallocated successfully!";
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $errorMessage = "Error Deallocate Material. " . $e->getMessage() . " Please try again.";
    } finally {
        mysqli_autocommit($conn, true);
        header("Location: {$_SERVER['PHP_SELF']}?id={$user_id}&project-id={$project_id}");
        exit();
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Material | <?php echo $projectName; ?></title>
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
            View Material | <?php echo $projectName; ?>
        </h2>
        
        <?php
            if (isset($successMessage)) {
                echo '<div class="alert alert-success">' . $successMessage . '</div>';
            } elseif (isset($errorMessage)) {
                echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
            }
        ?>

        <?php
        $selectQuery = "SELECT m.`mat_id`, m.`mat_name`, m.`mat_type`, 
                                SUM(pma.`material_allocation_quantity`) AS total_quantity, 
                                m.`mat_unit`, MAX(pma.`allocation_date`) AS latest_allocation_date, 
                                ma.`allocation_description`
                        FROM `project_material_allocation` pma
                        JOIN `material` m ON pma.`mat_id` = m.`mat_id`
                        JOIN `material_allocation` ma ON pma.`allocation_id` = ma.`allocation_id`
                        WHERE pma.`project_id` = $project_id
                            AND pma.`allocation_status` = 'allocate'
                        GROUP BY m.`mat_id`, m.`mat_name`, m.`mat_type`, m.`mat_unit`, ma.`allocation_description`";
        $result = mysqli_query($conn, $selectQuery);

        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Latest Allocation Date</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td>' . $row['mat_id'] . '</td>
                    <td>' . $row['mat_name'] . '</td>
                    <td>' . $row['total_quantity'] . '</td>
                    <td>' . $row['mat_unit'] . '</td>
                    <td>' . $row['latest_allocation_date'] . '</td>
                    <td>' . $row['allocation_description'] . '</td>
                    <td>';
                        if ($row['mat_type'] == 'special') {
                            echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#editModal' . $row['mat_id'] . '">
                                    <i class="fas fa-trash"></i> Deallocate
                                </button>';
                        } elseif ($row['mat_type'] == 'normal') {
                            echo '<button type="button" class="btn btn-secondary" disabled>
                                    <i class="fas fa-times"></i> Deallocate
                                </button>';
                        }

                    echo '</td>
                    </tr>';
                echo '<div class="modal fade" id="editModal' . $row['mat_id'] . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel' . $row['mat_id'] . '" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel' . $row['mat_id'] . '">Deallocate Material</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <input type="hidden" name="mat_id" value="' . $row['mat_id'] . '">
                                <input type="hidden" name="mat_quantity" value="' . $row['total_quantity'] . '">
                                <div class="modal-body">
                                    <p class="mb-0">Are you sure you want to deallocate the following material?</p>
                                    <h5 class="mt-2">' . $row['mat_name'] . ' - Qt ' . $row['total_quantity'] . '</h5>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                    <button type="submit" class="btn btn-primary" name="deallocateMat">Yes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </div>';

                // not this
                echo '<div class="modal fade" id="##' . $row['mat_id'] . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel-one' . $row['mat_id'] . '" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel' . $row['mat_id'] . '">Deallocate Material</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <input type="hidden" name="mat_id" value="' . $row['mat_id'] . '">
                                <div class="form-group">
                                    <label for="mat_name">Material Name:</label>
                                    <input type="text" class="form-control" id="mat_name" name="mat_name" value="' . $row['mat_name'] . '" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="allocated_qt">Allocated Quantity:</label>
                                    <input type="text" class="form-control" id="allocated_qt" name="allocated_qt" value="' . $row['total_quantity'] . '" readonly>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="mat_quantity">Enter Deallocate Quantity:</label>
                                    <input type="number" class="form-control" id="mat_quantity" name="mat_quantity" required max="' . $row['total_quantity'] . '">
                                </div>
                                <div class="form-group">
                                    <label for="reason">Reason:</label>
                                    <select class="form-control" id="reason" name="reason" required>
                                        <option value="">- Select a Option -</option>
                                        <option value="return">Return</option>
                                        <option value="damage">Damage</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary" name="deallocateMat">Deallocate</button>
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

    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
