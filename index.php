<?php include 'navbar.php'; ?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Telemedicine </title>
<style>
:root {
--primary-color: #5B8DEE;
--secondary-color: #6BCB77;
--dark-color: #2D3748;
--light-color: #F7FAFC;
--accent-color: #FF6B6B;
--soft-gray: #E8EDF2;
--text-primary: #2D3748;
--text-secondary: #718096;
--border-color: #E2E8F0;
}

* {
margin: 0;
padding: 0;
box-sizing: border-box;
}

body {
font-family: 'Segoe UI', 'Roboto', Tahoma, Geneva, Verdana, sans-serif;
background-color: var(--light-color);
color: var(--text-primary);
line-height: 1.6;
margin: 0;
padding: 0;
}

/* Hero Section */
.hero-section {
background: linear-gradient(135deg, #5B8DEE 0%, #4A6FD9 50%, #3D5AA8 100%);
color: white;
padding: 80px 0;
margin-bottom: 50px;
position: relative;
overflow: hidden;
}

.hero-section::before {
content: '';
position: absolute;
top: -50%;
right: -10%;
width: 400px;
height: 400px;
background: rgba(255,255,255,0.1);
border-radius: 50%;
}

.hero-section h1 {
font-size: 3.5rem;
font-weight: 700;
margin-bottom: 15px;
letter-spacing: -0.5px;
position: relative;
z-index: 1;
}

.hero-section .lead {
font-size: 1.3rem;
font-weight: 300;
opacity: 0.95;
position: relative;
z-index: 1;
}

/* Search Container */
.search-container {
background: white;
border-radius: 15px;
padding: 40px;
box-shadow: 0 10px 40px rgba(91, 141, 238, 0.08);
margin-bottom: 60px;
border: 1px solid var(--border-color);
}

.search-container .input-group {
box-shadow: 0 4px 15px rgba(91, 141, 238, 0.12);
border-radius: 10px;
overflow: hidden;
}

.search-container .form-control {
border: none;
padding: 15px 20px;
font-size: 1rem;
background-color: var(--light-color);
border: 1px solid var(--border-color);
}

.search-container .form-control:focus {
background-color: white;
border-color: var(--primary-color);
box-shadow: 0 0 0 3px rgba(91, 141, 238, 0.1);
}

.search-container .btn-primary {
background-color: var(--primary-color);
border: none;
padding: 15px 35px;
font-weight: 600;
transition: all 0.3s ease;
}

.search-container .btn-primary:hover {
background-color: #4A6FD9;
transform: translateX(2px);
}

.search-container .input-group-text {
padding: 0 15px;
font-size: 1.1rem;
color: var(--primary-color);
}

/* Section Title */
.section-title {
text-align: center;
margin-bottom: 50px;
position: relative;
}

.section-title h2 {
font-size: 2.2rem;
font-weight: 700;
color: var(--text-primary);
position: relative;
padding-bottom: 15px;
}

.section-title h2::after {
content: '';
position: absolute;
bottom: 0;
left: 50%;
transform: translateX(-50%);
width: 60px;
height: 4px;
background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
border-radius: 2px;
}

/* Doctor Card */
.tutor-card {
background: white;
border-radius: 12px;
overflow: hidden;
border: 1px solid var(--border-color);
box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
height: 100%;
display: flex;
flex-direction: column;
}

.tutor-card:hover {
transform: translateY(-8px);
box-shadow: 0 15px 40px rgba(91, 141, 238, 0.15);
border-color: var(--primary-color);
}

.tutor-img {
width: 100%;
height: 220px;
object-fit: cover;
background: var(--soft-gray);
position: relative;
}

.tutor-card:hover .tutor-img {
opacity: 0.95;
}

.tutor-info {
padding: 25px;
flex: 1;
display: flex;
flex-direction: column;
}

.tutor-name {
font-size: 1.4rem;
font-weight: 700;
color: var(--text-primary);
margin-bottom: 12px;
}

.tutor-meta {
color: var(--text-secondary);
margin-bottom: 10px;
font-size: 0.95rem;
display: flex;
align-items: center;
gap: 8px;
}

.tutor-meta i {
color: var(--primary-color);
width: 16px;
text-align: center;
}

.tutor-price {
font-weight: 700;
color: var(--primary-color);
font-size: 1.35rem;
margin-top: auto;
padding-top: 15px;
border-top: 1px solid var(--border-color);
margin-top: 15px;
}

/* Appointment Button */
.select-btn {
display: block;
width: 100%;
background: linear-gradient(135deg, var(--secondary-color), #5BC370);
color: white;
border: none;
padding: 12px;
border-radius: 8px;
font-weight: 600;
text-align: center;
transition: all 0.3s ease;
text-decoration: none;
margin-top: 15px;
cursor: pointer;
font-size: 1rem;
}

.select-btn:hover {
background: linear-gradient(135deg, #5BB76F, #4FB463);
transform: translateY(-2px);
box-shadow: 0 8px 20px rgba(107, 203, 119, 0.3);
color: white;
text-decoration: none;
}

.select-btn i {
margin-right: 8px;
}

/* Container Spacing */
.container {
padding: 0 15px;
}

.row {
margin: 0 -15px;
}

[class*='col-'] {
padding: 0 15px;
}

/* Alert Message */
.alert-warning {
background-color: #FFF5E6;
border: 1px solid #FFE5B4;
color: #8B6914;
border-radius: 10px;
padding: 20px;
}

/* Features Section */
.features-section {
padding: 80px 0;
background: white;
margin-bottom: 40px;
}

.feature-card {
background: white;
border-radius: 12px;
padding: 30px;
text-align: center;
border: 1px solid var(--border-color);
transition: all 0.3s ease;
height: 100%;
}

.feature-card:hover {
transform: translateY(-8px);
box-shadow: 0 15px 40px rgba(91, 141, 238, 0.15);
border-color: var(--primary-color);
}

.feature-icon {
font-size: 3rem;
color: var(--primary-color);
margin-bottom: 20px;
}

.feature-card h3 {
font-size: 1.3rem;
font-weight: 700;
color: var(--text-primary);
margin-bottom: 15px;
}

.feature-card p {
color: var(--text-secondary);
line-height: 1.8;
font-size: 0.95rem;
}

/* How It Works */
.how-works-section {
padding: 80px 0;
background: linear-gradient(135deg, rgba(91, 141, 238, 0.05), rgba(107, 203, 119, 0.05));
margin-bottom: 40px;
}

.step-card {
background: white;
border-radius: 12px;
padding: 40px 30px;
text-align: center;
border: 2px solid var(--border-color);
position: relative;
transition: all 0.3s ease;
}

.step-card:hover {
border-color: var(--primary-color);
box-shadow: 0 10px 30px rgba(91, 141, 238, 0.1);
}

.step-number {
display: inline-flex;
align-items: center;
justify-content: center;
width: 50px;
height: 50px;
background: linear-gradient(135deg, var(--primary-color), #4A6FD9);
color: white;
border-radius: 50%;
font-weight: 700;
font-size: 1.5rem;
margin-bottom: 20px;
}

.step-card h3 {
font-size: 1.2rem;
font-weight: 700;
color: var(--text-primary);
margin-bottom: 12px;
}

.step-card p {
color: var(--text-secondary);
font-size: 0.9rem;
}

/* Benefits Section */
.benefits-section {
padding: 80px 0;
background: white;
margin-bottom: 40px;
}

.benefit-item {
display: flex;
gap: 20px;
margin-bottom: 30px;
align-items: flex-start;
}

.benefit-icon {
font-size: 2rem;
color: var(--secondary-color);
flex-shrink: 0;
padding-top: 5px;
}

.benefit-content h4 {
font-size: 1.1rem;
font-weight: 700;
color: var(--text-primary);
margin-bottom: 8px;
}

.benefit-content p {
color: var(--text-secondary);
font-size: 0.95rem;
line-height: 1.7;
}

/* CTA Section */
.cta-section {
background: linear-gradient(135deg, #5B8DEE 0%, #4A6FD9 50%, #3D5AA8 100%);
color: white;
padding: 80px 0;
border-radius: 15px;
margin-bottom: 60px;
text-align: center;
}

.cta-section h2 {
font-size: 2.5rem;
font-weight: 700;
margin-bottom: 20px;
}

.cta-section p {
font-size: 1.1rem;
opacity: 0.95;
margin-bottom: 40px;
max-width: 600px;
margin-left: auto;
margin-right: auto;
}

.cta-buttons {
display: flex;
gap: 20px;
justify-content: center;
flex-wrap: wrap;
}

.cta-btn {
padding: 14px 35px;
font-size: 1rem;
font-weight: 700;
border-radius: 8px;
text-decoration: none;
transition: all 0.3s ease;
display: inline-flex;
align-items: center;
gap: 10px;
border: 2px solid white;
}

.cta-btn-primary {
background: white;
color: var(--primary-color);
}

.cta-btn-primary:hover {
transform: translateY(-2px);
box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
color: var(--primary-color);
text-decoration: none;
}

.cta-btn-secondary {
background: transparent;
color: white;
}

.cta-btn-secondary:hover {
background: rgba(255, 255, 255, 0.1);
transform: translateY(-2px);
color: white;
text-decoration: none;
}

/* Responsive */
@media (max-width: 768px) {
.hero-section h1 {
font-size: 2.5rem;
}

.hero-section .lead {
font-size: 1.1rem;
}

.search-container {
padding: 25px;
}

.section-title h2 {
font-size: 1.8rem;
}

.tutor-card {
margin-bottom: 20px;
}

.tutor-info {
padding: 20px;
}
}

@media (max-width: 576px) {
.hero-section {
padding: 50px 0;
}

.hero-section h1 {
font-size: 2rem;
}

.hero-section .lead {
font-size: 1rem;
}

.search-container {
padding: 20px;
margin-bottom: 40px;
}

.search-container .form-control,
.search-container .btn-primary {
padding: 12px 15px;
}
}
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="page-index">

<!-- Hero Section -->
<div class="hero-section">
<div class="container">
<div class="row">
<div class="col-md-8 mx-auto text-center">
<h1><i class="fas fa-heartbeat"></i> Your Health, Our Priority</h1>
<p class="lead">Connect with experienced doctors for expert medical consultation</p>
</div>
</div>
</div>
</div>

<!-- Search Section -->
<div class="container search-container">
<div class="row">
<div class="col-md-10 mx-auto">
<form method="GET">
<div class="input-group mb-3">
<span class="input-group-text" style="background: var(--light-color); border: 1px solid var(--border-color); border-right: none;">
<i class="fas fa-search"></i>
</span>
<input type="text" name="search" class="form-control form-control-lg" placeholder="Search by specialty, name, or qualification..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
<button class="btn btn-primary btn-lg" type="submit">
<i class="fas fa-arrow-right"></i> Find Doctor
</button>
</div>
</form>
</div>
</div>
</div>

<!--  Listing -->
<div class="container" style="margin-bottom: 60px;">
<div class="section-title">
<h2><i class="fas fa-stethoscope"></i> Available Doctors</h2>
</div>

<div class="row">
<?php
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$sql = "SELECT * FROM doctor";

if (!empty($search)) {
$sql .= " WHERE 
doctor.name LIKE '%$search%' 
OR doctor.email LIKE '%$search%' 
OR doctor.mobile LIKE '%$search%'
OR doctor.qualification LIKE '%$search%'
OR doctor.specialty LIKE '%$search%'
OR doctor.experience LIKE '%$search%'
OR doctor.address LIKE '%$search%'
OR doctor.availability LIKE '%$search%'
";
}

$res = mysqli_query($conn, $sql);

if (mysqli_num_rows($res) > 0) {
while ($row = mysqli_fetch_assoc($res)) {
$id = $row['id'];
$name = $row['name'];
$email = $row['email'];
$mobile = $row['mobile'];
$fee = $row['fee'];
$qualification = $row['qualification'];
$specialty = $row['specialty'];
$experience = $row['experience'];
$picture = $row['picture'];
$address = $row['address'];
$availability = $row['availability'];
$schedule = $row['schedule'];
?>
<div class="col-lg-4 col-md-6 col-sm-12" style="margin-bottom: 30px;">
<div class="tutor-card">

<img src="admin/uploads/<?php echo $picture; ?>" alt="<?php echo $name; ?>" class="tutor-img">

<div class="tutor-info">
<h3 class="tutor-name"><?php echo $name; ?></h3>

<div class="tutor-meta">
<i class="fas fa-stethoscope"></i> <strong><?php echo $specialty; ?></strong>
</div>

<div class="tutor-meta">
<i class="fas fa-certificate"></i> <?php echo $qualification; ?>
</div>

<div class="tutor-meta">
<i class="fas fa-briefcase"></i> <strong><?php echo $experience; ?> yrs</strong> experience
</div>

<div class="tutor-meta">
<i class="fas fa-clock"></i> <?php echo $schedule; ?>
</div>

<div class="tutor-meta">
<i class="fas fa-map-marker-alt"></i> <?php echo $address; ?>
</div>

<div class="tutor-price">
<i class="fas fa-tag"></i> PKR <?php echo number_format($fee); ?>/hr
</div>

<a href="check.php?s=<?php echo $id; ?>" class="select-btn">
<i class="fas fa-calendar-plus"></i> Book Appointment
</a>

</div>
</div>
</div>
<?php
}
} else {
echo '<div class="col-12"><div class="alert alert-warning text-center"><i class="fas fa-search"></i> <strong>No doctors found</strong> matching your search. Please try different keywords.</div></div>';
}
?>
</div>
</div>

<!-- Features Section -->
<div class="container">
<div class="features-section">
<div class="section-title">
<h2><i class="fas fa-sparkles"></i> Why Choose Us?</h2>
</div>

<div class="row">
<div class="col-lg-4 col-md-6">
<div class="feature-card">
<div class="feature-icon">
<i class="fas fa-video"></i>
</div>
<h3>Video Consultation</h3>
<p>Connect with doctors via secure video calls from the comfort of your home. High-quality, encrypted connections ensure your privacy.</p>
</div>
</div>

<div class="col-lg-4 col-md-6">
<div class="feature-card">
<div class="feature-icon">
<i class="fas fa-prescription-bottle"></i>
</div>
<h3>Digital Prescriptions</h3>
<p>Receive prescriptions directly in your account. Get medicines delivered to your doorstep with e-prescription support.</p>
</div>
</div>

<div class="col-lg-4 col-md-6">
<div class="feature-card">
<div class="feature-icon">
<i class="fas fa-clock"></i>
</div>
<h3>24/7 Availability</h3>
<p>Our doctors are available round the clock. Schedule appointments at your convenience and get instant consultations.</p>
</div>
</div>

<div class="col-lg-4 col-md-6">
<div class="feature-card">
<div class="feature-icon">
<i class="fas fa-shield-alt"></i>
</div>
<h3>Secure & Private</h3>
<p>Your medical data is protected with industry-leading encryption. We follow all HIPAA compliance standards.</p>
</div>
</div>

<div class="col-lg-4 col-md-6">
<div class="feature-card">
<div class="feature-icon">
<i class="fas fa-file-medical"></i>
</div>
<h3>Medical Records</h3>
<p>Access all your medical history, test reports, and previous consultations in one secure digital vault.</p>
</div>
</div>

<div class="col-lg-4 col-md-6">
<div class="feature-card">
<div class="feature-icon">
<i class="fas fa-wallet"></i>
</div>
<h3>Affordable Rates</h3>
<p>Competitive consultation fees without any hidden charges. Get quality healthcare at affordable prices.</p>
</div>
</div>
</div>
</div>
</div>

<!-- How It Works Section -->
<div class="container">
<div class="how-works-section">
<div class="section-title">
<h2><i class="fas fa-cogs"></i> How It Works?</h2>
</div>

<div class="row">
<div class="col-lg-3 col-md-6">
<div class="step-card">
<div class="step-number">1</div>
<h3>Create Account</h3>
<p>Sign up on our platform with your basic details. It only takes 2 minutes to create your account.</p>
</div>
</div>

<div class="col-lg-3 col-md-6">
<div class="step-card">
<div class="step-number">2</div>
<h3>Find Doctor</h3>
<p>Browse through our list of qualified doctors. Filter by specialty, experience, and availability.</p>
</div>
</div>

<div class="col-lg-3 col-md-6">
<div class="step-card">
<div class="step-number">3</div>
<h3>Book Appointment</h3>
<p>Select your preferred time slot and book an appointment. Get instant confirmation on your phone.</p>
</div>
</div>

<div class="col-lg-3 col-md-6">
<div class="step-card">
<div class="step-number">4</div>
<h3>Video Consultation</h3>
<p>Join the video call at the scheduled time. Get professional medical advice from our experienced doctors.</p>
</div>
</div>
</div>
</div>
</div>

<!-- Benefits Section -->
<div class="container">
<div class="benefits-section">
<div class="section-title">
<h2><i class="fas fa-heart"></i> Our Benefits</h2>
</div>

<div class="row">
<div class="col-lg-6">
<div class="benefit-item">
<div class="benefit-icon">
<i class="fas fa-users"></i>
</div>
<div class="benefit-content">
<h4>1000+ Qualified Doctors</h4>
<p>Access to a network of over 1000 experienced and certified doctors across various specialties.</p>
</div>
</div>

<div class="benefit-item">
<div class="benefit-icon">
<i class="fas fa-check-circle"></i>
</div>
<div class="benefit-content">
<h4>100% Verified Professionals</h4>
<p>All doctors are verified and have proper medical licenses. Your safety is our top priority.</p>
</div>
</div>

<div class="benefit-item">
<div class="benefit-icon">
<i class="fas fa-thumbs-up"></i>
</div>
<div class="benefit-content">
<h4>4.9/5 Rating</h4>
<p>Based on thousands of satisfied patient reviews. Join thousands of happy patients today.</p>
</div>
</div>
</div>

<div class="col-lg-6">
<div class="benefit-item">
<div class="benefit-icon">
<i class="fas fa-bolt"></i>
</div>
<div class="benefit-content">
<h4>Instant Appointments</h4>
<p>Get appointments within 24 hours. Same-day consultation available for urgent cases.</p>
</div>
</div>

<div class="benefit-item">
<div class="benefit-icon">
<i class="fas fa-mobile-alt"></i>
</div>
<div class="benefit-content">
<h4>Mobile App Available</h4>
<p>Download our app for iOS and Android. Consult on the go, anytime, anywhere.</p>
</div>
</div>

<div class="benefit-item">
<div class="benefit-icon">
<i class="fas fa-support"></i>
</div>
<div class="benefit-content">
<h4>24/7 Customer Support</h4>
<p>Our support team is always ready to help. Get assistance via chat, email, or phone.</p>
</div>
</div>
</div>
</div>
</div>

<!-- CTA Section -->
<div class="container" style="margin-bottom: 40px;">
<div class="cta-section">
<h2>Ready to Take Control of Your Health?</h2>
<p>Join thousands of patients who trust our platform for quality healthcare. Book your first consultation with an experienced doctor today.</p>
<div class="cta-buttons">
<a href="patientsignup.php" class="cta-btn cta-btn-primary">
<i class="fas fa-user-plus"></i> Sign Up Now
</a>
<a href="index.php" class="cta-btn cta-btn-secondary">
<i class="fas fa-stethoscope"></i> Find Doctor
</a>
</div>
</div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>


