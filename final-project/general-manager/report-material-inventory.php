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

    $query = "SELECT `mat_id`, `mat_name`, `mat_type`, `mat_quantity`, `mat_unit` FROM `material`";
    $result = mysqli_query($conn, $query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    
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
            display: none;
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
            Material Inventory Report
        </h2>
        



        <div class="mt-4"> </div>
        <div id="content">
                <div class="report-header">
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
            <hr style="margin: 10px auto; border-color: #ccc; border-width: 4px;">
            <h2 class="table title">Material Inventory Report</h2>
            <?php
                date_default_timezone_set('Asia/Colombo');

                $query = "SELECT `mat_id`, `mat_name`, `mat_type`, `mat_quantity`, `mat_unit` FROM `material`";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
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
                    echo "</div>";
                    echo "<p class='table footer'>Generate on: " . date("Y-m-d H:i:s") . "</p>";
                } else {
                    echo "<p>No material details found.</p>";
                }
            ?>
        </div>
        <button class="btn btn-primary mt-4" onclick="demoFromHTML()">Generate</button>


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
                        var currentDate = new Date();
                        var formattedDate = currentDate.getFullYear() + '-' + (currentDate.getMonth() + 1) + '-' + currentDate.getDate();

                        pdf.save('Material Inventory Report - ' + formattedDate + '.pdf');
                    }, margins
                );
            }

        </script>
    </div>
</body>
</html>