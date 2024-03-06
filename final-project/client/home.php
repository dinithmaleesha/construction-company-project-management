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

$query = "SELECT `project_id` FROM `project` WHERE `client_id` = '$user_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}

$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Naveen Builders</title>

    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
        crossorigin="anonymous">
        <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .banner {
            height: 60vh;
            background: url('../assets/client-home-background-1.jpg') center/cover; 
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .container {
            padding: 20px;
            text-align: center;
        }

        .welcome-text {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
        }

        .btn-primary {
            
        }
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px); 
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-text {
            animation: fadeUp 1s ease-out; 
        }
    </style>
</head>

<body>

    <!-- Header -->
    <?php include "client-header.php"; ?>

    <div class="banner">
        
    </div>

    <!-- Welcome Section -->
    <div class="container">
        <div class="welcome-text">
            <h1>Welcome to Naveen Builders</h1>
            <p>Discover your dream projects with us.</p>
            <?php
                $projectIdsQuery = "SELECT project_id FROM project WHERE client_id = '$user_id'";
                $projectIdsResult = mysqli_query($conn, $projectIdsQuery);

                if (!$projectIdsResult) {
                    die("Error executing query: " . mysqli_error($conn));
                }

                $projectIdsArray = array();

                while ($row = mysqli_fetch_assoc($projectIdsResult)) {
                    $projectIdsArray[] = $row['project_id'];
                }

                $projectCount = count($projectIdsArray);

                if ($projectCount > 1) {
                    echo '<a href="select-project.php?id=' . $user_id . '" class="btn btn-primary btn-view-project">View Your Projects</a>';
                } else {
                    // Use the first project ID from the array
                    $firstProjectId = reset($projectIdsArray);
                    echo '<a href="my-project.php?id=' . $user_id . '&pid=' . $firstProjectId . '" class="btn btn-primary btn-view-project">View Your Project</a>';
                }
            ?>


            
            
        </div>
    </div>

</body>

</html>