<?php
session_start();
if (!isset($_SESSION['client']) || $_SESSION['client'] !== true) {
    header("Location: ../php/access-denied.php");
    exit();
}

@include '../php/config.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    header("location: ../php/access-denied.php");
    exit();
}

// Fetch project details for the client
$projectQuery = "SELECT `project_id`, `project_name` FROM `project` WHERE `client_id` = '$user_id'";
$projectResult = mysqli_query($conn, $projectQuery);

if (!$projectResult) {
    die("Error fetching project details: " . mysqli_error($conn));
}

// Array to store project details
$projects = array();

while ($row = mysqli_fetch_assoc($projectResult)) {
    $projects[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select your project</title>

    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
        crossorigin="anonymous">
</head>

<body>

    <!-- Header -->
    <?php include "client-header.php"; ?>

    <div class="container">
    <div class="container mt-5">
        <h3>Your Projects</h3>
        <div class="card-deck mt-4">
            <?php foreach ($projects as $project): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $project['project_name']; ?></h5>
                            <?php $projectId = $project['project_id'];?>
                            <a href="my-project.php?id=<?php echo $user_id; ?>&pid=<?php echo $projectId; ?>" class="btn btn-primary mt-2">View Project</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


</body>

</html>
