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

        .report-card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-top: 4px solid var(--primary-color);
        }

        .report-title {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .report-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #ecf0f1;
        }

        .report-item:last-child {
            border-bottom: none;
        }

        .report-item-label {
            color: #7f8c8d;
            font-size: 0.95rem;
        }

        .report-item-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .progress-bar-custom {
            height: 8px;
            margin-top: 5px;
            border-radius: 10px;
        }

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .section-title {
            color: var(--dark-color);
            font-weight: 600;
            margin: 30px 0 20px 0;
            font-size: 1.3rem;
        }

        .top-doctor-card {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            border-left: 5px solid var(--primary-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .doctor-rank {
            background: linear-gradient(135deg, var(--primary-color), #2980b9);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            margin-right: 15px;
        }

        .doctor-info {
            flex: 1;
        }

        .doctor-name {
            color: var(--dark-color);
            font-weight: 600;
        }

        .doctor-specialty {
            color: #7f8c8d;
            font-size: 0.85rem;
        }

        .doctor-stat {
            color: var(--dark-color);
            font-weight: 600;
        }

        table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        table th {
            background: linear-gradient(135deg, var(--primary-color), #2980b9);
            color: white;
            font-weight: 600;
            border: none;
        }

        table td {
            padding: 15px;
            border-color: #ecf0f1;
        }
    </style>

<div class="container-main">
    <div class="page-title">
        <i class="fas fa-file-alt me-2"></i>Reports & Analytics
    </div>

    <!-- Key Metrics -->
    <div class="row" style="margin-bottom: 30px;">
        <?php
        $total_doctors = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count FROM doctor"))['count'];
        $total_patients = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count FROM patient"))['count'];
        $total_appointments = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count FROM enroll"))['count'];
        $avg_rating = mysqli_fetch_array(mysqli_query($conn, "SELECT AVG(rating) as avg FROM feedback"))['avg'];
        ?>

        <div class="col-md-3">
            <div class="stat-box">
                <div class="stat-icon" style="color: var(--primary-color);"><i class="fas fa-user-md"></i></div>
                <div class="stat-number"><?php echo $total_doctors; ?></div>
                <div class="stat-label">Registered Doctors</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-box">
                <div class="stat-icon" style="color: var(--secondary-color);"><i class="fas fa-users"></i></div>
                <div class="stat-number"><?php echo $total_patients; ?></div>
                <div class="stat-label">Registered Patients</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-box">
                <div class="stat-icon" style="color: #f39c12;"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-number"><?php echo $total_appointments; ?></div>
                <div class="stat-label">Total Appointments</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-box">
                <div class="stat-icon" style="color: #e74c3c;"><i class="fas fa-star"></i></div>
                <div class="stat-number"><?php echo round($avg_rating, 1); ?>/5</div>
                <div class="stat-label">Average Rating</div>
            </div>
        </div>
    </div>

    <!-- System Usage Report -->
    <div class="report-card">
        <div class="report-title"><i class="fas fa-chart-bar me-2"></i>System Usage Report</div>
        <?php
        $appointments_approved = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count FROM enroll WHERE status = 'approved'"))['count'];
        $appointments_cancelled = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count FROM enroll WHERE status = 'cancelled'"))['count'];
        $total_feedback = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count FROM feedback"))['count'];
        $total_prescriptions = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count FROM prescriptions"))['count'];

        $total_appt = $appointments_approved + $appointments_cancelled;
        $approval_rate = $total_appt > 0 ? round(($appointments_approved / $total_appt) * 100, 1) : 0;
        ?>

        <div class="report-item">
            <span class="report-item-label">Total Appointments</span>
            <span class="report-item-value"><?php echo $total_appt; ?></span>
        </div>

        <div class="report-item">
            <span class="report-item-label">Approved Appointments</span>
            <span class="report-item-value" style="color: var(--secondary-color);"><?php echo $appointments_approved; ?></span>
        </div>

        <div class="report-item">
            <span class="report-item-label">Cancelled Appointments</span>
            <span class="report-item-value" style="color: var(--accent-color);"><?php echo $appointments_cancelled; ?></span>
        </div>

        <div class="report-item">
            <span class="report-item-label">Approval Rate</span>
            <span class="report-item-value"><?php echo $approval_rate; ?>%</span>
        </div>
        <div class="progress-bar-custom" style="background: #ecf0f1;">
            <div class="progress-bar-custom" style="width: <?php echo $approval_rate; ?>%; background: linear-gradient(135deg, var(--secondary-color), #27ae60); height: 100%;"></div>
        </div>

        <div class="report-item">
            <span class="report-item-label">Total Feedback Received</span>
            <span class="report-item-value" style="color: var(--primary-color);"><?php echo $total_feedback; ?></span>
        </div>

        <div class="report-item">
            <span class="report-item-label">Total Prescriptions Issued</span>
            <span class="report-item-value" style="color: #f39c12;"><?php echo $total_prescriptions; ?></span>
        </div>
    </div>

    <!-- Top Doctors by Rating -->
    <div class="section-title"><i class="fas fa-award me-2"></i>Top Doctors by Rating</div>
    <div>
        <?php
        $top_doctors = "SELECT d.id, d.name, d.specialty, AVG(f.rating) as avg_rating, COUNT(f.fid) as feedback_count
                        FROM doctor d
                        LEFT JOIN feedback f ON d.id = f.doctorid
                        GROUP BY d.id
                        ORDER BY avg_rating DESC
                        LIMIT 5";
        $top_doctors_result = mysqli_query($conn, $top_doctors);
        $rank = 1;

        if (mysqli_num_rows($top_doctors_result) > 0) {
            while ($doctor = mysqli_fetch_array($top_doctors_result)) {
                $stars = !empty($doctor['avg_rating']) ? str_repeat('⭐', round($doctor['avg_rating'])) : 'No ratings';
                echo "
                <div class='top-doctor-card'>
                    <div style='display: flex; align-items: center;'>
                        <div class='doctor-rank'>#{$rank}</div>
                        <div class='doctor-info'>
                            <div class='doctor-name'>{$doctor['name']}</div>
                            <div class='doctor-specialty'>{$doctor['specialty']}</div>
                        </div>
                    </div>
                    <div style='text-align: right;'>
                        <div style='color: var(--primary-color); font-weight: 600; margin-bottom: 5px;'>{$stars}</div>
                        <div class='doctor-stat'>{$doctor['feedback_count']} ratings</div>
                    </div>
                </div>
                ";
                $rank++;
            }
        } else {
            echo '<p class="text-muted">No doctor data available</p>';
        }
        ?>
    </div>

    <!-- Top Specialties -->
    <div class="section-title"><i class="fas fa-stethoscope me-2"></i>Top Specialties by Appointments</div>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="30%">Specialty</th>
                    <th width="25%">Number of Doctors</th>
                    <th width="25%">Total Appointments</th>
                    <th width="20%">Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $specialties = "SELECT d.specialty, COUNT(DISTINCT d.id) as doctor_count, COUNT(e.eid) as appointment_count
                               FROM doctor d
                               LEFT JOIN enroll e ON d.id = e.doctorid
                               WHERE d.specialty != ''
                               GROUP BY d.specialty
                               ORDER BY appointment_count DESC
                               LIMIT 10";
                $specialties_result = mysqli_query($conn, $specialties);
                $total_appt_all = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count FROM enroll"))['count'];

                if (mysqli_num_rows($specialties_result) > 0) {
                    while ($specialty = mysqli_fetch_array($specialties_result)) {
                        $percentage = $total_appt_all > 0 ? round(($specialty['appointment_count'] / $total_appt_all) * 100, 1) : 0;
                        echo "
                        <tr>
                            <td><strong>{$specialty['specialty']}</strong></td>
                            <td>{$specialty['doctor_count']}</td>
                            <td>{$specialty['appointment_count']}</td>
                            <td>{$percentage}%</td>
                        </tr>
                        ";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Patient Engagement -->
    <div class="section-title"><i class="fas fa-chart-pie me-2"></i>Patient Engagement</div>
    <div class="report-card">
        <?php
        $active_patients = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(DISTINCT sid) as count FROM enroll"))['count'];
        $patients_with_feedback = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(DISTINCT patientid) as count FROM feedback"))['count'];
        $feedback_rate = $total_patients > 0 ? round(($patients_with_feedback / $total_patients) * 100, 1) : 0;
        ?>

        <div class="report-item">
            <span class="report-item-label">Total Patients</span>
            <span class="report-item-value"><?php echo $total_patients; ?></span>
        </div>

        <div class="report-item">
            <span class="report-item-label">Active Patients (with appointments)</span>
            <span class="report-item-value" style="color: var(--secondary-color);"><?php echo $active_patients; ?></span>
        </div>

        <div class="report-item">
            <span class="report-item-label">Patients with Feedback</span>
            <span class="report-item-value" style="color: var(--primary-color);"><?php echo $patients_with_feedback; ?></span>
        </div>

        <div class="report-item">
            <span class="report-item-label">Feedback Engagement Rate</span>
            <span class="report-item-value"><?php echo $feedback_rate; ?>%</span>
        </div>
        <div class="progress-bar-custom" style="background: #ecf0f1;">
            <div class="progress-bar-custom" style="width: <?php echo $feedback_rate; ?>%; background: linear-gradient(135deg, var(--primary-color), #2980b9); height: 100%;"></div>
        </div>

        <div class="report-item">
            <span class="report-item-label">Average Appointments per Patient</span>
            <span class="report-item-value"><?php echo $total_patients > 0 ? round($total_appointments / $total_patients, 2) : 0; ?></span>
        </div>
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
