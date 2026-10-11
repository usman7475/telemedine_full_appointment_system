<?php  if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<?php
if (!isset($_SESSION['temail'])) {         // condition Check: if session is not set. 
  header('location:../index.php');   // if not set the user is sendback to login page.
}
?>
<?php include '../conn.php'; ?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Telemedicine  </title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

 <link rel="stylesheet" href="../style.css" />
    <style>
body {
           font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            font-size: 13px;
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
            .nav-link:hover {
                background-color: white;
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
            <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top" >
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
               
   <a class="navbar-brand" href="#">Telemedicine  </a>          

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
 <a class="nav-link" href="doctorprofile.php">
    <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                     
              




<li class="nav-item">
    <a class="nav-link" href="chat.php">
    
    <i class="fas fa-calendar-check"></i> Appointments
                            </a>
                        </li>

<li class="nav-item">
    <a class="nav-link" href="prescriptions.php">
    
    <i class="fas fa-prescription-bottle"></i> Prescriptions
                            </a>
                        </li><li class="nav-item">
    <a class="nav-link" href="feedback.php">
    
    <i class="fas fa-star"></i> Feedback
                            </a>
                        </li>
<?php
$doctorid = $_SESSION['tid'];
$unread_query = "SELECT COUNT(*) as count FROM notifications WHERE doctorid = '$doctorid' AND is_read = 0";
$unread_result = mysqli_fetch_array(mysqli_query($conn, $unread_query));
$unread_count = $unread_result['count'];
?>
<li class="nav-item">
    <a class="nav-link" href="notifications.php">
    
    <i class="fas fa-bell"></i> Notifications
    <?php if ($unread_count > 0): ?>
        <span class="badge badge-danger" style="margin-left: 5px; padding: 3px 7px; border-radius: 50%; font-size: 0.75rem;">
            <?php echo $unread_count; ?>
        </span>
    <?php endif; ?>
                            </a>
                        </li>                        <!-- Add more items as needed -->
                    </ul>
                </div>
            </nav>

           
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js (Optional) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
