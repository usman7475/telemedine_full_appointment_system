<?php 
include '../conn.php'; 
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Telemedicine </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/8a2bdd4e42.js" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #f8f9fa;
    }

    .header-bar {
      background-color: #343a40;
      color: white;
      padding: 20px;
      font-size: 24px;
      font-weight: bold;
      text-align: center;
    }

    .login-container {
      margin-top: 100px;
      background-color: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    }

    .login-btn {
      background-color: #dc3545;
      color: white;
      border: none;
      font-weight: bold;
      border-radius: 25px;
      padding: 10px;
    }

    .login-btn:hover {
      background-color: #c82333;
    }

    .form-label-custom {
      font-weight: bold;
      color: #dc3545;
    }
  </style>
</head>

<body>

  <div class="header-bar">Telemedicine  - Admin Panel</div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="login-container">
          <h3 class="text-center mb-4">Admin Login</h3>

          <form method="POST" action="">
            <div class="form-group">
              <label class="form-label-custom">Email</label>
              <input type="email" class="form-control" name="email" placeholder="Enter your email" required >
            </div>

            <div class="form-group">
              <label class="form-label-custom">Password</label>
              <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" name="submit" class="btn login-btn btn-block">LOGIN</button>
          </form>

        </div>
      </div>
    </div>
  </div>

</body>
</html>

<?php
if (isset($_POST['submit'])) {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $query = "SELECT * FROM admin_login WHERE email = '$email' AND password = '$password'";
  $result = mysqli_query($conn, $query);

  if ($row = mysqli_fetch_assoc($result)) {
    $_SESSION['yid'] = $row['id'];
    $_SESSION['yemail'] = $row['email'];
    $_SESSION['ypassword'] = $row['password'];

    echo '<script>window.location.href="home.php";</script>';
  } else {
    echo '<script>alert("Incorrect Email or Password!"); window.location.href="index.php";</script>';
  }
}
?>
