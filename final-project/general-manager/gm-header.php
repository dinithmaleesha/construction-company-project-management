<?php
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    header("location: ../index.php");
    exit();
}
?>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">
            <img src="../assets/logo.png" alt="Logo" height="30">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item mr-3">
                    <a class="nav-link" href="home.php?id=<?php echo $user_id; ?>">Home</a>
                </li>
                <li class="nav-item dropdown mr-3">
                    <a class="nav-link dropdown-toggle" href="#" id="manageUserDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Manage User
                    </a>
                    <div class="dropdown-menu" aria-labelledby="manageUserDropdown">
                        <a class="dropdown-item" href="add-user.php?id=<?php echo $user_id; ?>">Add User</a>
                        <a class="dropdown-item" href="update-user.php?id=<?php echo $user_id; ?>">Update User</a>
                    </div>
                </li>
                <li class="nav-item dropdown mr-3">
                    <a class="nav-link dropdown-toggle" href="#" id="manageProjectDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Manage Project
                    </a>
                    <div class="dropdown-menu" aria-labelledby="manageProjectDropdown">
                        <a class="dropdown-item" href="add-project.php?id=<?php echo $user_id; ?>">Add Project</a>
                        <a class="dropdown-item" href="all-project.php?id=<?php echo $user_id; ?>">Update Project</a>
                    </div>
                </li>
                <li class="nav-item dropdown mr-3">
                    <a class="nav-link dropdown-toggle" href="#" id="manageMaterialDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Manage Material
                    </a>
                    <div class="dropdown-menu" aria-labelledby="manageMaterialDropdown">
                        <a class="dropdown-item" href="add-material.php?id=<?php echo $user_id; ?>">Add Material</a>
                        <a class="dropdown-item" href="update-material.php?id=<?php echo $user_id; ?>">Update Material</a>
                    </div>
                </li>
                <li class="nav-item dropdown mr-3">
                    <a class="nav-link dropdown-toggle" href="#" id="manageEmployeeDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Manage Employee
                    </a>
                    <div class="dropdown-menu" aria-labelledby="manageEmployeeDropdown">
                        <a class="dropdown-item" href="add-employee.php?id=<?php echo $user_id; ?>">Add Employee</a>
                        <a class="dropdown-item" href="update-employee.php?id=<?php echo $user_id; ?>">Update Employee</a>
                    </div>
                </li>
                <li class="nav-item dropdown mr-3">
                    <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Generate Report
                    </a>
                    <div class="dropdown-menu" aria-labelledby="reportDropdown">
                        <a class="dropdown-item" href="report-material-inventory.php?id=<?php echo $user_id; ?>"><i class="fas fa-box"></i> Material Report  | Inventory Report</a>
                        <a class="dropdown-item" href="report-material-project-wise.php?id=<?php echo $user_id; ?>"><i class="fas fa-box"></i> Material Report  | Project Wise</a>
                        <div class="dropdown-divider"></div> <!-- Add another divider -->
                        <a class="dropdown-item" href="report-employee-available-employee.php?id=<?php echo $user_id; ?>"><i class="fas fa-user"></i> Employee Report | Available Employee</a>
                        <a class="dropdown-item" href="report-employee-project-wise.php?id=<?php echo $user_id; ?>"><i class="fas fa-user"></i> Employee Report | Project Wise</a>
                        <div class="dropdown-divider"></div> <!-- Add another divider -->
                        <a class="dropdown-item" href="report-project.php?id=<?php echo $user_id; ?>"><i class="fas fa-briefcase"></i> Project</a>
                    </div>
                </li>
                <li class="nav-item dropdown mr-3">
                    <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Send Request
                    </a>
                    <div class="dropdown-menu" aria-labelledby="reportDropdown">
                        <a class="dropdown-item" href="send-employee-request.php?id=<?php echo $user_id; ?>">
                            <i class="fas fa-user"></i>&nbsp;  Request Employee
                        </a>
                        <a class="dropdown-item" href="send-material-request.php?id=<?php echo $user_id; ?>">
                            <i class="fas fa-box"></i>&nbsp;  Request Material
                        </a>
                    </div>
                </li>
                <li class="nav-item mr-3">
                    <a class="nav-link" href="view-request.php?id=<?php echo $user_id; ?>">View Request</a>
                </li>
                <li class="nav-item mr-3">
                    <a class="nav-link" href="my-request.php?id=<?php echo $user_id; ?>">My Request</a>
                </li>
                
            </ul>
            <ul class="navbar-nav">         
                <li class="nav-item">
                   <a class="nav-link" href="../php/logout.php?id=<?php echo $user_id; ?>">Log Out</a>
                </li>
            </ul>
            
        </div>
    </nav>
</header>
