<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['semail'])) {
    header('location:index.php');
    exit;
}

include 'conn.php';

$patient_id = $_SESSION['sid'];
$eid = isset($_GET['eid']) ? intval($_GET['eid']) : 0;

// Verify the enrollment belongs to the patient and get doctor info
$verify_query = "SELECT e.*, d.name as doctor_name, d.email as doctor_email, p.name as patient_name, p.email as patient_email, p.phone as patient_phone 
                 FROM enroll e 
                 LEFT JOIN doctor d ON e.doctorid = d.id 
                 LEFT JOIN patient p ON e.sid = p.sid
                 WHERE e.eid = '$eid' AND e.sid = '$patient_id'";

$result = mysqli_query($conn, $verify_query);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error_message'] = "Appointment not found or you don't have permission to cancel it.";
    header("location:mydoctors.php");
    exit;
}

$enrollment = mysqli_fetch_array($result);

// Check if already cancelled
if ($enrollment['status'] === 'cancelled') {
    $_SESSION['error_message'] = "This appointment is already cancelled.";
    header("location:mydoctors.php");
    exit;
}

// Update appointment status to cancelled
$update_query = "UPDATE enroll SET status = 'cancelled' WHERE eid = '$eid' AND sid = '$patient_id'";

if (mysqli_query($conn, $update_query)) {
    // Create notification for doctor
    $doctorid = $enrollment['doctorid'];
    $patient_name = $enrollment['patient_name'];
    $patient_email = $enrollment['patient_email'];
    $patient_phone = $enrollment['patient_phone'];
    
    $message = "Appointment cancelled by patient " . htmlspecialchars($patient_name);
    
    $notification_query = "INSERT INTO notifications (doctorid, eid, patient_name, patient_email, patient_phone, message, type, is_read, created_at) 
                          VALUES ('$doctorid', '$eid', '$patient_name', '$patient_email', '$patient_phone', '$message', 'appointment_cancelled', 0, NOW())";
    
    mysqli_query($conn, $notification_query);
    
    $_SESSION['success_message'] = "Appointment cancelled successfully!";
} else {
    $_SESSION['error_message'] = "Error cancelling appointment. Please try again.";
}

header("location:mydoctors.php");
exit;
?>
