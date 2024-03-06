
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


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Request</title>
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
            My Request
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
            $selectEmpRequest = "SELECT `emp_req_id`, `employee_role`, `emp_count`, `user_request_status`, `emp_request_status`, `user_id`, `project_id`
                                FROM `employee_request` WHERE user_id = $user_id
                                ORDER BY `emp_req_id` DESC
                                ";

            $result = mysqli_query($conn, $selectEmpRequest);
            echo '<div class="mt-4">
                            <h3><i class="fas fa-users"></i>&nbsp; Employee Request</h3>
                        </div>';

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    
                        echo '<table class="table mt-3">
                        <thead>
                            <tr>
                                <th style="width: 10%;">ID</th>
                                <th style="width: 50%;">Request</th>
                                <th style="width: 10%;">Status</th>
                            </tr>
                        </thead>
                        <tbody style="max-height: 200px; overflow-y: auto;">';

                $counter = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $requestId = $row['emp_req_id'];
                    $message = "Requesting more <b>" . $row['emp_count'] . " " . $row['employee_role'] . "</b> (s)";
                
                    echo '<tr>';
                    echo '<td>' . $counter . '</td>';
                    echo '<td>' . $message . '</td>';
                    $status = $row['user_request_status'];
                    $colorClass = '';

                    if ($status == 'Approve') {
                        $colorClass = 'text-success';
                    } elseif ($status == 'Decline') {
                        $colorClass = 'text-danger';
                    }

                    echo '<td class="' . $colorClass . '">' . $status . '</td>';
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

        <?php
            $selectEmpRequest = "SELECT `mat_request_id`, `mat_req_name`, `other_name`, `mat_count`, `user_mat_request_status`, `mat_request_status`, `user_id`, `project_id`
                                    FROM `material_request`
                                    WHERE `user_id` = $user_id
                                    ORDER BY `mat_request_id` DESC";
            // Execute the query
            $result = mysqli_query($conn, $selectEmpRequest);
            echo '<div class="mt-4">
            <hr>
            </div>';
            echo '<div class="mt-4">
            
            </div>';
            echo '<div class="mt-4">
                    <h3><i class="fas fa-box"></i>&nbsp; Material Request</h3>
                </div>';

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                   
                        echo '<table class="table mt-3">
                        <thead>
                            <tr>
                                <th style="width: 10%;">ID</th>
                                <th style="width: 50%;">Request</th>
                                <th style="width: 10%;">Status</th>
                            </tr>
                        </thead>
                        <tbody style="max-height: 200px; overflow-y: auto;">';

                $counter = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $message = "Requesting more <b>" . $row['mat_count'] . " ";

                    if ($row['other_name'] === NULL) {
                        $message .= $row['mat_req_name'];
                    } else {
                        $message .= $row['other_name'] . " (New Material)";
                    }

                    $message .= "</b> (s)";
                    $requestId = $row['user_id'];
                
                    echo '<tr>';
                    echo '<td>' . $counter . '</td>';
                    echo '<td>' . $message . '</td>';
                    $status = $row['user_mat_request_status'];
                    $colorClass = '';

                    if ($status == 'Approve') {
                        $colorClass = 'text-success';
                    } elseif ($status == 'Decline') {
                        $colorClass = 'text-danger';
                    }

                    echo '<td class="' . $colorClass . '">' . $status . '</td>';
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
            mysqli_close($conn);
        ?>

        
        

    </div>

    

</body>
</html>