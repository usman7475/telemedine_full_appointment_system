<?php include 'conn.php';  ?>  
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['semail'])) {
    // User is not logged in
    
        echo '<script >';
     echo 'alert("Please login first!");';
        echo 'window.location.href="index.php"';
        echo '</script>';
} else {
    // User is logged in, check cart status
     $doctorid  =$_GET['s'];
$suser_id =$_SESSION['sid'];

    
    $check_query = "SELECT * FROM enroll WHERE doctorid='$doctorid' AND sid='$suser_id'";

    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // if doctor is already selected
       
       echo '<script >';
     echo 'alert("You are Already Selected This Doctor!");';
        echo 'window.location.href="index.php"';
        echo '</script>';

    } else {
         
       
 $insert= "INSERT INTO enroll(sid,doctorid,status,created_at) VALUES ('$suser_id','$doctorid','approved',NOW())";

        if ($conn->query($insert)) {
            // Get the last inserted enrollment ID
            $eid_query = "SELECT eid FROM enroll WHERE sid = '$suser_id' AND doctorid = '$doctorid' ORDER BY created_at DESC LIMIT 1";
            $eid_result = $conn->query($eid_query);
            
            if ($eid_result && $eid_result->num_rows > 0) {
                $eid_row = $eid_result->fetch_assoc();
                $eid = $eid_row['eid'];
                
                // Get patient and doctor info for notification
                $patient_query = "SELECT name, email, phone FROM patient WHERE sid = '$suser_id'";
                $doctor_query = "SELECT name FROM doctor WHERE id = '$doctorid'";
                
                $patient_result = $conn->query($patient_query);
                $doctor_result = $conn->query($doctor_query);
                
                if ($patient_result->num_rows > 0 && $doctor_result->num_rows > 0) {
                    $patient = $patient_result->fetch_assoc();
                    $doctor = $doctor_result->fetch_assoc();
                    
                    // Create notification for doctor
                    $message = "New appointment from patient " . htmlspecialchars($patient['name']);
                    $notif_query = "INSERT INTO notifications (doctorid, eid, patient_name, patient_email, patient_phone, message, type, is_read, created_at) 
                                   VALUES ('$doctorid', '$eid', '{$patient['name']}', '{$patient['email']}', '{$patient['phone']}', '$message', 'new_appointment', 0, NOW())";
                    $conn->query($notif_query);
                }
            }
        }
        // Display a success message or perform any other necessary actions

      echo '<script >';
     echo 'alert("Shedule Successfully!");';
        echo 'window.location.href="mydoctors.php"';
        echo '</script>';


    }
}


?>
         