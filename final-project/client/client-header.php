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
                
                
                
                
                
            </ul>
            <ul class="navbar-nav">         
                <li class="nav-item">
                   <a class="nav-link" href="../php/logout.php?id=<?php echo $user_id; ?>">Log Out</a>
                </li>
            </ul>
            
        </div>
    </nav>
</header>
