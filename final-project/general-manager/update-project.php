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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['project_id'])) {
    $user_id = $_GET['id'];
    $project_id = $_GET['project_id'];

    $query = "SELECT p.*, CONCAT(u.fname, ' ', u.lname) AS client_fullname
                FROM project p
                JOIN user u ON p.client_id = u.user_id
                WHERE p.project_id = $project_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $projectDetails = mysqli_fetch_assoc($result);
    } else {
        header("location: ../index.php");
        exit();
    }

    $pmQuery = "SELECT `user_id`, CONCAT(`fname`, ' ', `lname`) AS `full_name` FROM `user` WHERE `user_type` = 'pm'";
    $projectManagersResult = mysqli_query($conn, $pmQuery);

    $projectManagers = [];
    while ($row = $projectManagersResult->fetch_assoc()) {
        $projectManagers[] = $row;
    }
} else {
    header("location: ../index.php");
    exit();
}

if (isset($_POST['updateProject'])) {
    $updatedName = mysqli_real_escape_string($conn, $_POST['projectName']);
    $updatedDescription = mysqli_real_escape_string($conn, $_POST['updatedDescription']);
    $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
    $endDate = mysqli_real_escape_string($conn, $_POST['endDate']);
    $projectManager = mysqli_real_escape_string($conn, $_POST['projectManager']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $updateQuery = "UPDATE `project`
                        SET `project_name`='$updatedName',
                            `description`='$updatedDescription',
                            `start_date`='$startDate',
                            `end_date`='$endDate',
                            `pm_id`='$projectManager',
                            `project_status`='$status' 
                        WHERE project_id = $project_id";

    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $successMessage = "Project updated successfully!";
    } else {
        $errorMessage = "Error updating project. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Project</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>
<body>
    <!-- header -->
    <?php include 'gm-header.php'; ?>

    <div class="container mt-5">
        <form action="" method="post">
        <h3 class="mt-4">
            <a href="all-project.php?id=<?php echo $user_id; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            Update a Project
        </h3>
            <?php
            if (isset($successMessage)) {
                echo '<div class="alert alert-success">' . $successMessage . '</div>';
            } elseif (isset($errorMessage)) {
                echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
            }
            ?>

            <div class="form-group">
                <label for="projectName">Project Name:</label>
                <input type="text" class="form-control" id="projectName" name="projectName" value="<?php echo $projectDetails['project_name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="updatedDescription">Project Description:</label>
                <textarea class="form-control" id="updatedDescription" name="updatedDescription" rows="3"><?php echo $projectDetails['description']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="startDate">Start Date:</label>
                <input type="date" class="form-control" id="startDate" name="startDate" value="<?php echo $projectDetails['start_date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="endDate">End Date:</label>
                <input type="date" class="form-control" id="endDate" name="endDate" value="<?php echo $projectDetails['end_date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="projectManager">Project Manager:</label>
                <select class="form-control" id="projectManager" name="projectManager" required>
                    <?php foreach ($projectManagers as $manager): ?>
                        <option value="<?php echo $manager['user_id']; ?>" <?php echo ($projectDetails['pm_id'] == $manager['user_id']) ? 'selected' : ''; ?>><?php echo $manager['full_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- <div class="form-group">
                <label for="clientName">Client Name:</label>
                <input type="text" class="form-control" id="clientName" name="clientName" value="<?php echo $projectDetails['client_fullname']; ?>" required>
            </div> -->

            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Ongoing" <?php echo ($projectDetails['project_status'] == 'Ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                    <option value="Done" <?php echo ($projectDetails['project_status'] == 'Done') ? 'selected' : ''; ?>>Done</option>
                    <option value="Deleted" <?php echo ($projectDetails['project_status'] == 'Deleted') ? 'selected' : ''; ?>>Deleted</option>
                    
                </select>
            </div>

            <button type="submit" class="btn btn-primary" name="updateProject">Update Project</button>
        </form>
    </div>
    <div class="container mt-5"> </div>

    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
