<?php
include '../conn.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['temail'])) {
    header('location:../index.php');
    exit;
}

$current_user_id = $_SESSION['tid']; // Doctor ID
$success_message = '';
$error_message = '';

// Handle adding new prescription
if (isset($_POST['add_prescription'])) {
    $patient_id = $_POST['patient_id'];
    $prescriptions = $_POST['medicines'];

    if (!empty($patient_id) && !empty($prescriptions)) {
        $all_success = true;

        foreach ($prescriptions as $med) {
            if (!empty($med['medicine'])) {
                $medicine = stripslashes(htmlspecialchars($med['medicine']));
                $dosage = stripslashes(htmlspecialchars($med['dosage']));
                $duration = stripslashes(htmlspecialchars($med['duration']));
                $frequency = stripslashes(htmlspecialchars($med['frequency']));
                $instructions = stripslashes(htmlspecialchars($med['instructions']));

                $stmt = $conn->prepare("INSERT INTO prescriptions (doctorid, patientid, medicine, dosage, duration, frequency, instructions) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iisssss", $current_user_id, $patient_id, $medicine, $dosage, $duration, $frequency, $instructions);

                if (!$stmt->execute()) {
                    $all_success = false;
                    $error_message = "Error saving prescription. Please try again.";
                }
                $stmt->close();
            }
        }

        if ($all_success) {
            $success_message = "Prescriptions added successfully!";
        }
    } else {
        $error_message = "Please select a patient and add at least one medicine.";
    }
}

// Handle deleting prescription
if (isset($_GET['delete'])) {
    $pid = intval($_GET['delete']);

    // Verify this prescription belongs to the current doctor
    $verify_stmt = $conn->prepare("SELECT * FROM prescriptions WHERE pid = ? AND doctorid = ?");
    $verify_stmt->bind_param("ii", $pid, $current_user_id);
    $verify_stmt->execute();
    $verify_result = $verify_stmt->get_result();

    if ($verify_result->num_rows > 0) {
        $delete_stmt = $conn->prepare("DELETE FROM prescriptions WHERE pid = ?");
        $delete_stmt->bind_param("i", $pid);
        if ($delete_stmt->execute()) {
            $success_message = "Prescription deleted successfully!";
        } else {
            $error_message = "Error deleting prescription.";
        }
        $delete_stmt->close();
    }
    $verify_stmt->close();
}

// Fetch enrolled patients
$patient_query = "SELECT e.`eid`, e.`sid`, e.`doctorid`, p.name, p.email, p.phone, p.age, p.gender, p.contact, p.medical_history
                  FROM `enroll` e
                  INNER JOIN patient p ON e.sid = p.sid
                  WHERE e.doctorid = ?
                  ORDER BY p.name ASC";
$patient_stmt = $conn->prepare($patient_query);
$patient_stmt->bind_param("i", $current_user_id);
$patient_stmt->execute();
$patients_result = $patient_stmt->get_result();
$enrolled_patients = [];

while ($patient = $patients_result->fetch_assoc()) {
    $enrolled_patients[] = $patient;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --dark-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-bg: #ecf0f1;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
        }

        .prescription-container {
            margin-top: 80px;
            margin-left: 0;
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2980b9 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }

        .patient-card {
            background: white;
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .patient-card:hover {
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
            transform: translateX(5px);
        }

        .patient-card.active {
            background: var(--light-bg);
            border-left-color: var(--secondary-color);
        }

        .medicine-row {
            background: #fbfcfd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }

        .btn-add-medicine {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-add-medicine:hover {
            background: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-remove-medicine {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-remove-medicine:hover {
            background: #c0392b;
        }

        .btn-save-prescription {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-save-prescription:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .form-control-custom {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control-custom:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .patient-details {
            background: #f0f7ff;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid var(--primary-color);
            margin: 20px 0;
        }

        .patient-details strong {
            color: var(--dark-color);
        }

        .prescriptions-history {
            margin-top: 30px;
        }

        .prescription-item {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            border-left: 4px solid var(--secondary-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .prescription-item .btn-delete {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .prescription-item .btn-delete:hover {
            background: #c0392b;
        }

        .no-patients-message {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }

        .section-title {
            color: var(--dark-color);
            font-weight: 600;
            margin: 20px 0 15px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary-color);
            font-size: 20px;
        }

        .prescription-card-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .prescription-container {
                padding: 10px;
            }

            .patient-card {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php include 'sidebar.php'; ?>   
        </div>

        <div class="col-md-10" style="margin-top: 20px;">
        
            <div class="prescription-container">
        <!-- Success/Error Messages -->
        <?php if (!empty($success_message)): ?>
            <div class="alert-success">
                <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="row">
            <!-- Patients List -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header-custom">
                        <i class="fas fa-users me-2"></i> Your Patients
                    </div>
                    <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                        <?php if (count($enrolled_patients) > 0): ?>
                            <div id="patients-list">
                                <?php foreach ($enrolled_patients as $patient): ?>
                                    <div class="patient-card" onclick="selectPatient(<?php echo htmlspecialchars(json_encode($patient)); ?>)">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <strong> <?php echo htmlspecialchars($patient['name']); ?></strong><br>
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($patient['email']); ?><br>
                                                    <i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($patient['phone']); ?>
                                                </small>
                                            </div>
                                            <i class="fas fa-chevron-right text-primary"></i>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-patients-message">
                                <i class="fas fa-inbox me-2"></i> No enrolled patients yet
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Prescription Form -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header-custom">
                        <i class="fas fa-prescription-bottle-alt me-2"></i> Add Prescription
                    </div>
                    <div class="card-body">
                        <div id="patient-info" style="display: none;">
                            <div class="patient-details">
                                <h6><i class="fas fa-user-circle me-2"></i> Patient Information</h6>
                                <div id="patient-details-content"></div>
                            </div>

                            <div class="h6 section-title">
                                <i class="fas fa-pills"></i>Medicines
                            </div>

                            <form id="prescription-form" method="POST" action="">
                                <input type="hidden" name="patient_id" id="patient_id">
                                <div id="medicines-container"></div>

                                <button type="button" class="btn-add-medicine" onclick="addMedicineRow()">
                                    <i class="fas fa-plus me-2"></i>Add Medicine
                                </button>

                                <button type="submit" name="add_prescription" class="btn-save-prescription" style="margin-top: 20px;">
                                    <i class="fas fa-save me-2"></i> Save Prescription
                                </button>
                            </form>
                        </div>

                        <div id="no-patient-selected" style="text-align: center; color: #999; padding: 40px 20px;">
                            <i class="fas fa-hand-point-left" style="font-size: 40px; margin-bottom: 10px;"></i>
                            <p>Select a patient to add prescription</p>
                        </div>
                    </div>
                </div>

                <!-- Prescription History -->
                <div class="prescriptions-history">
                    <div class="card shadow-sm">
                        <div class="card-header-custom">
                            <i class="fas fa-history me-2"></i> Recent Prescriptions
                        </div>
                        <div class="card-body">
                            <div id="prescriptions-history-container">
                                <?php
                                $history_query = "SELECT pr.pid, pr.medicine, pr.dosage, pr.duration, pr.frequency, 
                                                  pr.instructions, pr.created_at, p.name, p.email 
                                                  FROM prescriptions pr
                                                  INNER JOIN patient p ON pr.patientid = p.sid
                                                  WHERE pr.doctorid = ?
                                                  ORDER BY pr.created_at DESC LIMIT 10";
                                $history_stmt = $conn->prepare($history_query);
                                $history_stmt->bind_param("i", $current_user_id);
                                $history_stmt->execute();
                                $history_result = $history_stmt->get_result();

                                if ($history_result->num_rows > 0):
                                    while ($row = $history_result->fetch_assoc()):
                                ?>
                                        <div class="prescription-item">
                                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                                <div>
                                                    <strong><?php echo htmlspecialchars($row['medicine']); ?></strong><br>
                                                    <small class="text-muted">
                                                        Patient: <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                                                        Dosage: <?php echo htmlspecialchars($row['dosage']); ?> | 
                                                        Duration: <?php echo htmlspecialchars($row['duration']); ?><br>
                                                        <i class="fas fa-calendar me-1"></i> <?php echo date('d M, Y H:i', strtotime($row['created_at'])); ?>
                                                    </small>
                                                </div>
                                                <a href="?delete=<?php echo $row['pid']; ?>" 
                                                   class="prescription-item .btn-delete" 
                                                   onclick="return confirm('Are you sure?')" 
                                                   style="background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 12px;">
                                                    <i class="fas fa-trash me-1"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                <?php
                                    endwhile;
                                else:
                                    echo '<div class="text-center text-muted" style="padding: 20px;">No prescriptions yet</div>';
                                endif;
                                $history_stmt->close();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        let medicineCount = 0;

        function selectPatient(patient) {
            // Update selected patient
            document.getElementById('patient_id').value = patient.sid;

            // Display patient details
            let detailsHTML = `
                <strong><i class="fas fa-user me-2"></i>${escapeHtml(patient.name)}</strong><br>
                <small class="text-muted">
                    Email: ${escapeHtml(patient.email)}<br>
                    Phone: ${escapeHtml(patient.phone)}<br>
                    Age: ${patient.age || 'N/A'} | Gender: ${escapeHtml(patient.gender || 'N/A')}<br>
                    Contact: ${escapeHtml(patient.contact || 'N/A')}<br>
                    Medical History: ${escapeHtml(patient.medical_history || 'N/A')}
                </small>
            `;
            document.getElementById('patient-details-content').innerHTML = detailsHTML;

            // Show form
            document.getElementById('patient-info').style.display = 'block';
            document.getElementById('no-patient-selected').style.display = 'none';

            // Clear medicines container
            medicineCount = 0;
            document.getElementById('medicines-container').innerHTML = '';
            addMedicineRow();

            // Highlight selected patient
            document.querySelectorAll('.patient-card').forEach(card => {
                card.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
        }

        function addMedicineRow() {
            medicineCount++;
            const html = `
                <div class="medicine-row" id="medicine-${medicineCount}">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label"><strong>Medicine Name</strong></label>
                            <input type="text" name="medicines[${medicineCount}][medicine]" class="form-control form-control-custom" placeholder="e.g., Paracetamol" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label"><strong>Dosage</strong></label>
                            <input type="text" name="medicines[${medicineCount}][dosage]" class="form-control form-control-custom" placeholder="e.g., 500mg">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label"><strong>Frequency</strong></label>
                            <select name="medicines[${medicineCount}][frequency]" class="form-control form-control-custom">
                                <option value="">Select Frequency</option>
                                <option value="Once daily">Once daily</option>
                                <option value="Twice daily">Twice daily</option>
                                <option value="Three times daily">Three times daily</option>
                                <option value="Four times daily">Four times daily</option>
                                <option value="Every 4 hours">Every 4 hours</option>
                                <option value="Every 6 hours">Every 6 hours</option>
                                <option value="As needed">As needed</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label"><strong>Duration</strong></label>
                            <input type="text" name="medicines[${medicineCount}][duration]" class="form-control form-control-custom" placeholder="e.g., 7 days">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label"><strong>Instructions</strong></label>
                            <textarea name="medicines[${medicineCount}][instructions]" class="form-control form-control-custom" rows="2" placeholder="e.g., Take with food, avoid dairy products"></textarea>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn-remove-medicine" onclick="removeMedicineRow(${medicineCount})">
                                <i class="fas fa-trash me-1"></i>Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('medicines-container').insertAdjacentHTML('beforeend', html);
        }

        function removeMedicineRow(id) {
            const element = document.getElementById(`medicine-${id}`);
            if (element) {
                element.remove();
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
