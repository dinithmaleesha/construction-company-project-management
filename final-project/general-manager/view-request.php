<!-- Allocate Employee to the PROJECT -->

<?php
session_start();
if (!isset($_SESSION['gm']) || $_SESSION['gm'] !== true) {
    header("Location: ../php/access-denied.php");
    exit();
}
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    header("location: ../php/access-denied.php");
    exit();
}
@include '../php/config.php';

if (isset($_POST['updateStatus'])) {
    $requestId = $_POST["modalIdInput"];
    $status = $_POST["requestStatus"];

    $updateStatusQuery = "UPDATE employee_request SET emp_request_status = ? WHERE emp_req_id = ?";
    $stmt = $conn->prepare($updateStatusQuery);
    $stmt->bind_param("si", $status, $requestId);

    if ($stmt->execute()) {
        $successMessage = "Status updated successfully.";
    } else {
    }

    $stmt->close();
} else {
    // Invalid request
}

if (isset($_POST['materialRequest'])) {
    $materialRequestId = mysqli_real_escape_string($conn, $_POST['modalIdInputMat']);
    $materialRequestStatus = mysqli_real_escape_string($conn, $_POST['matSequestStatus']);

    $updateQuery = "UPDATE `material_request` SET `mat_request_status`=? WHERE `mat_request_id`= ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $materialRequestStatus, $materialRequestId);

    if ($stmt->execute()) {
        $successMessage = "Status updated successfully.";
    } else {
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
    <title>View Request</title>
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
            View Request from Project Manager
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

        <hr>

        
        <?php
            $selectEmpRequest = "SELECT er.`emp_req_id`, er.`employee_role`, er.`emp_count`, er.`emp_request_status`,
                                        p.`project_name`, CONCAT(u.`fname`, ' ', u.`lname`) AS `pm_name`
                                FROM `employee_request` er
                                JOIN `project` p ON er.`project_id` = p.`project_id`
                                JOIN `user` u ON p.`pm_id` = u.`user_id`
                                ORDER BY er.`emp_req_id` DESC;
                                ";

            $result = mysqli_query($conn, $selectEmpRequest);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<div class="mt-4">
                            <h3><i class="fas fa-users"></i>&nbsp; Employee Request</h3>
                        </div>';
                        echo '<table class="table mt-3">
                        <thead>
                            <tr>
                                <th style="width: 10%;">ID</th>
                                <th style="width: 50%;">Request</th>
                                <th style="width: 30%;">Request By</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody style="max-height: 200px; overflow-y: auto;">';
                $counter = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $requestId = $row['emp_req_id'];
                    $message = "Requesting <b>" . $row['emp_count'] . " " . $row['employee_role'] . "</b>(s) for the project <b>" . $row['project_name'] . "</b>";
                    $requestedBy = "Requested by " . $row['pm_name'];
                
                    echo '<tr>';
                    echo '<td>' . $counter . '</td>';
                    echo '<td>' . $message . '</td>';
                    echo '<td>' . $requestedBy . '</td>';
                    $status = $row['emp_request_status'];
                            $colorClass = '';
        
                            if ($status == 'Approve') {
                                $colorClass = 'text-success';
                            } elseif ($status == 'Decline') {
                                $colorClass = 'text-danger';
                            }
        
                            echo '<td class="' . $colorClass . '">' . $status . '</td>';
                    echo '<td><button type="button" class="btn btn-warning" onclick="openApproveDeclineModal(' . $requestId . ', \'' . $message . '\', \'' . $requestedBy . '\')">Approve/Decline</button></td>';
                    echo '</tr>';
                    $counter++;
                }
                
                echo '</tbody></table>';
                
                } else {
                    echo '<div class="mt-4">
                            <h4><i class="fas fa-exclamation-triangle"></i>&nbsp; No Results Found</h4>
                        </div>';
                }
            } else {
                echo '<p>Error executing the query: ' . mysqli_error($conn) . '</p>';
            }

            mysqli_free_result($result);
        ?>


        <?php
            $selectEmpRequest = "SELECT 
                                    mr.mat_request_id,
                                    mr.mat_req_name,
                                    mr.other_name,
                                    mr.mat_count,
                                    mr.mat_request_status,
                                    mr.user_id,
                                    mr.project_id,
                                    p.project_name,
                                    u.user_id,
                                    CONCAT(u.fname, ' ', u.lname) AS pm_name
                                FROM 
                                    material_request mr
                                JOIN 
                                    project p ON mr.project_id = p.project_id
                                JOIN 
                                    user u ON p.pm_id = u.user_id
                                ";

            $result = mysqli_query($conn, $selectEmpRequest);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<div class="mt-4">
                            <h3><i class="fas fa-box"></i>&nbsp; Material Request</h3>
                        </div>';
                        echo '<table class="table mt-3">
                        <thead>
                            <tr>
                                <th style="width: 10%;">ID</th>
                                <th style="width: 50%;">Request</th>
                                <th style="width: 30%;">Request By</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody style="max-height: 200px; overflow-y: auto;">';
                
                        $counter = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $message = "Requesting <b>" . $row['mat_count'] . " ";

                            if ($row['other_name'] === NULL) {
                                $message .= $row['mat_req_name'];
                            } else {
                                $message .= $row['other_name'] . "(s) (New Material)";
                            }
        
                            $message .= "</b> for the project <b>" . $row['project_name'] . "</b>";
                            $requestId = $row['mat_request_id'];
                            $requestedBy = "Requested by " . $row['pm_name'];
                        
                            echo '<tr>';
                            echo '<td>' . $counter . '</td>';
                            echo '<td>' . $message . '</td>';
                            echo '<td>' . $requestedBy . '</td>';
                            $status = $row['mat_request_status'];
                            $colorClass = '';
        
                            if ($status == 'Approve') {
                                $colorClass = 'text-success';
                            } elseif ($status == 'Decline') {
                                $colorClass = 'text-danger';
                            }
        
                            echo '<td class="' . $colorClass . '">' . $status . '</td>';
                            echo '<td><button type="button" class="btn btn-warning" onclick="openApproveDeclineModalMaterial(' . $requestId . ', \'' . $message . '\', \'' . $requestedBy . '\')">Approve/Decline</button></td>';
                            
                           echo '</tr>';
                            $counter++;
                        }
                        echo '</tbody></table>';
                        
                        } else {
                            echo '<div class="mt-4">
                                    <p><i class="fas fa-exclamation-triangle"></i>&nbsp; No Results Found</p>
                                </div>';
                        }
                    } else {
                        echo '<p>Error executing the query: ' . mysqli_error($conn) . '</p>';
                    }
        
                    mysqli_free_result($result);
                   
                ?>

        
        

    </div>



    <!-- Modal -->
    <div class="modal" id="approveDeclineModal" tabindex="-1" role="dialog" aria-labelledby="approveDeclineModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveDeclineModalLabel">Approve/Decline Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage"></p>
                    <p id="modalRequestedBy"></p>
                    <hr>
                    <form id="statusUpdateForm" action="" method="post">
                        <div class="form-group">
                            <input type="hidden" id="modalIdInput" name="modalIdInput" value="">
                            <label for="requestStatus">Select Status:</label>
                            <select class="form-control" id="requestStatus" name="requestStatus">
                                <option value="Approve">Approve</option>
                                <option value="Decline">Decline</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" form="statusUpdateForm" class="btn btn-primary" name="updateStatus">OK</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openApproveDeclineModal(requestId, message, requestedBy) {
            document.getElementById('modalMessage').innerHTML = message;
            document.getElementById('modalRequestedBy').innerHTML = requestedBy;

            document.getElementById('modalIdInput').value = requestId;

            $('#approveDeclineModal').modal('show');
        }
    </script>


    <!-- Modal -->
        <div class="modal" id="approveDeclineModalMaterial" tabindex="-1" role="dialog" aria-labelledby="approveDeclineModalMaterialLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveDeclineModalMaterialLabel">Approve/Decline Material Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Display request details -->
                        <!-- <p id="modalMessageIdMat"></p> -->
                        <p id="modalMessageMat"></p>
                        <p id="modalRequestedByMat"></p>
                        <hr>
                        <form id="matStatusUpdateForm" method="post">
                            <div class="form-group">
                                <input type="hidden" id="modalIdInputMat" name="modalIdInputMat" value="">
                                <label for="matSequestStatus">Select Status:</label>
                                <select class="form-control" id="matSequestStatus" name="matSequestStatus">
                                    <option value="Approve">Approve</option>
                                    <option value="Decline">Decline</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="materialRequest" class="btn btn-primary">OK</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openApproveDeclineModalMaterial(requestId, message, requestedBy) {
                document.getElementById('modalMessageMat').innerHTML = message;
                document.getElementById('modalRequestedByMat').innerHTML = requestedBy;

                document.getElementById('modalIdInputMat').value = requestId;

                $('#approveDeclineModalMaterial').modal('show');
            }
        </script>





    

</body>
</html>