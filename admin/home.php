<?php include '../conn.php'; ?>
<?php include 'sidebar.php'; ?>

<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2ecc71;
        --dark-color: #2c3e50;
        --accent-color: #e74c3c;
        --warning-color: #f39c12;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f5f5;
    }

    .dashboard-container {
            margin-top: 100px;
            margin-left: 20px;
            margin-right: 20px;
            margin-bottom: 40px;
        }

        .dashboard-title {
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 5px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .stat-card.green { border-left-color: var(--secondary-color); }
        .stat-card.orange { border-left-color: var(--warning-color); }
        .stat-card.red { border-left-color: var(--accent-color); }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .stat-card.green .stat-icon { color: var(--secondary-color); }
        .stat-card.orange .stat-icon { color: var(--warning-color); }
        .stat-card.red .stat-icon { color: var(--accent-color); }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 10px 0;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.95rem;
        }

        .activity-item {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .activity-item.appointment { border-left-color: var(--primary-color); }
        .activity-item.feedback { border-left-color: var(--secondary-color); }
        .activity-item.doctor { border-left-color: var(--warning-color); }

        .section-title {
            color: var(--dark-color);
            font-weight: 600;
            margin: 30px 0 20px 0;
            font-size: 1.3rem;
        }

        .quick-action-btn {
            background: linear-gradient(135deg, var(--primary-color), #2980b9);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
            margin-top: 5px;
        }

        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
            color: white;
            text-decoration: none;
        }
    </style>

<div class="dashboard-container">
    <!-- Dashboard Title -->
    <div class="dashboard-title">
        <i class="fas fa-chart-line me-2"></i>Admin Dashboard
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <?php
        // Total Doctors
        $doctors_query = "SELECT COUNT(*) as count FROM doctor";
        $doctors_result = mysqli_fetch_array(mysqli_query($conn, $doctors_query));
        $total_doctors = $doctors_result['count'];

        // Total Patients
        $patients_query = "SELECT COUNT(*) as count FROM patient";
        $patients_result = mysqli_fetch_array(mysqli_query($conn, $patients_query));
        $total_patients = $patients_result['count'];

        // Total Appointments
        $appointments_query = "SELECT COUNT(*) as count FROM enroll";
        $appointments_result = mysqli_fetch_array(mysqli_query($conn, $appointments_query));
        $total_appointments = $appointments_result['count'];

        // Total Feedback
        $feedback_query = "SELECT COUNT(*) as count FROM feedback";
        $feedback_result = mysqli_fetch_array(mysqli_query($conn, $feedback_query));
        $total_feedback = $feedback_result['count'];

        // Approved Appointments
        $approved_query = "SELECT COUNT(*) as count FROM enroll WHERE status = 'approved'";
        $approved_result = mysqli_fetch_array(mysqli_query($conn, $approved_query));
        $approved_appointments = $approved_result['count'];

        // Cancelled Appointments
        $cancelled_query = "SELECT COUNT(*) as count FROM enroll WHERE status = 'cancelled'";
        $cancelled_result = mysqli_fetch_array(mysqli_query($conn, $cancelled_query));
        $cancelled_appointments = $cancelled_result['count'];
        ?>

        <div class="col-md-3">
            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-user-md"></i></div>
                <div class="stat-number"><?php echo $total_doctors; ?></div>
                <div class="stat-label">Total Doctors</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card orange">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-number"><?php echo $total_patients; ?></div>
                <div class="stat-label">Total Patients</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-number"><?php echo $total_appointments; ?></div>
                <div class="stat-label">Total Appointments</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card red">
                <div class="stat-icon"><i class="fas fa-star"></i></div>
                <div class="stat-number"><?php echo $total_feedback; ?></div>
                <div class="stat-label">Total Feedback</div>
            </div>
        </div>
    </div>

    <!-- Appointment Statistics -->
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="color: var(--secondary-color);"><i class="fas fa-check-circle"></i></div>
                <div class="stat-number" style="color: var(--secondary-color);"><?php echo $approved_appointments; ?></div>
                <div class="stat-label">Approved Appointments</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="color: var(--accent-color);"><i class="fas fa-times-circle"></i></div>
                <div class="stat-number" style="color: var(--accent-color);"><?php echo $cancelled_appointments; ?></div>
                <div class="stat-label">Cancelled Appointments</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="section-title">Quick Actions</div>
    <div style="margin-bottom: 30px;">
        <a href="doctors.php" class="quick-action-btn">
            <i class="fas fa-check-circle me-2"></i>Doctor Verification
        </a>
        <a href="patients.php" class="quick-action-btn" style="background: linear-gradient(135deg, var(--warning-color), #e67e22);">
            <i class="fas fa-users me-2"></i>Patient Management
        </a>
        <a href="activity.php" class="quick-action-btn" style="background: linear-gradient(135deg, var(--secondary-color), #27ae60);">
            <i class="fas fa-chart-line me-2"></i>System Activity
        </a>
        <a href="reports.php" class="quick-action-btn" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
            <i class="fas fa-file-alt me-2"></i>Reports
        </a>
    </div>

    <!-- Recent Appointments -->
    <div class="section-title">Recent Appointments</div>
    <div>
        <?php
        $recent_appointments_query = "SELECT e.eid, e.status, e.created_at, p.name as patient_name, d.name as doctor_name
                                     FROM enroll e
                                     INNER JOIN patient p ON e.sid = p.sid
                                     INNER JOIN doctor d ON e.doctorid = d.id
                                     ORDER BY e.created_at DESC LIMIT 5";
        $recent_result = mysqli_query($conn, $recent_appointments_query);
        $count = 0;

        if (mysqli_num_rows($recent_result) > 0) {
            while ($row = mysqli_fetch_array($recent_result)) {
                $count++;
                $status_color = ($row['status'] === 'approved') ? 'success' : 'danger';
                echo "
                <div class='activity-item appointment'>
                    <strong>{$count}. {$row['patient_name']}</strong> → <strong>{$row['doctor_name']}</strong>
                    <br>
                    <small>Booked on: ".date('d M, Y H:i', strtotime($row['created_at']))."</small>
                    <br>
                    <span class='badge badge-{$status_color}' style='margin-top: 5px;'>".ucfirst($row['status'])."</span>
                </div>
                ";
            }
        } else {
            echo "<p class='text-muted'>No appointments yet.</p>";
        }
        ?>
    </div>

    <!-- Recent Feedback -->
    <div class="section-title">Recent Feedback</div>
    <div>
        <?php
        $recent_feedback_query = "SELECT f.fid, f.rating, f.message, f.created_at, p.name as patient_name, d.name as doctor_name
                                 FROM feedback f
                                 INNER JOIN patient p ON f.patientid = p.sid
                                 INNER JOIN doctor d ON f.doctorid = d.id
                                 ORDER BY f.created_at DESC LIMIT 5";
        $feedback_result = mysqli_query($conn, $recent_feedback_query);
        $count = 0;

        if (mysqli_num_rows($feedback_result) > 0) {
            while ($row = mysqli_fetch_array($feedback_result)) {
                $count++;
                $stars = str_repeat('⭐', $row['rating']);
                echo "
                <div class='activity-item feedback'>
                    <strong>{$count}. {$row['patient_name']}</strong> → <strong>{$row['doctor_name']}</strong>
                    <br>
                    <small>Rating: {$stars}</small>
                    <br>
                    <small>\"".substr($row['message'], 0, 50)."...\"</small>
                </div>
                ";
            }
        } else {
            echo "<p class='text-muted'>No feedback yet.</p>";
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
