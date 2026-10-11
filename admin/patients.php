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

        .patient-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .patient-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .patient-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .patient-info h5 {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 5px;
        }

        .patient-info-row {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 3px;
        }

        .badge-gender {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            background: #dbeafe;
            color: #1e40af;
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

        .btn-view {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-view:hover {
            background: #2980b9;
            color: white;
            text-decoration: none;
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

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>

<div class="container-main">
    <div class="page-title">
        <i class="fas fa-users me-2"></i>Patient Management
    </div>

    <?php
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        $patient_id = intval($_GET['id']);

        if ($action === 'delete') {
            // Check if patient exists
            $patient_check = mysqli_query($conn, "SELECT sid, name FROM patient WHERE sid = '$patient_id'");
            
            if (mysqli_num_rows($patient_check) == 0) {
                echo '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Patient not found!</div>';
            } else {
                $patient_data = mysqli_fetch_array($patient_check);
                
                // Delete patient - cascading deletes will handle:
                // - feedback records (feedback_ibfk_2)
                // - prescription records (prescriptions_ibfk_2)
                // - enroll records need manual handling
                
                // First delete enrollments
                $delete_enrollments = mysqli_query($conn, "DELETE FROM enroll WHERE sid = '$patient_id'");
                
                // Then delete patient (cascading deletes handle prescriptions and feedback)
                $delete_result = mysqli_query($conn, "DELETE FROM patient WHERE sid = '$patient_id'");
                
                if ($delete_enrollments && $delete_result) {
                    echo '<div class="alert alert-danger"><i class="fas fa-trash me-2"></i>Patient <strong>' . htmlspecialchars($patient_data['name']) . '</strong> account and all related data have been permanently deleted!</div>';
                } else {
                    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Error deleting patient: ' . mysqli_error($conn) . '</div>';
                }
            }
        }
    }
    ?>

    <!-- Statistics -->
    <div class="row" style="margin-bottom: 30px;">
        <?php
        $total_patients_query = "SELECT COUNT(*) as count FROM patient";
        $male_patients_query = "SELECT COUNT(*) as count FROM patient WHERE gender = 'Male'";
        $female_patients_query = "SELECT COUNT(*) as count FROM patient WHERE gender = 'Female'";

        $total_patients = mysqli_fetch_array(mysqli_query($conn, $total_patients_query));
        $male_patients = mysqli_fetch_array(mysqli_query($conn, $male_patients_query));
        $female_patients = mysqli_fetch_array(mysqli_query($conn, $female_patients_query));
        ?>

        <div class="col-md-4">
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 5px solid var(--primary-color);">
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary-color);"><?php echo $total_patients['count']; ?></div>
                <div style="color: #7f8c8d; margin-top: 5px;">Total Patients</div>
            </div>
        </div>

        <div class="col-md-4">
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 5px solid var(--secondary-color);">
                <div style="font-size: 2rem; font-weight: 700; color: var(--secondary-color);"><?php echo $male_patients['count']; ?></div>
                <div style="color: #7f8c8d; margin-top: 5px;">Male Patients</div>
            </div>
        </div>

        <div class="col-md-4">
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 5px solid #ff69b4;">
                <div style="font-size: 2rem; font-weight: 700; color: #ff69b4;"><?php echo $female_patients['count']; ?></div>
                <div style="color: #7f8c8d; margin-top: 5px;">Female Patients</div>
            </div>
        </div>
    </div>

    <!-- Patients List -->
    <div>
        <?php
        $patients_query = "SELECT * FROM patient ORDER BY sid DESC";
        $patients_result = mysqli_query($conn, $patients_query);

        if (mysqli_num_rows($patients_result) > 0) {
            while ($patient = mysqli_fetch_array($patients_result)) {
                // Get patient's appointment count
                $appt_query = "SELECT COUNT(*) as count FROM enroll WHERE sid = '{$patient['sid']}'";
                $appt_result = mysqli_fetch_array(mysqli_query($conn, $appt_query));
                $appointment_count = $appt_result['count'];

                echo "
                <div class='patient-card'>
                    <div class='patient-header'>
                        <div class='patient-info'>
                            <h5>{$patient['name']}</h5>
                            <div class='patient-info-row'><i class='fas fa-envelope me-2'></i>{$patient['email']}</div>
                            <div class='patient-info-row'><i class='fas fa-phone me-2'></i>{$patient['phone']}</div>
                            <div class='patient-info-row'><i class='fas fa-birthday-cake me-2'></i>{$patient['age']} years old</div>
                            <div class='patient-info-row'><i class='fas fa-map-marker-alt me-2'></i>{$patient['contact']}</div>
                            
                            <div class='stats-row'>
                                <i class='fas fa-calendar-check me-2'></i>Total Appointments: <strong>{$appointment_count}</strong>
                            </div>
                        </div>
                        <div>
                            <span class='badge-gender'>{$patient['gender']}</span>
                        </div>
                    </div>

                    <div class='patient-info-row' style='margin-top: 10px; color: #34495e;'>
                        <strong>Medical History:</strong><br>
                        " . (!empty($patient['medical_history']) ? substr($patient['medical_history'], 0, 100) . "..." : "No medical history provided") . "
                    </div>

                    <div class='action-buttons'>
                        <a href='?action=delete&id={$patient['sid']}' class='btn-delete' onclick=\"return confirm('Delete this patient account? This action cannot be undone.')\"><i class='fas fa-trash me-1'></i>Delete Account</a>
                    </div>
                </div>
                ";
            }
        } else {
            echo '<div class="no-data"><i class="fas fa-inbox fa-3x mb-3" style="color: #ddd;"></i><p>No patients registered yet</p></div>';
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
