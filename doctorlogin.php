<?php 
include 'navbar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Doctor Login - Telemedicine</title>
<style>
:root {
--primary-color: #5B8DEE;
--secondary-color: #6BCB77;
--dark-color: #2D3748;
--light-color: #F7FAFC;
--accent-color: #FF6B6B;
}

body {
font-family: 'Poppins', sans-serif;
background-color: #f8f9fa;
}

.hero-section {
background: linear-gradient(135deg, #5B8DEE 0%, #4A6FD9 50%, #3D5AA8 100%);
color: white;
padding: 60px 0;
margin-bottom: 40px;
box-shadow: 0 4px 12px rgba(91, 141, 238, 0.2);
}

.hero-section h1 {
font-size: 2.5rem;
font-weight: 700;
margin-bottom: 10px;
}

.hero-section p {
font-size: 1.1rem;
opacity: 0.9;
}

.login-container {
background: white;
border-radius: 15px;
box-shadow: 0 10px 30px rgba(0,0,0,0.1);
padding: 40px;
margin-bottom: 50px;
}

.login-card {
  margin-bottom: 40px;
background: white;
border-radius: 15px;
box-shadow: 0 10px 30px rgba(0,0,0,0.1);
padding: 40px;
}

.form-title {
font-size: 1.5rem;
color: var(--dark-color);
margin-bottom: 30px;
font-weight: 700;
text-align: center;
display: flex;
align-items: center;
justify-content: center;
gap: 10px;
}

.form-title i {
color: var(--primary-color);
font-size: 1.8rem;
}

.form-group {
margin-bottom: 20px;
}

.form-group label {
font-weight: 600;
color: var(--dark-color);
margin-bottom: 8px;
display: flex;
align-items: center;
gap: 8px;
font-size: 0.95rem;
}

.form-group label i {
color: var(--primary-color);
font-size: 1.1rem;
}

.form-group input {
border: 2px solid #e0e0e0;
border-radius: 8px;
padding: 12px 15px;
font-family: 'Poppins', sans-serif;
font-size: 0.95rem;
width: 100%;
transition: all 0.3s ease;
}

.form-group input:focus {
outline: none;
border-color: var(--primary-color);
box-shadow: 0 0 0 3px rgba(91, 141, 238, 0.15);
}

.login-btn {
width: 100%;
background: linear-gradient(135deg, #5B8DEE, #4A6FD9);
color: white;
border: none;
padding: 14px 30px;
font-size: 1rem;
font-weight: 700;
border-radius: 8px;
cursor: pointer;
transition: all 0.3s ease;
margin-top: 20px;
display: flex;
align-items: center;
justify-content: center;
gap: 10px;
}

.login-btn:hover {
transform: translateY(-2px);
box-shadow: 0 10px 25px rgba(91, 141, 238, 0.3);
color: white;
text-decoration: none;
}

.login-btn:active {
transform: translateY(0);
}

.signup-link {
text-align: center;
margin-top: 25px;
padding-top: 25px;
border-top: 1px solid #e0e0e0;
}

.signup-link p {
color: #7f8c8d;
margin-bottom: 10px;
font-size: 0.95rem;
}

.signup-link a {
color: var(--primary-color);
text-decoration: none;
font-weight: 600;
transition: color 0.3s ease;
}

.signup-link a:hover {
color: var(--dark-color);
text-decoration: underline;
}

@media (max-width: 768px) {
.hero-section h1 {
font-size: 2rem;
}

.login-card {
padding: 25px;
}
}
</style>
</head>
<body>

<!-- Hero Section -->
<div class="hero-section">
<div class="container">
<div class="row">
<div class="col-md-8 mx-auto text-center">
<h1><i class="fas fa-stethoscope me-2"></i>Doctor Login</h1>
<p class="lead">Access your professional dashboard and manage patient consultations</p>
</div>
</div>
</div>
</div>

<!-- Login Form -->
<div class="container">
<div class="row">
<div class="col-lg-5 mx-auto">
<div class="login-card">
<h2 class="form-title">
<i class="fas fa-sign-in-alt"></i> Welcome Back
</h2>

<form method="POST" action="">
<div class="form-group">
<label for="email"><i class="fas fa-envelope"></i> Email Address</label>
<input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
</div>

<div class="form-group">
<label for="password"><i class="fas fa-lock"></i> Password</label>
<input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
</div>

<button type="submit" name="submit" class="login-btn">
<i class="fas fa-sign-in-alt"></i> Login
</button>

<div class="signup-link">
<p>Don't have an account?</p>
<a href="doctorsignup.php"><i class="fas fa-user-plus me-1"></i>Register Here</a>
</div>
</form>
</div>
</div>
</div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

<?php
if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = "SELECT * FROM doctor WHERE email = '$email' AND password = '$password'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);

    $_SESSION['tid']       = $row['id'];
    $_SESSION['tname']     = $row['name'];
    $_SESSION['temail']    = $row['email'];
    $_SESSION['tpassword'] = $row['password'];

    echo '<script>window.location.href = "doctor/doctorprofile.php";</script>';
  } else {
    echo '<script>alert("Incorrect Email or Password!"); window.location.href = "doctorlogin.php";</script>';
  }
}
?>
