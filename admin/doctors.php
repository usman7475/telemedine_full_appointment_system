<?php include 'sidebar.php'; ?>
<?php include '../conn.php'; ?>


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

    .container-main {
        margin-top: 100px;
        margin-left: 20px;
        margin-right: 20px;
        margin-bottom: 40px;
    }

    .page-title {
        color: var(--dark-color);
        font-weight: 700;
        margin-bottom: 30px;
        font-size: 1.8rem;
    }

    .doctor-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .doctor-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .doctor-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
    }

    .doctor-info h5 {
        color: var(--dark-color);
        font-weight: 600;
        margin-bottom: 5px;
    }

    .doctor-info-row {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin-bottom: 3px;
    }

    .badge-specialty {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        background: #e8f4f8;
        color: #0369a1;
    }

    .stats-row {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
        font-size: 0.85rem;
        color: #7f8c8d;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .btn-delete {
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-delete:hover {
        background: #c0392b;
        color: white;
        text-decoration: none;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #7f8c8d;
    }

    .alert {
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>

<div class="container-main">
    <div class="page-title">
        <i class="fas fa-user-md me-2"></i>Doctor Management
    </div>

    <?php
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        $doctor_id = intval($_GET['id']);

        if ($action === 'delete') {
            // Check if doctor exists
            $doctor_check = mysqli_query($conn, "SELECT id, name FROM doctor WHERE id = '$doctor_id'");
            
            if (mysqli_num_rows($doctor_check) == 0) {
                echo '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Doctor not found!</div>';
            } else {
                $doctor_data = mysqli_fetch_array($doctor_check);
                
                // Delete doctor - cascading deletes will handle:
                // - feedback records (feedback_ibfk_1)
                // - notification records (notifications_ibfk_1)
                // - prescription records (prescriptions_ibfk_1)
                // - enroll records need manual handling
                
                // First delete enrollments
                $delete_enrollments = mysqli_query($conn, "DELETE FROM enroll WHERE doctorid = '$doctor_id'");
                
                // Then delete doctor (cascading deletes handle notifications, feedback, prescriptions)
                $delete_result = mysqli_query($conn, "DELETE FROM doctor WHERE id = '$doctor_id'");
                
                if ($delete_enrollments && $delete_result) {
                    echo '<div class="alert alert-danger"><i class="fas fa-trash me-2"></i>Doctor <strong>' . htmlspecialchars($doctor_data['name']) . '</strong> account and all related data have been permanently deleted!</div>';
                } else {
                    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Error deleting doctor: ' . mysqli_error($conn) . '</div>';
                }
            }
        }
    }
    ?>

    <!-- Statistics -->
    <div class="row" style="margin-bottom: 30px;">
        <?php
        $total_doctors_query = "SELECT COUNT(*) as count FROM doctor";
        $specialties_query = "SELECT COUNT(DISTINCT specialty) as count FROM doctor WHERE specialty IS NOT NULL AND specialty != ''";
        $total_appointments_query = "
        SELECT COUNT(*) as count FROM enroll WHERE doctorid IN (SELECT id FROM doctor)";

        $total_doctors = mysqli_fetch_array(mysqli_query($conn, $total_doctors_query));
        $specialties_count = mysqli_fetch_array(mysqli_query($conn, $specialties_query));
        $total_appointments = mysqli_fetch_array(mysqli_query($conn, $total_appointments_query));
        ?>

        <div class="col-md-4">
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 5px solid var(--primary-color);">
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary-color);"><?php echo $total_doctors['count']; ?></div>
                <div style="color: #7f8c8d; margin-top: 5px;">Total Doctors</div>
            </div>
        </div>

        <div class="col-md-4">
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 5px solid var(--secondary-color);">
                <div style="font-size: 2rem; font-weight: 700; color: var(--secondary-color);"><?php echo $specialties_count['count']; ?></div>
                <div style="color: #7f8c8d; margin-top: 5px;">Active Specialties</div>
            </div>
        </div>

        <div class="col-md-4">
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 5px solid #f39c12;">
                <div style="font-size: 2rem; font-weight: 700; color: #f39c12;"><?php echo $total_appointments['count']; ?></div>
                <div style="color: #7f8c8d; margin-top: 5px;">Total Appointments</div>
            </div>
        </div>
    </div>

    <!-- Doctors List -->
    <div>
        <?php
        $doctors_query = "SELECT * FROM doctor ORDER BY id DESC";
        $doctors_result = mysqli_query($conn, $doctors_query);

        if (mysqli_num_rows($doctors_result) > 0) {
            while ($doctor = mysqli_fetch_array($doctors_result)) {
                // Get doctor's appointment count
                $appt_query = "SELECT COUNT(*) as count FROM enroll WHERE doctorid = '{$doctor['id']}'";
                $appt_result = mysqli_fetch_array(mysqli_query($conn, $appt_query));
                $appointment_count = $appt_result['count'];

                echo "
                <div class='doctor-card'>
                    <div class='doctor-header'>
                        <div class='doctor-info'>
                            <h5>{$doctor['name']}</h5>
                            <div class='doctor-info-row'><i class='fas fa-stethoscope me-2'></i>{$doctor['specialty']}</div>
                            <div class='doctor-info-row'><i class='fas fa-envelope me-2'></i>{$doctor['email']}</div>
                            <div class='doctor-info-row'><i class='fas fa-phone me-2'></i>{$doctor['mobile']}</div>
                            <div class='doctor-info-row'><i class='fas fa-certificate me-2'></i>{$doctor['qualification']}</div>
                            <div class='doctor-info-row'><i class='fas fa-briefcase me-2'></i>{$doctor['experience']} years experience</div>
                            <div class='doctor-info-row'><i class='fas fa-map-marker-alt me-2'></i>{$doctor['address']}</div>
                            
                            <div class='stats-row'>
                                <i class='fas fa-calendar-check me-2'></i>Total Appointments: <strong>{$appointment_count}</strong>
                            </div>
                        </div>
                        <div>
                            <span class='badge-specialty'>{$doctor['specialty']}</span>
                        </div>
                    </div>

                    <div class='doctor-info-row' style='margin-top: 10px; color: #34495e;'>
                        <strong>Fee:</strong> Rs. {$doctor['fee']}
                    </div>

                    <div class='action-buttons'>
                        <a href='?action=delete&id={$doctor['id']}' class='btn-delete' onclick=\"return confirm('Delete this doctor account? This action cannot be undone.')\"><i class='fas fa-trash me-1'></i>Delete Account</a>
                    </div>
                </div>
                ";
            }
        } else {
            echo '<div class="no-data"><i class="fas fa-inbox fa-3x mb-3" style="color: #ddd;"></i><p>No doctors registered yet</p></div>';
        }
        ?>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

            </main>
        </div>
    </div>
</body>
</html>
