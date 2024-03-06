<?php
session_start();
if (!isset($_SESSION['client']) || $_SESSION['client'] !== true) {
    header("Location: ../php/access-denied.php");
    exit();
}
if (isset($_GET['id']) && isset($_GET['task-id']) || isset($_GET['pid'])) {
    $user_id = $_GET['id'];
    $task_id = $_GET['task-id'];
    $project_id = $_GET['pid'];
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
    <title>View Image</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
        .max-height-400 {
            max-height: 475px;
        }
        .carousel-container {
            max-height: 475px;
            overflow: hidden;
            position: relative;
            background-color: rgba(0, 0, 0, 0.05);
        }
        .carousel-image {
            width: auto;
            max-width: auto;
            max-height: 475px;
            display: block;
            margin: 0 auto;
            object-fit: contain;
        }
    </style>

    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <!-- header -->
    <?php include "client-header.php"; ?>
    <div class="container mt-5">
        <h2 class="mt-4">
            <a href="view-plan.php?id=<?php echo $user_id; ?>&pid=<?php echo $project_id; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            View Image
        </h2>

        <!-- display image -->
        <div class="container mt-3">
            <?php
            $selectImagePath = "SELECT
                                    mt.task_name,
                                    pt.task_id,
                                    pti.image_path
                                FROM
                                    main_task mt
                                JOIN
                                    plan_task pt ON mt.task_id = pt.task_id
                                LEFT JOIN
                                    plan_task_images pti ON pt.plan_task_id = pti.plan_task_id
                                WHERE
                                    pt.task_id = $task_id
                                ";
            $result = mysqli_query($conn, $selectImagePath);

            if ($result) {
                $images = [];
                $taskName = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['image_path'] !== null) {
                        $images[] = $row['image_path'];
                        $taskName = $row['task_name'];
                    }
                }

                if (!empty($images)) {
                    echo '<h4><i class="fas fa-camera"></i> Images of ' . $taskName . '</h4>';
                    echo '<div id="imageCarousel" class="carousel slide" data-ride="carousel">';
                    echo '    <div class="carousel-inner">';
    
                    foreach ($images as $key => $imagePath) {
                        $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $imagePath);
                        $relativeUrl = str_replace('\\', '/', $relativePath);
    
                        echo '        <div class="carousel-item ' . ($key === 0 ? 'active' : '') . '">';
                        echo '            <div class="carousel-container">';
                        echo '                <img src="' . $relativeUrl . '" alt="Task Image" class="d-block w-100 carousel-image">';
                        echo '            </div>';
                        echo '        </div>';
                    }
    
                    echo '    </div>';
                    echo '    <a class="carousel-control-prev" href="#imageCarousel" role="button" data-slide="prev">';
                    echo '        <span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                    echo '        <span class="sr-only">Previous</span>';
                    echo '    </a>';
                    echo '    <a class="carousel-control-next" href="#imageCarousel" role="button" data-slide="next">';
                    echo '        <span class="carousel-control-next-icon" aria-hidden="true"></span>';
                    echo '        <span class="sr-only">Next</span>';
                    echo '    </a>';
                    echo '</div>';
                } else {
                    // No image available
                    echo '<h4>No image available for this task.</h4>';
                }
            } else {
                // Query failed
                echo '<p>Error retrieving image paths.</p>';
            }
            ?>
        </div>

    </div>

    
</body>

</html>
