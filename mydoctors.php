<?php include 'navbar.php'; ?>
<?php
if (!isset($_SESSION['semail'])) {
    header('location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tutors | Tutor Finder</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --accent-color: #e74c3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
        }
        
        .course-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            overflow: hidden;
            height: 100%;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .course-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        
        .course-body {
            padding: 20px;
        }
        
        .course-title {
            font-size: 1.2rem;
            color: var(--dark-color);
            margin-bottom: 10px;
        }
        
        .course-subject {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 15px;
        }
        
        .course-meta {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .quiz-btn {
            display: block;
            width: 100%;
            background-color: var(--secondary-color);
            color: white;
            text-align: center;
            padding: 8px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s ease;
            margin-top: 15px;
        }
        
        .quiz-btn:hover {
            background-color: #27ae60;
            color: white;
        }
        
        .no-courses {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 768px) {
            .course-card {
                margin-bottom: 20px;
            }
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h1><i class="fas fa-book-open me-2"></i>My Appointments</h1>
                <p class="lead">View all the appointments that you're choice</p>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div class="container">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success_message'];
             unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error']) && $_GET['error'] === 'appointment_cancelled'): ?>
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-info-circle me-2"></i>This appointment has been cancelled. Chat is unavailable.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<!-- Tutors Section -->
<div class="container">
    <div class="row">
       <?php
$suser_id = $_SESSION['sid'];

$slt7 = "SELECT * FROM enroll WHERE sid='$suser_id'";
$cnm7 = mysqli_query($conn, $slt7);

if (mysqli_num_rows($cnm7) > 0) {
    while ($row7 = mysqli_fetch_array($cnm7)) {
        $enroll_id = $row7['eid'];
        $patient_id = $row7['sid'];
        $doctor_id = $row7['doctorid'];

        // Fetch doctor details from doctor table
        $slt2 = "SELECT * FROM doctor WHERE id = '$doctor_id'";
        $cnm2 = mysqli_query($conn, $slt2);
        $row = mysqli_fetch_array($cnm2);

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

        <div class="col-md-4">
            <div class="course-card">
                <img src="admin/uploads/<?php echo $picture; ?>" alt="<?php echo $name; ?>" class="course-img">
                <div class="course-body">
                    <h5 class="course-title"><?php echo $name; ?></h5>
                    <div class="course-subject"><i class="fas fa-stethoscope me-2"></i><?php echo $specialty; ?></div>
                    
                    <div class="course-meta">
                        <i class="fas fa-certificate me-2"></i><?php echo $qualification; ?>
                    </div>
                    <div class="course-meta">
                        <i class="fas fa-briefcase me-2"></i><?php echo $experience; ?> years experience
                    </div>
                    <div class="course-meta">
                        <i class="fas fa-calendar-check me-2"></i><?php echo $availability; ?>
                    </div>
                    <div class="course-meta">
                        <i class="fas fa-clock me-2"></i><?php echo $schedule; ?>
                    </div>
                    <div class="course-meta">
                        <i class="fas fa-phone me-2"></i><?php echo $mobile; ?>
                    </div>
                    <div class="course-meta">
                        PKR <?php echo $fee; ?>/hr
                    </div>
                    
                    <?php
                    // Fetch appointment status
                    $status_query = "SELECT status FROM enroll WHERE eid = '$enroll_id' AND sid = '$patient_id'";
                    $status_result = mysqli_fetch_array(mysqli_query($conn, $status_query));
                    $appointment_status = $status_result['status'];
                    ?>
                    
                    <!-- Status Badge -->
                    <div style="margin-top: 15px; margin-bottom: 15px;">
                        <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; <?php echo ($appointment_status === 'approved') ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;'; ?>">
                            <i class="fas <?php echo ($appointment_status === 'approved') ? 'fa-check-circle' : 'fa-times-circle'; ?> me-1"></i>
                            <?php echo ucfirst($appointment_status); ?>
                        </span>
                    </div>
                    
                    <!-- Chat Button (only show if approved) -->
                    <?php if ($appointment_status === 'approved'): ?>
                        <a href="users_chat.php?doctor_id=<?php echo $doctor_id; ?>" class="quiz-btn">
                           <i class="fas fa-comments me-2"></i>Chat with Doctor
                        </a>
                    <?php else: ?>
                        <button class="quiz-btn" disabled style="background-color: #ccc; cursor: not-allowed;">
                           <i class="fas fa-comments me-2"></i>Chat Unavailable (Cancelled)
                        </button>
                    <?php endif; ?>
                    
                    <a href="feedback_form.php?doctor_id=<?php echo $doctor_id; ?>&doctor_name=<?php echo urlencode($name); ?>" class="quiz-btn" style="background-color: #e67e22; margin-top: 10px;">
                       <i class="fas fa-star me-2"></i>Give Feedback
                    </a>
                    
                    <!-- Cancel Button (only show if approved) -->
                    <?php if ($appointment_status === 'approved'): ?>
                        <a href="cancel_appointment.php?eid=<?php echo $enroll_id; ?>" class="quiz-btn" style="background-color: #e74c3c; margin-top: 10px;" onclick="return confirm('Are you sure you want to cancel this appointment?');">
                           <i class="fas fa-times-circle me-2"></i>Cancel Appointment
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php 
            }
        } else {
        ?>
        <div class="col-12">
            <div class="no-courses">
                <i class="fas fa-user-md fa-4x mb-4" style="color: #ddd;"></i>
                <h3>No Appointments</h3>
                <p class="text-muted">You haven't booked any Appointments yet. Browse our doctors to get started!</p>
                <a href="index.php" class="btn btn-primary mt-3">
                    <i class="fas fa-search me-2"></i>Find Doctors
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<br>
<br>
<?php include 'footer.php'; ?>


</body>
</html>