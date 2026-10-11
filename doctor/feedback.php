<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['temail'])) {
    header('location:../index.php');
    exit;
}

include '../conn.php';

$current_doctor_id = $_SESSION['tid'];
$success_message = '';
$error_message = '';

// Fetch all feedback for this doctor
$feedback_query = "SELECT f.fid, f.rating, f.message, f.created_at, p.name as patient_name, p.email as patient_email, p.phone as patient_phone, p.age, p.gender
                   FROM feedback f
                   INNER JOIN patient p ON f.patientid = p.sid
                   WHERE f.doctorid = '$current_doctor_id'
                   ORDER BY f.created_at DESC";
$feedback_result = mysqli_query($conn, $feedback_query);
$total_feedback = mysqli_num_rows($feedback_result);

// Calculate average rating
$avg_query = "SELECT AVG(rating) as avg_rating FROM feedback WHERE doctorid = '$current_doctor_id'";
$avg_result = mysqli_query($conn, $avg_query);
$avg_data = mysqli_fetch_array($avg_result);
$avg_rating = $avg_data['avg_rating'] ? round($avg_data['avg_rating'], 1) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Doctor Panel</title>
   
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --dark-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-bg: #ecf0f1;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
        }

        .feedback-container {
            margin-top: 90px;
            margin-left: 20px;
            margin-right: 20px;
        }

        .stats-header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 5px solid var(--primary-color);
        }

        .stat-item {
            display: inline-block;
            margin-right: 40px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.95rem;
        }

        .stars {
            color: #ffc107;
            font-size: 1.2rem;
        }

        .feedback-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 5px solid var(--secondary-color);
            transition: all 0.3s ease;
        }

        .feedback-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .patient-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .patient-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .patient-details h5 {
            margin: 0;
            color: var(--dark-color);
            font-weight: 600;
        }

        .patient-details small {
            color: #7f8c8d;
        }

        .feedback-rating {
            background: #fff3cd;
            padding: 8px 12px;
            border-radius: 6px;
            border-left: 4px solid #ffc107;
        }

        .feedback-rating .stars {
            margin-right: 8px;
        }

        .feedback-message {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            color: #2c3e50;
            line-height: 1.6;
            border-left: 3px solid var(--primary-color);
        }

        .feedback-meta {
            display: flex;
            gap: 20px;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .btn-delete {
            display: none;
        }

        .no-feedback {
            background: white;
            border-radius: 10px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .no-feedback i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .no-feedback h3 {
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .no-feedback p {
            color: #7f8c8d;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .title-header {
            margin-bottom: 30px;
        }

        .title-header h2 {
            color: var(--dark-color);
            font-weight: 700;
        }

        .title-header p {
            color: #7f8c8d;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php include 'sidebar.php'; ?>   
        </div>

        <div class="col-md-10">
            <div class="feedback-container">
                <!-- Title -->
                <div class="title-header">
                    <h5><i class="fas fa-comments me-2"></i> Feedback & Reviews</h5>
                    <p>View feedback and ratings from your patients</p>
                </div>

                <!-- Success/Error Messages -->
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Statistics -->
                <?php if ($total_feedback > 0): ?>
                    <div class="stats-header">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $total_feedback; ?></div>
                            <div class="stat-label">Total Feedback</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">
                                <span class="stars">
                                    <?php 
                                    for ($i = 0; $i < floor($avg_rating); $i++) {
                                        echo '<i class="fas fa-star"></i>';
                                    }
                                    if ($avg_rating - floor($avg_rating) >= 0.5) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    }
                                    ?>
                                </span>
                                <?php echo $avg_rating; ?>
                            </div>
                            <div class="stat-label">Average Rating</div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Feedback List -->
                <?php if ($total_feedback > 0): ?>
                    <?php while ($feedback = mysqli_fetch_array($feedback_result)): ?>
                        <div class="feedback-card">
                            <div class="feedback-header">
                                <div class="patient-info">
                                    <div class="patient-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="patient-details">
                                        <h5><?php echo htmlspecialchars($feedback['patient_name']); ?></h5>
                                        <small><i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($feedback['patient_email']); ?></small><br>
                                        <small><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($feedback['patient_phone']); ?></small><br>
                                        <small><i class="fas fa-user me-1"></i> <?php echo $feedback['age']; ?> years | <?php echo htmlspecialchars($feedback['gender']); ?></small>
                                    </div>
                                </div>
                            </div>

                            <div class="feedback-rating">
                                <span class="stars">
                                    <?php 
                                    for ($i = 0; $i < $feedback['rating']; $i++) {
                                        echo '<i class="fas fa-star"></i>';
                                    }
                                    for ($i = $feedback['rating']; $i < 5; $i++) {
                                        echo '<i class="fas fa-star" style="color: #ddd;"></i>';
                                    }
                                    ?>
                                </span>
                                <strong><?php echo $feedback['rating']; ?>/5</strong>
                            </div>

                            <div class="feedback-message">
                                <?php echo nl2br(htmlspecialchars($feedback['message'])); ?>
                            </div>

                            <div class="feedback-meta">
                                <span><i class="fas fa-calendar me-1"></i><?php echo date('d M, Y H:i', strtotime($feedback['created_at'])); ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-feedback">
                        <i class="fas fa-inbox"></i>
                        <h5>No Feedback Yet</h5>
                        <p>You haven't received any feedback from your patients yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
