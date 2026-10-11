<?php session_start(); ?>
<?php
if (!isset($_SESSION['yemail'])) {         // condition Check: if session is not set. 
  header('location: index.php');   // if not set the user is sendback to login page.
}
?>
<?php include '../conn.php'; ?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Telemedicine - Admin Dashboard  </title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        /* Custom CSS for responsive sidebar */
        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 1000;
                padding-top: 56px; /* Adjust this value based on your navbar height */
              
            }

            .sidebar-sticky {
               
                top: 56px; /* Adjust this value based on your navbar height */
                height: calc(100vh - 56px); /* Adjust this value based on your navbar height */
                overflow-x: hidden;
                overflow-y: auto; /* Scrollable sidebar */
            }
            a {
    color:  rgb(0,200,200);
    font-weight: bold;  
}
            a:hover {
                background-color: #dc3545;
    color:  rgb(0,200,200);
    font-weight: bold;  
}

            
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
               
   <a class="navbar-brand" href="#">Telemedicine - Admin Dashboard  </a>          

<div class="navbar-nav ml-auto">
    <a class="nav-link" style='font-size:20px;color:red;' href="logout.php">
        <i class="fas fa-power-off"></i> LogOut
    </a>
</div>


     </nav>
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse" id="sidebarCollapse" ><br><br>
                <div class="sidebar-sticky">
                  
                    <ul class="nav flex-column">
      <li class="nav-item">
 <a class="nav-link active" href="home.php">
  <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
 <a class="nav-link" href="doctors.php">
    <i class="fas fa-user-md"></i> Doctor Management
                            </a>
                        </li>

         <li class="nav-item">
 <a class="nav-link" href="patients.php">
    <i class="fas fa-users"></i> Patient Management
                            </a>
                        </li>
                        <li class="nav-item">
        <a class="nav-link" href="activity.php">
    
    <i class="fas fa-chart-line"></i> System Activity
                            </a>
                        </li>

    <li class="nav-item">
        <a class="nav-link" href="reports.php">
                                <i class="fas fa-file-alt"></i> Reports & Analytics
                            </a>
                        </li>
                        <!-- Add more items as needed -->
                    </ul>
                </div>
            </nav>

            <!-- Main content area -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <!-- Page content will be inserted here -->
