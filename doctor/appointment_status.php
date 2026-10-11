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

// Handle appointment status update
if (isset($_POST['update_status'])) {
    $eid = intval($_POST['eid']);
    $new_status = $_POST['status'];
    
    if (!in_array($new_status, ['approved', 'cancelled'])) {
        $error_message = "Invalid status";
    } else {
        // Verify ownership
        $verify_query = "SELECT e.*, p.name, p.email, p.phone FROM enroll e 
                        INNER JOIN patient p ON e.sid = p.sid 
                        WHERE e.eid = '$eid' AND e.doctorid = '$current_doctor_id'";
        $verify_result = mysqli_query($conn, $verify_query);
        
        if (mysqli_num_rows($verify_result) > 0) {
            $enrollment = mysqli_fetch_array($verify_result);
            
            // Update status
            $update_query = "UPDATE enroll SET status = '$new_status' WHERE eid = '$eid'";
            if (mysqli_query($conn, $update_query)) {
                // Create notification
                $message = ($new_status === 'approved') ? "Your appointment has been approved" : 
                "Your appointment has been cancelled";
                $type = ($new_status === 'approved') ? 'appointment_approved' : 'appointment_cancelled';
                
                $notif_query = "INSERT INTO notifications (doctorid, eid, patient_name, patient_email, patient_phone, message, type) 
                               VALUES ('$current_doctor_id', '$eid', '{$enrollment['name']}', '{$enrollment['email']}', '{$enrollment['phone']}', '$message', '$type')";
                mysqli_query($conn, $notif_query);
                
                $success_message = "Appointment status updated successfully!";
            } else {
                $error_message = "Error updating appointment status.";
            }
        } else {
            $error_message = "Appointment not found or unauthorized.";
        }
    }
}

// Fetch all appointments for this doctor
$appointments_query = "SELECT e.eid, e.status, e.created_at, p.name as patient_name, p.email as patient_email, 
                              p.phone as patient_phone, p.age, p.gender
                       FROM enroll e
                       INNER JOIN patient p ON e.sid = p.sid
                       WHERE e.doctorid = '$current_doctor_id'
                       ORDER BY e.created_at DESC";
$appointments_result = mysqli_query($conn, $appointments_query);
$total_appointments = mysqli_num_rows($appointments_result);

// Count by status
$approved_query = "SELECT COUNT(*) as count FROM enroll WHERE doctorid = '$current_doctor_id' AND status = 'approved'";
$cancelled_query = "SELECT COUNT(*) as count FROM enroll WHERE doctorid = '$current_doctor_id' AND status = 'cancelled'";
$approved_result = mysqli_fetch_array(mysqli_query($conn, $approved_query));
$cancelled_result = mysqli_fetch_array(mysqli_query($conn, $cancelled_query));
$approved_count = $approved_result['count'];
$cancelled_count = $cancelled_result['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Status - Doctor Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --dark-color: #2c3e50;
            --accent-color: #e74c3c;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
        }

        .appointments-container {
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

        .appointment-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 5px solid var(--secondary-color);
        }

        .appointment-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .appointment-header {
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

        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .appointment-meta {
            display: flex;
            gap: 20px;
            margin: 15px 0;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .status-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-status {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-approve {
            background: var(--secondary-color);
            color: white;
        }

        .btn-approve:hover {
            background: #27ae60;
            color: white;
            text-decoration: none;
        }

        .btn-cancel {
            background: var(--accent-color);
            color: white;
        }

        .btn-cancel:hover {
            background: #c0392b;
            color: white;
            text-decoration: none;
        }

        .no-appointments {
            background: white;
            border-radius: 10px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .no-appointments i {
            font-size: 60px;
            color: #ddd;
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

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
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
            <div class="appointments-container">
                <!-- Title -->
                <div class="title-header">
                    <h5><i class="fas fa-calendar-check me-2"></i> Appointment Management</h5>
                    <p>Manage and update appointment status</p>
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
                <?php if ($total_appointments > 0): ?>
                    <div class="stats-header">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $total_appointments; ?></div>
                            <div class="stat-label">Total Appointments</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" style="color: var(--secondary-color);"><?php echo $approved_count; ?></div>
                            <div class="stat-label">Approved</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" style="color: var(--accent-color);"> <?php echo $cancelled_count; ?></div>
                            <div class="stat-label">Cancelled</div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Appointments List -->
                <?php if ($total_appointments > 0): ?>
                    <?php while ($appointment = mysqli_fetch_array($appointments_result)): ?>
                        <div class="appointment-card">
                            <div class="appointment-header">
                                <div class="patient-info">
                                    <div class="patient-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="patient-details">
                                        <h5><?php echo htmlspecialchars($appointment['patient_name']); ?></h5>
                                        <small><i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($appointment['patient_email']); ?></small><br>
                                        <small><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($appointment['patient_phone']); ?></small><br>
                                        <small><i class="fas fa-user me-1"></i> <?php echo $appointment['age']; ?> years | <?php echo htmlspecialchars($appointment['gender']); ?></small>
                                    </div>
                                </div>
                                <div class="status-badge <?php echo ($appointment['status'] === 'approved') ? 'status-approved' : 'status-cancelled'; ?>">
                                    <i class="fas <?php echo ($appointment['status'] === 'approved') ? 'fa-check-circle' : 'fa-times-circle'; ?> me-1"></i>
                                    <?php echo ucfirst($appointment['status']); ?>
                                </div>
                            </div>

                            <div class="appointment-meta">
                                <span><i class="fas fa-calendar me-1"></i><?php echo date('d M, Y H:i', strtotime($appointment['created_at'])); ?></span>
                            </div>

                            <div class="status-actions">
                                <?php if ($appointment['status'] !== 'approved'): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="eid" value="<?php echo $appointment['eid']; ?>">
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" name="update_status" class="btn-status btn-approve">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <?php if ($appointment['status'] !== 'cancelled'): ?>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Cancel this appointment?')">
                                        <input type="hidden" name="eid" value="<?php echo $appointment['eid']; ?>">
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" name="update_status" class="btn-status btn-cancel">
                                            <i class="fas fa-times me-1"></i> Cancel
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-appointments">
                        <i class="fas fa-inbox"></i>
                        <h3>No Appointments Yet</h3>
                        <p>You don't have any appointments yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
