<?php include 'navbar.php'; ?>

<?php
if (!isset($_SESSION['semail'])) {
    header('location: index.php');
    exit;
}

$current_user_id = $_SESSION['sid'];
$doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
$doctor_name = isset($_GET['doctor_name']) ? htmlspecialchars($_GET['doctor_name']) : 'Doctor';

$success_message = '';
$error_message = '';

// Get patient info
$patient_query = "SELECT name, email FROM patient WHERE sid = '$current_user_id'";
$patient_result = mysqli_query($conn, $patient_query);
$patient_data = mysqli_fetch_array($patient_result);

// Handle form submission
if (isset($_POST['submit_feedback'])) {
    $rating = intval($_POST['rating']);
    $message = stripslashes(htmlspecialchars($_POST['message']));

    if ($rating < 1 || $rating > 5) {
        $error_message = "Please select a valid rating (1-5 stars)";
    } elseif (empty($message)) {
        $error_message = "Please write your feedback message";
    } else {
        // Check if feedback already exists for this patient-doctor pair
        $check_query = "SELECT fid FROM feedback WHERE doctorid = '$doctor_id' AND patientid = '$current_user_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // Update existing feedback
            $update_query = "UPDATE feedback SET rating = '$rating', message = '$message' WHERE doctorid = '$doctor_id' AND patientid = '$current_user_id'";
            if (mysqli_query($conn, $update_query)) {
                $success_message = "Feedback updated successfully!";
            } else {
                $error_message = "Error updating feedback. Please try again.";
            }
        } else {
            // Insert new feedback
            $insert_query = "INSERT INTO feedback (doctorid, patientid, rating, message) VALUES ('$doctor_id', '$current_user_id', '$rating', '$message')";
            if (mysqli_query($conn, $insert_query)) {
                $success_message = "Thank you! Your feedback has been submitted successfully!";
            } else {
                $error_message = "Error submitting feedback. Please try again.";
            }
        }
    }
}

// Fetch existing feedback if any
$existing_query = "SELECT * FROM feedback WHERE doctorid = '$doctor_id' AND patientid = '$current_user_id'";
$existing_result = mysqli_query($conn, $existing_query);
$existing_feedback = mysqli_fetch_array($existing_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give Feedback - Telemedicine</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        body {
            background-color: #ffffff;
            margin: 0;
            padding-top: 0;
        }

        .feedback-wrapper {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .star-rating {
            display: flex;
            gap: 10px;
            margin: 15px 0;
            font-size: 35px;
        }

        .star-rating .fa-star {
            cursor: pointer;
            color: #ddd;
            transition: color 0.3s ease;
        }

        .star-rating .fa-star:hover,
        .star-rating .fa-star.active {
            color: #ffc107;
        }

        .rating-value {
            text-align: center;
            color: #3498db;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .character-count {
            text-align: right;
            color: #7f8c8d;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
        }

        .info-box strong {
            color: #3498db;
        }
    </style>
</head>
<body>

<div class="feedback-wrapper">
<div class="container">
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card shadow rounded-3" style="margin-bottom: 50px;">
<div class="card-header text-center bg-light">
<h4 class="mb-0"><i class="fas fa-star me-2"></i>Give Feedback to Doctor</h4>
</div>
<div class="card-body">

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <div class="info-box">
        <strong><i class="fas fa-user-md me-2"></i>Doctor:</strong> <?php echo $doctor_name; ?><br><br>
        <strong><i class="fas fa-user me-2"></i>Your Name:</strong> <?php echo htmlspecialchars($patient_data['name']); ?>
    </div>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label"><strong><i class="fas fa-star me-2"></i>Your Rating</strong></label>
            <div class="rating-value">
                <span id="ratingDisplay">
                    <?php echo isset($existing_feedback) && $existing_feedback ? $existing_feedback['rating'] : '0'; ?>
                </span> Stars
            </div>
            <div class="star-rating" id="starRating">
                <?php $current_rating = isset($existing_feedback) && $existing_feedback ? $existing_feedback['rating'] : 0; ?>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?php if ($i <= $current_rating) echo 'active'; ?>" onclick="setRating(<?php echo $i; ?>)"></i>
                <?php endfor; ?>
            </div>
            <input type="hidden" name="rating" id="ratingInput" value="<?php echo $current_rating; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label"><strong><i class="fas fa-pen me-2"></i>Your Feedback Message</strong></label>
            <textarea name="message" id="message" class="form-control" required placeholder="Write your feedback here... (e.g., Doctor was very professional, timely appointment, good behavior, etc.)"><?php echo isset($existing_feedback) && $existing_feedback ? htmlspecialchars($existing_feedback['message']) : ''; ?></textarea>
            <div class="character-count">
                <span id="charCount">0</span> / 1000 characters
            </div>
        </div>

        <button type="submit" name="submit_feedback" class="btn btn-success">
            <i class="fas fa-paper-plane me-2"></i>
            <?php echo isset($existing_feedback) && $existing_feedback ? 'Update Feedback' : 'Submit Feedback'; ?>
        </button>
    </form>
</div>
</div>
</div>
</div>
</div>

<script>
    function setRating(stars) {
        document.getElementById('ratingInput').value = stars;
        document.getElementById('ratingDisplay').textContent = stars;
        
        const starElements = document.querySelectorAll('#starRating .fa-star');
        starElements.forEach((star, index) => {
            if (index < stars) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    // Character counter
    document.getElementById('message').addEventListener('input', function() {
        const count = this.value.length;
        if (count > 1000) {
            this.value = this.value.substring(0, 1000);
        }
        document.getElementById('charCount').textContent = this.value.length;
    });

    // Initialize character count on page load
    document.getElementById('charCount').textContent = document.getElementById('message').value.length;
</script>

<?php include 'footer.php'; ?>

</body>
</html>