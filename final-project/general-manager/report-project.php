<!-- Allocate Employee to the PROJECT -->

<?php
ob_start(); // Start output buffering

session_start();
@include '../php/config.php';


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

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Report</title>
    <style>
        #content {
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }
        .report-header {
            display: flex;
            justify-content: left;
            align-items: center;
            padding: 10px;
            border-bottom: 2px solid #ccc;
        }

        .company-logo img {
            max-width: 100px; 
            height: auto;
        }

        .company-details {
            padding-left : 30px;
            text-align: left;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
        }

        .company-email,
        .company-address,
        .company-phone {
            margin-bottom: 4px;
        }


        .table-container {
            max-height: 300px; 
            overflow-y: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            font-size: 11px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2.table.title {
            margin-top: 50px;
            /* display: none; */
        }
        p.table.footer {
            display: none;
            margin-top: 10px; 
            font-size: 10px; 
        }
    </style>

    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- Bootstrap JavaScript and jQuery files -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
</head>
<body>
    <!-- header -->
    <?php include "gm-header.php"; ?>

    <div class="container mt-5">
        <h2 class="mt-4">
            <a href="home.php?id=<?php echo $user_id; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            Project Report
        </h2>
        <div class="form-group">
        <div class="form-group">
            <label for="projectSelect">Select Project:</label>
            <form method="post">
                <select class="form-control" name="projectSelect" id="projectSelect" onchange="this.form.submit()">
                    <option value="0">- Select a Project -</option>
                    <?php
                    $projectQuery = "SELECT `project_id`, `project_name` FROM `project`";
                    $projectResult = mysqli_query($conn, $projectQuery);
                    while ($projectRow = mysqli_fetch_assoc($projectResult)) {
                        $selected = ($_POST['projectSelect'] == $projectRow['project_id']) ? 'selected' : '';
                        echo "<option value='" . $projectRow['project_id'] . "' $selected>" . $projectRow['project_name'] . "</option>";
                    }
                    ?>
                </select>
            </form>
        </div>

    </div>

    <div class="mt-4"></div>

    <div id="content">
        
        <?php
        if (isset($_POST['projectSelect'])) {
            $selectedProjectId = $_POST['projectSelect'];
            $projectName = "";

            $selectProjectName = "SELECT `project_name` FROM `project` WHERE `project_id` = $selectedProjectId";
            $selectProjectNameResult = mysqli_query($conn, $selectProjectName);
            if (mysqli_num_rows($selectProjectNameResult) > 0) {
                $firstRow = mysqli_fetch_assoc($selectProjectNameResult);
                $projectName = $firstRow['project_name'];
            }
            $query = "SELECT
                        P.`project_id`,
                        P.`project_name`,
                        P.`description`,
                        P.`start_date`,
                        P.`end_date`,
                        P.`project_status`,
                        CONCAT(C.`fname`, ' ', C.`lname`) AS `client_full_name`,
                        CONCAT(PM.`fname`, ' ', PM.`lname`) AS `pm_full_name`,
                        P.`plan_id`
                    FROM
                        `project` AS P
                    JOIN
                        `user` AS C ON P.`client_id` = C.`user_id`
                    JOIN
                        `user` AS PM ON P.`pm_id` = PM.`user_id`
                    WHERE
                        P.`project_id` = $selectedProjectId;";

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="report-header">
                                    <!-- Left Side - Company Logo -->
                                    <div class="company-logo">
                                        <img src="../assets/logo.png" alt="Company Logo">
                                    </div>

                                    <!-- Right Side - Company Details -->
                                    <div class="company-details">
                                        <h1 class="company-name">Naveen Builders</h1>
                                        <p class="company-email">info@naveenbuilders.com</p>
                                        <p class="company-address">123 Main Street, Cityville</p>
                                        <p class="company-phone">(123) 456-7890</p>
                                    </div>
                                    
                                </div>
                                <hr style="margin: 10px auto; border-color: #ccc; border-width: 4px;">';
                            echo "<h2 class='table title'>Project Report | " . $projectName . "</h2>";

                            echo "<p><strong>Project ID:</strong> " . $row['project_id'] . "</p>";
                            echo "<p><strong>Project Name:</strong> " . $row['project_name'] . "</p>";
                            echo "<p><strong>Description:</strong> " . $row['description'] . "</p>";
                            echo "<p><strong>Start Date:</strong> " . $row['start_date'] . "</p>";
                            echo "<p><strong>End Date:</strong> " . $row['end_date'] . "</p>";
                            echo "<p><strong>Project Status:</strong> " . $row['project_status'] . "</p>";
                            echo "<p><strong>Client:</strong> " . $row['client_full_name'] . "</p>";
                            echo "<p><strong>Project Manager:</strong> " . $row['pm_full_name'] . "</p>";
                            echo "<p><strong>Plan ID:</strong> " . $row['plan_id'] . "</p>";

                            echo "<p class='table footer'>Generate on: " . date("Y-m-d H:i:s") . "</p>";
                            // echo '<button class="btn btn-primary mt-4" onclick="demoFromHTML()">Generate</button>';
                            $selectPlanName = "SELECT p.plan_name, p.plan_description
                                FROM project pr
                                INNER JOIN plan p ON pr.plan_id = p.plan_id
                                WHERE pr.project_id = $selectedProjectId";

                            $selectPlanNameQuery = mysqli_query($conn, $selectPlanName);

                            if ($selectPlanNameQuery) {
                                if (mysqli_num_rows($selectPlanNameQuery) > 0) {
                                    $planData = mysqli_fetch_assoc($selectPlanNameQuery);

                                    $planName = $planData['plan_name'];
                                    $planDescription = $planData['plan_description'];

                                    // Display Plan Name from Database
                                    echo '<div class="mt-3">';
                                    echo '<br>';
                                    echo '<h4>Plan Name: ' . $planName . '</h4>';
                                    echo '<p>Description: ' . $planDescription . '</p>';
                                    echo '</div>';
                                    echo '<div class="mb-5"></div>';
                                } else {
                                    echo '<div class="mt-3"><h4>No plan found for this project.</h4></div>';
                                }
                            } else {
                                echo '<div class="mt-3"><h4>Loading...</h4></div>';
                            }

                            $checkPlanQuery = "SELECT `plan_id` FROM `project` WHERE `project_id` = $selectedProjectId";
                            $checkPlanResult = mysqli_query($conn, $checkPlanQuery);

                            if ($checkPlanResult) {
                                $row = mysqli_fetch_assoc($checkPlanResult);
                                if ($row && isset($row['plan_id'])) {
                                    $planId = $row['plan_id'];

                                    $selectPlanMainTask = "SELECT mt.task_id, mt.task_name, mt.task_description, pt.task_status
                                                            FROM main_task mt
                                                            JOIN plan_task pt ON mt.task_id = pt.task_id
                                                            WHERE pt.plan_id = $planId";

                                    $selectPlanMainTaskQuery = mysqli_query($conn, $selectPlanMainTask);
                                    $taskCounter = 1;

                                    if ($selectPlanMainTaskQuery) {
                                        while ($taskData = mysqli_fetch_assoc($selectPlanMainTaskQuery)) {
                                            $taskId = $taskData['task_id'];
                                            $taskName = $taskData['task_name'];
                                            $taskDesc = $taskData['task_description'];
                                            $taskStatus = $taskData['task_status'];

                                            echo '<div class="mt-5">';
                                            echo '<div class="d-flex justify-content-between align-items-start">';
                                            echo '    <div>';
                                            echo '        <h5>' . $taskCounter . '. Main Task: ' . $taskName . '</h5>';
                                            echo '        <p>' . $taskDesc . '</p>';

                                            $selectSubtasksQuery = "SELECT st.subtask_name, st.sub_description, pst.sub_task_status
                                                                    FROM sub_task st
                                                                    JOIN plan_sub_task pst ON st.subtask_id = pst.subtask_id
                                                                    WHERE pst.plan_id = $planId AND st.task_id = {$taskData['task_id']}";

                                            $selectSubtasksResult = mysqli_query($conn, $selectSubtasksQuery);
                                            $subTaskCounter = 1;

                                            if ($selectSubtasksResult) {
                                                echo '<ul>';
                                                while ($subtaskData = mysqli_fetch_assoc($selectSubtasksResult)) {
                                                    $subtaskStatus = $subtaskData['sub_task_status'];
                                                    $color = '';

                                                    if ($subtaskStatus === 'Completed') {
                                                        $color = 'green';
                                                    } elseif ($subtaskStatus === 'Not_Yet') {
                                                        $color = 'red';
                                                    } else {
                                                        $color = 'orange'; 
                                                    }
                                                    echo '<br>';
                                                    echo '<li>';
                                                    
                                                    echo '<strong>Sub Task Name:</strong> ' . $subtaskData['subtask_name'] . '<br>';
                                                    echo '<strong>Sub Task Description:</strong> ' . $subtaskData['sub_description'] . '<br>';
                                                    echo '<strong>Sub Task Status:</strong> <span style="color: ' . $color . ';">' . $subtaskStatus . '</span>';
                                                    echo '</li>';
                                                    $subTaskCounter++;
                                                }
                                                echo '</ul>';
                                            } else {
                                                echo '<p>No subtasks found for this task.</p>';
                                            }

                                            echo '</div>';
                                            echo '</div>';
                                            echo '<hr>';
                                            echo '</div>';

                                            $taskCounter++;
                                        }

                                        echo '<button class="btn btn-primary mt-4" onclick="demoFromHTML()">Generate</button>';
                                    } else {
                                        echo '<p>No main tasks found for this plan.</p>';
                                    }

                                    } else {
                                        echo '<div class="mt-3"><h4>Loading...</h4></div>';
                                    }
                                } else {
                                    echo '<div class="mt-3"><h4>No plan found for this project.</h4></div>';
                                }
                            }
                            
                        }
                         else{
                            echo "<p>No project details found for the selected project.</p>";
                         }
                    } else {
                        echo "<p>No project details found for the selected project.</p>";
                    }
        
        ?>
    </div>

        

        <!-- Add your jsPDF script here -->
        <script>
            function demoFromHTML() {
                var pdf = new jsPDF('p', 'pt', 'a4');
                // source can be HTML-formatted string, or a reference
                // to an actual DOM element from which the text will be scraped.
                source = $('#content')[0];

                // Calculate the height of the company details
                var companyDetailsHeight = $('.report-header').outerHeight();

                // we support special element handlers. Register them with jQuery-style 
                // ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
                // There is no support for any other type of selectors 
                // (class, of compound) at this time.
                specialElementHandlers = {
                    // element with id of "bypass" - jQuery style selector
                    '#bypassme': function (element, renderer) {
                        // true = "handled elsewhere, bypass text extraction"
                        return true
                    }
                };
                margins = {
                    top: 20,
                    left: 20,
                    right: 20,
                    bottom: 60,
                    width: 522
                };

                // all coords and widths are in jsPDF instance's declared units
                // 'inches' in this case
                pdf.fromHTML(
                    source, // HTML string or DOM elem ref.
                    margins.left, // x coord
                    margins.top, { // y coord
                        'width': margins.width, // max width of content on PDF
                        'elementHandlers': specialElementHandlers,
                        'pagesplit': true,
                        'center': true // Center the content horizontally
                    },
                    function (dispose) {
                        

                        pdf.save('Material Report - ' + '<?php echo $projectName; ?>' + ' Project.pdf');
                    }, margins
                );
            }

        </script>

        



        <div class="mt-4"> </div>
        
    </div>
</body>
</html>