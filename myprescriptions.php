<?php
include 'conn.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['semail'])) {
    header('location: index.php');
    exit;
}

$current_patient_id = $_SESSION['sid'];

// Handle PDF Download
if (isset($_GET['download_pdf'])) {
    $pid = intval($_GET['download_pdf']);

    // Fetch prescription details
    $query = "SELECT pr.pid, pr.medicine, pr.dosage, pr.duration, pr.frequency, pr.instructions, 
                     pr.created_at, p.name as patient_name, p.email as patient_email, p.phone as patient_phone,
                     p.age, p.gender, d.name as doctor_name, d.email as doctor_email, d.mobile as doctor_mobile, 
                     d.specialty, d.qualification, d.fee
              FROM prescriptions pr
              INNER JOIN patient p ON pr.patientid = p.sid
              INNER JOIN doctor d ON pr.doctorid = d.id
              WHERE pr.pid = ? AND pr.patientid = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $pid, $current_patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Generate PDF content
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Prescription - <?php echo htmlspecialchars($row['patient_name']); ?></title>
            <style>
                * { margin: 0; padding: 0; }
                body { font-family: Arial, sans-serif; background: white; }
                .container { width: 900px; margin: auto; padding: 30px; }
                .header {
                    text-align: center;
                    border-bottom: 3px solid #3498db;
                    padding-bottom: 20px;
                    margin-bottom: 20px;
                }
                .header h1 { color: #2c3e50; font-size: 28px; }
                .header p { color: #7f8c8d; font-size: 14px; }
                
                .doctor-info {
                    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                    color: white;
                    padding: 15px;
                    border-radius: 5px;
                    margin-bottom: 20px;
                }
                .doctor-info h3 { margin-bottom: 10px; }
                .doctor-info p { margin: 5px 0; font-size: 13px; }
                
                .patient-info {
                    background: #ecf0f1;
                    padding: 15px;
                    border-radius: 5px;
                    margin-bottom: 20px;
                    border-left: 4px solid #e74c3c;
                }
                .patient-info h4 { color: #2c3e50; margin-bottom: 10px; }
                .patient-details {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 10px;
                    font-size: 13px;
                }
                .patient-details p { margin: 5px 0; }
                
                .prescription-box {
                    border: 2px solid #27ae60;
                    border-radius: 5px;
                    padding: 20px;
                    margin: 20px 0;
                }
                .prescription-box h4 {
                    color: #27ae60;
                    margin-bottom: 15px;
                    font-size: 16px;
                }
                
                .medicine-item {
                    background: #ffffff;
                    border-left: 4px solid #27ae60;
                    padding: 15px;
                    margin: 10px 0;
                    border-radius: 3px;
                }
                .medicine-item .medicine-name {
                    font-weight: bold;
                    font-size: 15px;
                    color: #2c3e50;
                }
                .medicine-item .medicine-details {
                    font-size: 13px;
                    color: #555;
                    margin-top: 8px;
                }
                .medicine-item .detail-row {
                    display: inline-block;
                    margin-right: 25px;
                    margin-top: 5px;
                }
                .medicine-item .detail-label {
                    font-weight: bold;
                    color: #2c3e50;
                }
                .medicine-item .instructions {
                    font-style: italic;
                    color: #7f8c8d;
                    margin-top: 8px;
                    padding: 8px;
                    background: #f8f9fa;
                    border-radius: 3px;
                }
                
                .date-issued {
                    text-align: right;
                    color: #7f8c8d;
                    font-size: 12px;
                    margin-top: 20px;
                }
                
                .footer {
                    text-align: center;
                    color: #95a5a6;
                    font-size: 12px;
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 1px solid #bdc3c7;
                }
                
                @media print {
                    body { margin: 0; }
                    .container { padding: 0; }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <!-- Header -->
                <div class="header">
                    <h1> Medical Prescription</h1>
                    <p>Generated from Telemedicine Platform</p>
                </div>

                <!-- Doctor Information -->
                <div class="doctor-info">
                    <h3> Doctor Information</h3>
                    <p><strong>Name:</strong> Dr. <?php echo htmlspecialchars($row['doctor_name']); ?></p>
                    <p><strong>Specialty:</strong> <?php echo htmlspecialchars($row['specialty']); ?></p>
                    <p><strong>Qualification:</strong> <?php echo htmlspecialchars($row['qualification']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['doctor_email']); ?> | 
                       <strong>Phone:</strong> <?php echo htmlspecialchars($row['doctor_mobile']); ?></p>
                    <p><strong>Consultation Fee:</strong> PKR <?php echo htmlspecialchars($row['fee']); ?></p>
                </div>

                <!-- Patient Information -->
                <div class="patient-info">
                    <h4>👤 Patient Information</h4>
                    <div class="patient-details">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($row['patient_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['patient_email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['patient_phone']); ?></p>
                        <p><strong>Age:</strong> <?php echo htmlspecialchars($row['age']); ?></p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($row['gender']); ?></p>
                    </div>
                </div>

                <!-- Prescription -->
                <div class="prescription-box">
                    <h4> Prescription Details</h4>
                    <div class="medicine-item">
                        <div class="medicine-name"><?php echo htmlspecialchars($row['medicine']); ?></div>
                        <div class="medicine-details">
                            <div class="detail-row">
                                <span class="detail-label">Dosage:</span> <?php echo htmlspecialchars($row['dosage']); ?>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Frequency:</span> <?php echo htmlspecialchars($row['frequency']); ?>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Duration:</span> <?php echo htmlspecialchars($row['duration']); ?>
                            </div>
                        </div>
                        <?php if (!empty($row['instructions'])): ?>
                            <div class="instructions">
                                <strong> Instructions:</strong> <?php echo htmlspecialchars($row['instructions']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="date-issued">
                    <p><strong>Date Issued:</strong> <?php echo date('d M, Y H:i', strtotime($row['created_at'])); ?></p>
                </div>

                <div class="footer">
                    <p>This is an electronically generated prescription from Telemedicine Platform.</p>
                    <p>Please consult the doctor if you have any questions regarding this prescription.</p>
                </div>
            </div>

            <script>
                window.print();
            </script>
        </body>
        </html>
        <?php
        $content = ob_get_clean();

        // Create filename
        $filename = "Prescription_" . $row['patient_name'] . "_" . date('d-m-Y') . ".html";

        // Send headers for download
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $content;
        exit;
    } else {
        $error_message = "Prescription not found or you don't have access to it.";
    }
    $stmt->close();
}

// Fetch prescriptions for current patient
$prescription_query = "SELECT pr.pid, pr.medicine, pr.dosage, pr.duration, pr.frequency, pr.instructions,
                              pr.created_at, d.name as doctor_name, d.specialty, d.qualification, d.mobile
                       FROM prescriptions pr
                       INNER JOIN doctor d ON pr.doctorid = d.id
                       WHERE pr.patientid = ?
                       ORDER BY pr.created_at DESC";

$stmt = $conn->prepare($prescription_query);
$stmt->bind_param("i", $current_patient_id);
$stmt->execute();
$prescriptions_result = $stmt->get_result();
$prescriptions = [];

while ($row = $prescriptions_result->fetch_assoc()) {
    $prescriptions[] = $row;
}

$stmt->close();

include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Prescriptions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
        }

        .page-title {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 28px;
        }

        .page-title i {
            color: var(--primary-color);
            font-size: 32px;
        }

        .prescription-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
            border-left: 5px solid var(--secondary-color);
            transition: all 0.3s ease;
        }

        .prescription-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .prescription-header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #27ae60 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .prescription-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 18px;
        }

        .prescription-date {
            font-size: 12px;
            opacity: 0.9;
        }

        .prescription-body {
            padding: 20px;
        }

        .doctor-badge {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2980b9 100%);
            color: white;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .doctor-badge strong {
            display: block;
            margin-bottom: 5px;
        }

        .medicine-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid var(--primary-color);
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 14px;
        }

        .detail-label {
            font-weight: 600;
            color: var(--dark-color);
            min-width: 120px;
        }

        .detail-value {
            color: #555;
        }

        .instructions-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 6px;
            padding: 12px;
            margin-top: 10px;
            font-size: 13px;
            color: #856404;
        }

        .btn-download {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-download:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            color: white;
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 60px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #7f8c8d;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .filter-section h6 {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 15px;
        }
        .mt-4{
            margin-bottom: 60px;
        }

        .sort-button {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .sort-button:hover {
            background: #27ae60;
        }

        .sort-button.active {
            background: var(--primary-color);
        }

        @media (max-width: 768px) {
            .prescription-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .detail-row {
                flex-direction: column;
            }

            .page-title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Page Title -->
        <div class="page-title">
            <i class="fas fa-prescription-bottle-alt"></i>
            My Prescriptions
        </div>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Filter Section -->
        <div class="filter-section">
            <h6><i class="fas fa-filter me-2"></i>Filter</h6>
            <button class="sort-button active" onclick="filterPrescriptions('all')">
                <i class="fas fa-list me-1"></i>All Prescriptions
            </button>
            <button class="sort-button" onclick="filterPrescriptions('recent')">
                <i class="fas fa-clock me-1"></i>Recent
            </button>
        </div>

        <!-- Prescriptions List -->
        <?php if (count($prescriptions) > 0): ?>
            <div id="prescriptions-container">
                <?php foreach ($prescriptions as $prescription): ?>
                    <div class="prescription-card">
                        <!-- Header -->
                        <div class="prescription-header">
                            <div>
                                <h5><?php echo htmlspecialchars($prescription['medicine']); ?></h5>
                                <div class="prescription-date">
                                    <i class="fas fa-calendar me-1"></i><?php echo date('d M, Y H:i', strtotime($prescription['created_at'])); ?>
                                </div>
                            </div>
                            <a href="?download_pdf=<?php echo $prescription['pid']; ?>" class="btn-download" title="Download Prescription as PDF">
                                <i class="fas fa-download"></i>Download
                            </a>
                        </div>

                        <!-- Body -->
                        <div class="prescription-body">
                            <!-- Doctor Badge -->
                            <div class="doctor-badge">
                                <strong><i class="fas fa-user-md me-2"></i>Dr. <?php echo htmlspecialchars($prescription['doctor_name']); ?></strong>
                                <small>
                                    <?php echo htmlspecialchars($prescription['specialty']); ?> | <?php echo htmlspecialchars($prescription['qualification']); ?>
                                </small>
                            </div>

                            <!-- Medicine Details -->
                            <div class="medicine-details">
                                <div class="detail-row">
                                    <span class="detail-label"><i class="fas fa-pills me-2"></i>Dosage:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($prescription['dosage']); ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label"><i class="fas fa-hourglass-end me-2"></i>Frequency:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($prescription['frequency']); ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label"><i class="fas fa-calendar-days me-2"></i>Duration:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($prescription['duration']); ?></span>
                                </div>
                            </div>

                            <!-- Instructions -->
                            <?php if (!empty($prescription['instructions'])): ?>
                                <div class="instructions-box">
                                    <strong><i class="fas fa-lightbulb me-2"></i>Instructions:</strong><br>
                                    <?php echo htmlspecialchars($prescription['instructions']); ?>
                                </div>
                            <?php endif; ?>

                            
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="card shadow-sm" style="border: none;">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h4>No Prescriptions Yet</h4>
                        <p>You haven't received any prescriptions from your doctors yet.</p>
                        <p class="text-muted"><a href="mydoctors.php" style="color: var(--primary-color); text-decoration: none;"><i class="fas fa-arrow-right me-1"></i>Book a consultation</a></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->

    <script>
        function filterPrescriptions(type) {
            // Update button states
            document.querySelectorAll('.sort-button').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');

            // Filter logic
            const cards = document.querySelectorAll('.prescription-card');
            if (type === 'all') {
                cards.forEach(card => card.style.display = 'block');
            } else if (type === 'recent') {
                cards.forEach(card => card.style.display = 'block');
                // Already sorted by recent on server side
            }
        }
    </script>
</body>
</html>

<?php include 'footer.php'; ?>
