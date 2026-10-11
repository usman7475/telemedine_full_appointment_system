<?php include 'conn.php'; ?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Telemedicine </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  

  
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css">
  
  <style>
    .navbar {
      background: linear-gradient(135deg, #5B8DEE 0%, #4A6FD9 50%, #3D5AA8 100%) !important;
      box-shadow: 0 4px 12px rgba(91, 141, 238, 0.2) !important;
    }
    
    .navbar .navbar-brand {
      color: #ffffff !important;
      font-size: 1.4rem;
      font-weight: 700 !important;
      letter-spacing: 0.5px;
    }
    
    .navbar .nav-link {
      color: rgba(255, 255, 255, 0.95) !important;
      transition: all 0.3s ease;
      font-weight: 500;
    }
    
    .navbar .nav-link:hover {
      color: #ffffff !important;
      transform: translateY(-2px);
    }
    
    .navbar .dropdown-menu {
      background-color: #ffffff;
      border: 1px solid rgba(91, 141, 238, 0.1);
      border-radius: 10px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }
    
    .navbar .dropdown-item {
      color: #2D3748;
      transition: all 0.2s ease;
    }
    
    .navbar .dropdown-item:hover {
      background-color: #F7FAFC;
      color: #5B8DEE;
      padding-left: 1.8rem;
    }
    
    .navbar .dropdown-item.text-danger:hover {
      background-color: #FFE5E5;
      color: #FF6B6B;
    }
  </style>
</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php">
        <i class="fas fa-chalkboard-teacher me-2"></i>Telemedicine 
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
              aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">
              <i class="fas fa-home me-1"></i> Home
            </a>
          </li>
          
          
          
          <?php if(isset($_SESSION['semail'])): ?>
            <li class="nav-item">
              <a class="nav-link active" href="mydoctors.php">
                <i class="fas fa-book-open me-2"></i> My Appointments
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link active" href="myprescriptions.php">
                <i class="fas fa-prescription-bottle me-1"></i> Prescriptions
              </a>
            </li>
            
          
          <?php endif; ?>
        </ul>
        
        <ul class="navbar-nav ms-auto">
          <?php if(!isset($_SESSION['semail'])): ?>
             <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle active" href="#" id="loginDropdown" role="button" 
                 data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-sign-in-alt me-1"></i> Signup
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                <li><a class="dropdown-item" href="patientsignup.php">Patient Signup</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="doctorsignup.php">Doctor Signup</a></li>
              </ul>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle active" href="#" id="loginDropdown" role="button" 
                 data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-sign-in-alt me-1"></i> Login
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                <li><a class="dropdown-item" href="patientlogin.php">Patient Login</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="doctorlogin.php">Doctor Login</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle active" href="#" id="profileDropdown" role="button" 
                 data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle me-1"></i> My Account
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>

              

                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
              </ul>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  

  

  <!-- JavaScript CDNs -->
  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
          crossorigin="anonymous"></script>
  

</body>
</html>