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

// Mark notification as read
if (isset($_GET['read'])) {
    $nid = intval($_GET['read']);
    $update_query = "UPDATE notifications SET is_read = 1 WHERE nid = '$nid' AND doctorid = '$current_doctor_id'";
    mysqli_query($conn, $update_query);
    header("Location: notifications.php");
    exit;
}

// Delete notification
if (isset($_GET['delete'])) {
    $nid = intval($_GET['delete']);
    $verify_query = "SELECT * FROM notifications WHERE nid = '$nid' AND doctorid = '$current_doctor_id'";
    $verify_result = mysqli_query($conn, $verify_query);
    
    if (mysqli_num_rows($verify_result) > 0) {
        $delete_query = "DELETE FROM notifications WHERE nid = '$nid'";
        if (mysqli_query($conn, $delete_query)) {
            $success_message = "Notification deleted successfully!";
        } else {
            $error_message = "Error deleting notification.";
        }
    } else {
        $error_message = "Notification not found or unauthorized.";
    }
}

// Fetch all notifications
$notifications_query = "SELECT * FROM notifications WHERE doctorid = '$current_doctor_id' ORDER BY created_at DESC";
$notifications_result = mysqli_query($conn, $notifications_query);
$total_notifications = mysqli_num_rows($notifications_result);

// Count unread
$unread_query = "SELECT COUNT(*) as count FROM notifications WHERE doctorid = '$current_doctor_id' AND is_read = 0";
$unread_result = mysqli_fetch_array(mysqli_query($conn, $unread_query));
$unread_count = $unread_result['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Doctor Panel</title>
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

        .notifications-container {
            margin-top: 90px;
            margin-left: 20px;
            margin-right: 20px;
        }

        .notification-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 5px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .notification-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .notification-card.unread {
            background: #f0f8ff;
            border-left-color: var(--secondary-color);
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .notification-title {
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .notification-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-new-appointment {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-approved {
            background: #d4edda;
            color: #155724;
        }

        .badge-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .notification-patient {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin: 12px 0;
            border-left: 3px solid var(--primary-color);
        }

        .notification-patient strong {
            color: var(--primary-color);
        }

        .notification-patient small {
            display: block;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .notification-timestamp {
            font-size: 0.85rem;
            color: #7f8c8d;
        }

        .notification-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-read {
            background: var(--secondary-color);
            color: white;
        }

        .btn-read:hover {
            background: #27ae60;
            color: white;
            text-decoration: none;
        }

        .btn-delete {
            background: var(--accent-color);
            color: white;
        }

        .btn-delete:hover {
            background: #c0392b;
            color: white;
            text-decoration: none;
        }

        .no-notifications {
            background: white;
            border-radius: 10px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .no-notifications i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
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
            <div class="notifications-container">
                <!-- Title -->
                <div class="title-header">
                    <h5><i class="fas fa-bell me-2"></i> Notifications</h5>
                    <p>Stay updated with appointment notifications</p>
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
                <?php if ($total_notifications > 0): ?>
                    <div class="stats-header">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $total_notifications; ?></div>
                            <div class="stat-label">Total Notifications</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" style="color: var(--secondary-color);"><?php echo $unread_count; ?></div>
                            <div class="stat-label">Unread</div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Notifications List -->
                <?php if ($total_notifications > 0): ?>
                    <?php while ($notification = mysqli_fetch_array($notifications_result)): ?>
                        <div class="notification-card <?php echo $notification['is_read'] == 0 ? 'unread' : ''; ?>">
                            <div class="notification-header">
                                <div>
                                    <h5 class="notification-title"><?php echo htmlspecialchars($notification['message']); ?></h5>
                                </div>
                                <span class="notification-badge badge-<?php 
                                    if ($notification['type'] === 'new_appointment') echo 'new-appointment';
                                    elseif ($notification['type'] === 'appointment_approved') echo 'approved';
                                    elseif ($notification['type'] === 'appointment_cancelled') echo 'cancelled';
                                ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $notification['type'])); ?>
                                </span>
                            </div>

                            <div class="notification-patient">
                                <strong><i class="fas fa-user me-1"></i> <?php echo htmlspecialchars($notification['patient_name']); ?></strong><br>
                                <small><i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($notification['patient_email']); ?></small><br>
                                <small><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($notification['patient_phone']); ?></small>
                            </div>

                            <div class="notification-timestamp">
                                <i class="fas fa-clock me-1"></i> <?php echo date('d M, Y H:i', strtotime($notification['created_at'])); ?>
                            </div>

                            <div class="notification-actions">
                                <?php if ($notification['is_read'] == 0): ?>
                                    <a href="?read=<?php echo $notification['nid']; ?>" class="btn-action btn-read">
                                        <i class="fas fa-check me-1"></i> Mark as Read
                                    </a>
                                <?php endif; ?>
                                <a href="?delete=<?php echo $notification['nid']; ?>" class="btn-action btn-delete" onclick="return confirm('Delete this notification?')">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-notifications">
                        <i class="fas fa-inbox"></i>
                        <h5>No Notifications</h5>
                        <p>You don't have any notifications yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
