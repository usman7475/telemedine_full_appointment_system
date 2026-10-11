<?php include 'conn.php'; ?> 
<?php include 'navbar.php'; ?> 

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Patient Sign Up - Telemedicine</title>
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
padding: 40px 0;
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

.signup-container {
background: white;
border-radius: 15px;
box-shadow: 0 10px 30px rgba(0,0,0,0.1);
padding: 40px;
margin-bottom: 50px;
}

.form-section-title {
font-size: 1.3rem;
color: var(--dark-color);
margin-bottom: 25px;
padding-bottom: 15px;
border-bottom: 3px solid var(--primary-color);
font-weight: 600;
}

.form-group-row {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 20px;
margin-bottom: 20px;
}

.form-group-row.full {
grid-template-columns: 1fr;
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

.form-group input,
.form-group select,
.form-group textarea {
border: 2px solid #e0e0e0;
border-radius: 8px;
padding: 12px 15px;
font-family: 'Poppins', sans-serif;
font-size: 0.95rem;
transition: all 0.3s ease;
width: 100%;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
outline: none;
border-color: var(--primary-color);
box-shadow: 0 0 0 3px rgba(91, 141, 238, 0.15);
}

.form-group textarea {
resize: vertical;
min-height: 100px;
}

.submit-btn {
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

.submit-btn:hover {
transform: translateY(-2px);
box-shadow: 0 10px 25px rgba(91, 141, 238, 0.3);
}

.submit-btn:active {
transform: translateY(0);
}

.login-link {
text-align: center;
margin-top: 25px;
padding-top: 25px;
border-top: 1px solid #e0e0e0;
}

.login-link p {
color: #7f8c8d;
margin-bottom: 10px;
}

.login-link a {
color: var(--primary-color);
text-decoration: none;
font-weight: 600;
transition: color 0.3s ease;
}

.login-link a:hover {
color: var(--dark-color);
text-decoration: underline;
}

@media (max-width: 768px) {
.form-group-row {
grid-template-columns: 1fr;
}

.hero-section h1 {
font-size: 2rem;
}

.signup-container {
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
<h1><i class="fas fa-user-md me-2"></i>Patient Registration</h1>
<p class="lead">Join our telemedicine platform and connect with qualified doctors</p>
</div>
</div>
</div>
</div>

<!-- Signup Form -->
<div class="container">
<div class="row">
<div class="col-lg-6 mx-auto">
<div class="signup-container">
<h2 class="form-section-title"><i class="fas fa-clipboard-list me-2"></i>Personal Information</h2>

<form method="POST" action="" enctype="multipart/form-data">

<!-- Name and Email -->
<div class="form-group-row">
<div class="form-group">
<label for="name"><i class="fas fa-user"></i> Full Name</label>
<input type="text" class="form-control" id="name" placeholder="Enter your full name" name="name" required="required">
</div>
<div class="form-group">
<label for="email"><i class="fas fa-envelope"></i> Email Address</label>
<input type="email" class="form-control" id="email" placeholder="Enter your email" name="email" required="required">
</div>
</div>

<!-- Phone and Age -->
<div class="form-group-row">
<div class="form-group">
<label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
<input type="number" class="form-control" id="phone" placeholder="Enter your phone number" name="phone" required="required">
</div>
<div class="form-group">
<label for="age"><i class="fas fa-calendar"></i> Age</label>
<input type="number" class="form-control" id="age" placeholder="Enter your age" name="age" required="required">
</div>
</div>

<!-- Gender and Address -->
<div class="form-group-row">
<div class="form-group">
<label for="gender"><i class="fas fa-venus-mars"></i> Gender</label>
<select class="form-control" id="gender" name="gender" required="required">
<option value="">Select Gender</option>
<option value="Male">Male</option>
<option value="Female">Female</option>
<option value="Other">Other</option>
</select>
</div>
<div class="form-group">
<label for="contact"><i class="fas fa-map-marker-alt"></i> Contact Address</label>
<input type="text" class="form-control" id="contact" placeholder="Enter your address" name="contact" required="required">
</div>
</div>

<!-- Medical History -->
<div class="form-group-row full">
<div class="form-group">
<label for="medical_history"><i class="fas fa-notes-medical"></i> Medical History</label>
<textarea class="form-control" id="medical_history" placeholder="Enter any relevant medical 
conditions, allergies, or medications..." name="medical_history"></textarea>
</div>
</div>

<!-- Password -->
<div class="form-group-row full">
<div class="form-group">
<label for="password"><i class="fas fa-lock"></i> Password</label>
<input type="password" class="form-control" id="password" placeholder="Enter a strong password" name="password" required="required">
</div>
</div>

<!-- Submit Button -->
<button type="submit" class="submit-btn" name="submit">
<i class="fas fa-user-check"></i> Complete Registration
</button>

<!-- Login Link -->
<div class="login-link">
<p>Already have an account?</p>
<a href="patientlogin.php"><i class="fas fa-sign-in-alt me-1"></i>Sign In Instead</a>
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
 
  
  // If upload button is clicked ...

  if (isset($_POST['submit'])) {


  
    $name = $_POST["name"];
       $email= $_POST["email"];
       
        $phone= $_POST["phone"];
        $age= $_POST["age"];
        $gender= $_POST["gender"];
        $contact= $_POST["contact"];
        $medical_history= $_POST["medical_history"];
        $password= $_POST["password"];
   

  

        // Get all the submitted data from the form
        
 $rgt = "INSERT INTO patient (name,email,password,phone,age,gender,contact,medical_history) 
 VALUES ('$name','$email','$password','$phone','$age','$gender','$contact','$medical_history')";

        if (mysqli_query($conn ,$rgt)) {

          echo  "<script>

      window.location.href='patientsignup.php'
      alert('Registration Successfully!');

</script>
   ";
        }            
    

  }
?>
