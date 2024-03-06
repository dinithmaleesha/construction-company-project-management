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
    <title>Material Report</title>
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
            Material Report - Project Wise
        </h2>
        <div class="form-group">
        <div class="form-group">
            <label for="projectSelect">Select Project:</label>
            <form method="post">
                <select class="form-control" name="projectSelect" id="projectSelect" onchange="this.form.submit()">
                    <option value="0">- Select a Project -</option>
                    <?php
                    // Fetch projects from your database and populate the dropdown
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
            // Fetch and display content based on the selected project_id
            $query = "SELECT
                        M.`mat_id`,
                        M.`mat_name`,
                        M.`mat_type`,
                        P.`project_name`,
                        SUM(PMA.`material_allocation_quantity`) AS mat_quantity,
                        M.`mat_unit`
                    FROM
                        `project_material_allocation` AS PMA
                    JOIN
                        `material` AS M ON PMA.`mat_id` = M.`mat_id`
                    JOIN
                        `project` AS P ON PMA.`project_id` = P.`project_id`
                    WHERE
                        PMA.`allocation_status` = 'allocate'
                        AND PMA.`project_id` = $selectedProjectId
                    GROUP BY
                        M.`mat_id`;";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
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
            echo "<h2 class='table title'>Material Report | " . $projectName . "</h2>";
                echo "<div class='table-container'>";
                echo "<table>";
                echo "<thead><tr><th>Material ID</th><th>Material Name</th><th>Material Type</th><th>Material Quantity</th><th>Material Unit</th></tr></thead><tbody>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['mat_id'] . "</td>";
                    echo "<td>" . $row['mat_name'] . "</td>";
                    echo "<td>" . $row['mat_type'] . "</td>";
                    echo "<td>" . $row['mat_quantity'] . "</td>";
                    echo "<td>" . $row['mat_unit'] . "</td>";
                    echo "</tr>";
                }

                echo "</tbody></table>";
                echo "<p class='table footer'>Generate on: " . date("Y-m-d H:i:s") . "</p>";
                echo "</div>";
            } else {
                echo "<p>No material details found for the selected project.</p>";
            }
        }
        ?>
    </div>

        <button class="btn btn-primary mt-4" onclick="demoFromHTML()">Generate</button>

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
                    top: 20, // Adjust top margin based on the height of company details
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