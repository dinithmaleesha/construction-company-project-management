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

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $result = mysqli_query($conn, "SELECT `project_id`, `project_name` FROM `project` WHERE `project_status` = 'Ongoing'");

    if (!$result) {
        die('<script>console.error("Error in SQL query: ' . mysqli_error($conn) . '");</script>');
        $errorMessage = "Please try again.";
        echo '<script>
            setTimeout(function() {
                window.location.href = "add-user.php?id=' . $user_id . '";
            }, 5000);
        </script>';
    }
} else {
    header("location: ../index.php");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</head>
<body>
    <!-- header -->
    <?php include "gm-header.php"; ?>
    
    <?php
    if (isset($successMessage)) {
        echo '<div class="alert alert-success">' . $successMessage . '</div>';
    } elseif (isset($errorMessage)) {
        echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
    }
    ?>
    <!-- <div class="container mt-5">
        <h3>Project Status Counts</h3>
        
        <?php
        // Fetch counts of ongoing, done, and pending projects
        $ongoingCountQuery = "SELECT COUNT(*) AS ongoing_count FROM project WHERE project_status = 'Ongoing'";
        $doneCountQuery = "SELECT COUNT(*) AS done_count FROM project WHERE project_status = 'Done'";
        $pendingCountQuery = "SELECT COUNT(*) AS pending_count FROM project WHERE project_status = 'Pending'";
        
        $ongoingCountResult = mysqli_query($conn, $ongoingCountQuery);
        $doneCountResult = mysqli_query($conn, $doneCountQuery);
        $pendingCountResult = mysqli_query($conn, $pendingCountQuery);
        
        $ongoingCount = mysqli_fetch_assoc($ongoingCountResult)['ongoing_count'];
        $doneCount = mysqli_fetch_assoc($doneCountResult)['done_count'];
        $pendingCount = mysqli_fetch_assoc($pendingCountResult)['pending_count'];
        ?>
        
        <div class="status-counts">
            <p>Ongoing Projects: <span class="badge badge-success"><?php echo $ongoingCount; ?></span></p>
            <p>Completed Projects: <span class="badge badge-primary"><?php echo $doneCount; ?></span></p>
            <p>Pending Projects: <span class="badge badge-warning"><?php echo $pendingCount; ?></span></p>
        </div>
    </div> -->

    <div class="container mt-5">
        <h3>Our Ongoing Projects</h3>
        <div class="card-deck mt-4">
            <?php
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['project_name']; ?></h5>
                        <?php $projectId = $row['project_id'];?>
                        <a href="project.php?id=<?php echo $user_id; ?>&project-id=<?php echo $projectId; ?>" class="btn btn-primary mt-2">View Project</a>
                    </div>
                </div>
            </div>
            <?php
            endwhile;
            ?>
        </div>
    </div>
    
</body>

</html>
