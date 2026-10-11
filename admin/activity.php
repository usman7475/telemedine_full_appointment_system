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

        .activity-item {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            border-left: 5px solid var(--primary-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .activity-item.appointment {
            border-left-color: var(--primary-color);
        }

        .activity-item.feedback {
            border-left-color: var(--secondary-color);
        }

        .activity-item.prescription {
            border-left-color: #f39c12;
        }

        .activity-content h6 {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 5px;
        }

        .activity-content p {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin: 0;
        }

        .activity-time {
            color: #7f8c8d;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 5px solid var(--primary-color);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.95rem;
            margin-top: 5px;
        }

        .section-title {
            color: var(--dark-color);
            font-weight: 600;
            margin: 30px 0 20px 0;
            font-size: 1.3rem;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }
    </style>

<div class="container-main">
    <div class="page-title">
        <i class="fas fa-chart-line me-2"></i>System Activity Monitoring
    </div>

    <!-- Activity Statistics -->
    <div class="row" style="margin-bottom: 30px;">
        <?php
        // Today's appointments
        $today_appt = "SELECT COUNT(*) as count FROM enroll WHERE DATE(created_at) = CURDATE()";
        $today_appt_result = mysqli_fetch_array(mysqli_query($conn, $today_appt));

        // Today's feedback
        $today_feedback = "SELECT COUNT(*) as count FROM feedback WHERE DATE(created_at) = CURDATE()";
        $today_feedback_result = mysqli_fetch_array(mysqli_query($conn, $today_feedback));

        // Today's prescriptions
        $today_prescription = "SELECT COUNT(*) as count FROM prescriptions WHERE DATE(created_at) = CURDATE()";
        $today_prescription_result = mysqli_fetch_array(mysqli_query($conn, $today_prescription));

        // This month's appointments
        $month_appt = "SELECT COUNT(*) as count FROM enroll WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())";
        $month_appt_result = mysqli_fetch_array(mysqli_query($conn, $month_appt));
        ?>

        <div class="col-md-3">
            <div class="stat-box" style="border-left-color: var(--primary-color);">
                <div class="stat-number" style="color: var(--primary-color);"><?php echo $today_appt_result['count']; ?></div>
                <div class="stat-label">Appointments Today</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-box" style="border-left-color: var(--secondary-color);">
                <div class="stat-number" style="color: var(--secondary-color);"><?php echo $today_feedback_result['count']; ?></div>
                <div class="stat-label">Feedback Today</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-box" style="border-left-color: #f39c12;">
                <div class="stat-number" style="color: #f39c12;"><?php echo $today_prescription_result['count']; ?></div>
                <div class="stat-label">Prescriptions Today</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-box" style="border-left-color: #9b59b6;">
                <div class="stat-number" style="color: #9b59b6;"><?php echo $month_appt_result['count']; ?></div>
                <div class="stat-label">Appointments This Month</div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments Activity -->
    <div class="section-title">
        <i class="fas fa-calendar-check me-2"></i>Recent Appointments
    </div>
    <div>
        <?php
        $appt_query = "SELECT e.eid, e.status, e.created_at, p.name as patient_name, d.name as doctor_name
                       FROM enroll e
                       INNER JOIN patient p ON e.sid = p.sid
                       INNER JOIN doctor d ON e.doctorid = d.id
                       ORDER BY e.created_at DESC LIMIT 10";
        $appt_result = mysqli_query($conn, $appt_query);

        if (mysqli_num_rows($appt_result) > 0) {
            while ($appt = mysqli_fetch_array($appt_result)) {
                echo "
                <div class='activity-item appointment'>
                    <div class='activity-content'>
                        <h6><i class='fas fa-calendar-check me-2'></i>{$appt['patient_name']} → {$appt['doctor_name']}</h6>
                        <p>Appointment " . ($appt['status'] === 'approved' ? 'Approved' : 'Cancelled') . "</p>
                    </div>
                    <div class='activity-time'>" . date('d M, Y H:i', strtotime($appt['created_at'])) . "</div>
                </div>
                ";
            }
        } else {
            echo '<div class="no-data"><p>No appointment activity</p></div>';
        }
        ?>
    </div>

    <!-- Recent Feedback Activity -->
    <div class="section-title" style="margin-top: 30px;">
        <i class="fas fa-star me-2"></i>Recent Feedback
    </div>
    <div>
        <?php
        $feedback_query = "SELECT f.fid, f.rating, f.created_at, p.name as patient_name, d.name as doctor_name
                          FROM feedback f
                          INNER JOIN patient p ON f.patientid = p.sid
                          INNER JOIN doctor d ON f.doctorid = d.id
                          ORDER BY f.created_at DESC LIMIT 10";
        $feedback_result = mysqli_query($conn, $feedback_query);

        if (mysqli_num_rows($feedback_result) > 0) {
            while ($feedback = mysqli_fetch_array($feedback_result)) {
                $stars = str_repeat('⭐', $feedback['rating']);
                echo "
                <div class='activity-item feedback'>
                    <div class='activity-content'>
                        <h6><i class='fas fa-star me-2'></i>{$feedback['patient_name']} rated {$feedback['doctor_name']}</h6>
                        <p>Rating: {$stars}</p>
                    </div>
                    <div class='activity-time'>" . date('d M, Y H:i', strtotime($feedback['created_at'])) . "</div>
                </div>
                ";
            }
        } else {
            echo '<div class="no-data"><p>No feedback activity</p></div>';
        }
        ?>
    </div>

    <!-- Recent Prescriptions Activity -->
    <div class="section-title" style="margin-top: 30px;">
        <i class="fas fa-prescription-bottle me-2"></i>Recent Prescriptions
    </div>
    <div>
        <?php
        $prescription_query = "SELECT pr.pid, pr.created_at, p.name as patient_name, d.name as doctor_name
                              FROM prescriptions pr
                              INNER JOIN patient p ON pr.patientid = p.sid
                              INNER JOIN doctor d ON pr.doctorid = d.id
                              ORDER BY pr.created_at DESC LIMIT 10";
        $prescription_result = mysqli_query($conn, $prescription_query);

        if (mysqli_num_rows($prescription_result) > 0) {
            while ($prescription = mysqli_fetch_array($prescription_result)) {
                echo "
                <div class='activity-item prescription'>
                    <div class='activity-content'>
                        <h6><i class='fas fa-prescription-bottle me-2'></i>{$prescription['doctor_name']} prescribed to {$prescription['patient_name']}</h6>
                        <p>Prescription Created</p>
                    </div>
                    <div class='activity-time'>" . date('d M, Y H:i', strtotime($prescription['created_at'])) . "</div>
                </div>
                ";
            }
        } else {
            echo '<div class="no-data"><p>No prescription activity</p></div>';
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
