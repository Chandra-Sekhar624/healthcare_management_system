<?php
// Start session
session_start();

// Include database configuration
// include '../includes/config.php';

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get doctor information
$doctor_id = $_SESSION['user_id'];
$doctor_name = $_SESSION['user_name'];

// Placeholder data (in a real system, you would fetch this from the database)
$medical_records = [
    [
        'id' => 1,
        'patient_name' => 'Jane Smith',
        'patient_id' => 1,
        'date' => '2023-06-01',
        'type' => 'Consultation',
        'diagnosis' => 'Hypertension',
        'blood_pressure' => '140/90 mmHg',
        'heart_rate' => '76 bpm',
        'temperature' => '98.6°F',
        'symptoms' => 'Headache, dizziness',
        'treatment' => 'Prescribed Lisinopril 10mg once daily',
        'notes' => 'Patient advised to reduce salt intake and exercise regularly',
        'status' => 'active'
    ],
    [
        'id' => 2,
        'patient_name' => 'Robert Johnson',
        'patient_id' => 2,
        'date' => '2023-05-28',
        'type' => 'Follow-up',
        'diagnosis' => 'Diabetes Type 2',
        'blood_pressure' => '135/85 mmHg',
        'heart_rate' => '82 bpm',
        'temperature' => '98.7°F',
        'symptoms' => 'Increased thirst, frequent urination',
        'treatment' => 'Adjusted Metformin dosage to 1000mg twice daily',
        'notes' => 'Blood sugar levels showing improvement but still need monitoring',
        'status' => 'active'
    ],
    [
        'id' => 3,
        'patient_name' => 'Emily Williams',
        'patient_id' => 3,
        'date' => '2023-05-15',
        'type' => 'Prenatal Check-up',
        'diagnosis' => 'Normal pregnancy - 28 weeks',
        'blood_pressure' => '120/80 mmHg',
        'heart_rate' => '88 bpm',
        'temperature' => '98.2°F',
        'symptoms' => 'Mild lower back pain',
        'treatment' => 'Prescribed prenatal vitamins',
        'notes' => 'Fetal heart rate normal, fundal height appropriate for gestational age',
        'status' => 'active'
    ],
    [
        'id' => 4,
        'patient_name' => 'Michael Brown',
        'patient_id' => 4,
        'date' => '2023-06-10',
        'type' => 'Routine Check-up',
        'diagnosis' => 'Osteoarthritis',
        'blood_pressure' => '130/85 mmHg',
        'heart_rate' => '72 bpm',
        'temperature' => '98.4°F',
        'symptoms' => 'Joint pain in knees and hands',
        'treatment' => 'Prescribed anti-inflammatory medication and physical therapy',
        'notes' => 'Recommended follow-up in 4 weeks',
        'status' => 'active'
    ],
    [
        'id' => 5,
        'patient_name' => 'Sarah Thompson',
        'patient_id' => 5,
        'date' => '2023-06-05',
        'type' => 'Emergency',
        'diagnosis' => 'Acute asthma attack',
        'blood_pressure' => '125/82 mmHg',
        'heart_rate' => '95 bpm',
        'temperature' => '99.1°F',
        'symptoms' => 'Shortness of breath, wheezing',
        'treatment' => 'Administered nebulizer treatment, prescribed steroid inhaler',
        'notes' => 'Patient responded well to treatment, advised to avoid known triggers',
        'status' => 'active'
    ],
    [
        'id' => 6,
        'patient_name' => 'David Wilson',
        'patient_id' => 6,
        'date' => '2023-06-08',
        'type' => 'Post-Surgery',
        'diagnosis' => 'Recovering from appendectomy',
        'blood_pressure' => '128/78 mmHg',
        'heart_rate' => '80 bpm',
        'temperature' => '98.8°F',
        'symptoms' => 'Mild pain at incision site',
        'treatment' => 'Wound care and pain management',
        'notes' => 'Surgical site healing well, no signs of infection',
        'status' => 'active'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records | Doctor Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-body">
    <!-- Sidebar and Navigation -->
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="dashboard-sidebar bg-dark text-white">
            <div class="sidebar-header p-3 text-center">
                <a href="../index.php" class="sidebar-brand text-decoration-none">
                    <i class="fas fa-heartbeat text-primary me-2"></i>
                    <span class="fw-bold fs-4 text-white">HealthConnect</span>
                </a>
            </div>
            <hr class="sidebar-divider">
            <div class="sidebar-user text-center mb-4">
            <div class="user-avatar mb-3">
                    <?php
                    // Display the uploaded image if available, otherwise show default
                    $profile_image = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : '../img/doctor-avatar.jpg';
                    ?>
                    <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Doctor" class="rounded-circle">
                </div>
                <div class="user-info">
                    <h6 class="mb-0"><?php echo htmlspecialchars($doctor_name); ?></h6>
                    <span class="text-muted small">Doctor</span>
                </div>
            </div>
            <ul class="sidebar-nav list-unstyled">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="appointment.php">
                        <i class="fas fa-calendar-check me-2"></i> Appointments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my-patients.php">
                        <i class="fas fa-user-injured me-2"></i> My Patients
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="medical-records.php">
                        <i class="fas fa-notes-medical me-2"></i> Medical Records
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="prescription.php">
                        <i class="fas fa-prescription me-2"></i> Prescriptions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="schedule.php">
                        <i class="fas fa-clock me-2"></i> Schedule
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reviews.php">
                        <i class="fas fa-star me-2"></i> Reviews
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="payments.php">
                        <i class="fas fa-money-bill-wave me-2"></i> Payments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">
                        <i class="fas fa-cog me-2"></i> Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="dashboard-content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggle" class="btn btn-link d-md-none rounded-circle me-3">
                    <i class="fas fa-bars"></i>
                </button>
                
                <ul class="navbar-nav ms-auto">
                    <!-- Notifications -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell fa-fw"></i>
                            <span class="badge bg-danger badge-counter">3+</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">Notifications Center</h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">Today</div>
                                    <span>A new medical record has been added</span>
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-primary" href="#">Show All Notifications</a>
                        </div>
                    </li>

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($doctor_name); ?></span>
                            <img class="img-profile rounded-circle" src="<?php echo htmlspecialchars(isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : '../img/doctor-avatar.jpg'); ?>">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i> Profile
                            </a>
                            <a class="dropdown-item" href="settings.php">
                                <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../logout.php">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Medical Records</h1>
                    <div>
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                            <i class="fas fa-plus fa-sm me-2"></i> Add New Record
                        </a>
                        <a href="#" class="btn btn-info btn-sm shadow-sm ms-2" onclick="window.print();">
                            <i class="fas fa-print fa-sm me-2"></i> Print
                        </a>
                    </div>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <!-- Record Statistics Cards -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Records</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">54</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Records This Month</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">12</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Tests Pending</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">7</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-flask fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Follow-ups Required</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">4</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Records Filter -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">Filter Records</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Actions:</div>
                                <a class="dropdown-item" href="#">Reset Filters</a>
                                <a class="dropdown-item" href="#">Save Filters</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row mb-3">
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <label for="patientFilter" class="form-label">Patient</label>
                                    <select class="form-select" id="patientFilter">
                                        <option value="" selected>All Patients</option>
                                        <option value="1">Jane Smith</option>
                                        <option value="2">Robert Johnson</option>
                                        <option value="3">Emily Williams</option>
                                        <option value="4">Michael Brown</option>
                                        <option value="5">Sarah Thompson</option>
                                        <option value="6">David Wilson</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <label for="recordType" class="form-label">Record Type</label>
                                    <select class="form-select" id="recordType">
                                        <option value="" selected>All Types</option>
                                        <option value="consultation">Consultation</option>
                                        <option value="follow-up">Follow-up</option>
                                        <option value="emergency">Emergency</option>
                                        <option value="test-results">Test Results</option>
                                        <option value="prenatal">Prenatal Check-up</option>
                                        <option value="post-surgery">Post-Surgery</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <label for="dateRange" class="form-label">Date Range</label>
                                    <select class="form-select" id="dateRange">
                                        <option value="all" selected>All Time</option>
                                        <option value="today">Today</option>
                                        <option value="thisweek">This Week</option>
                                        <option value="thismonth">This Month</option>
                                        <option value="lastsixmonths">Last 6 Months</option>
                                        <option value="lastyear">Last Year</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>
                                <div class="col-md-3 align-self-end">
                                    <button type="button" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-2"></i> Apply Filters
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Medical Records Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">All Medical Records</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="medicalRecordsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Diagnosis</th>
                                        <th>Vitals</th>
                                        <th>Treatment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($medical_records as $record): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($record['id']); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2" style="width: 32px; height: 32px; background-color: #4e73df; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold;">
                                                    <?php echo substr(htmlspecialchars($record['patient_name']), 0, 1); ?>
                                                </div>
                                                <?php echo htmlspecialchars($record['patient_name']); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($record['date']); ?></td>
                                        <td>
                                            <span class="badge bg-info rounded-pill">
                                                <?php echo htmlspecialchars($record['type']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($record['diagnosis']); ?></td>
                                        <td>
                                            <small>
                                                <div><i class="fas fa-heartbeat me-1 text-danger"></i> <?php echo htmlspecialchars($record['heart_rate']); ?></div>
                                                <div><i class="fas fa-heart me-1 text-primary"></i> <?php echo htmlspecialchars($record['blood_pressure']); ?></div>
                                                <div><i class="fas fa-thermometer-half me-1 text-warning"></i> <?php echo htmlspecialchars($record['temperature']); ?></div>
                                            </small>
                                        </td>
                                        <td><?php echo substr(htmlspecialchars($record['treatment']), 0, 30) . (strlen($record['treatment']) > 30 ? '...' : ''); ?></td>
                                        <td>
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#viewRecordModal<?php echo $record['id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#editRecordModal<?php echo $record['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#printRecordModal<?php echo $record['id']; ?>">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#addPrescriptionModal<?php echo $record['id']; ?>">
                                                    <i class="fas fa-prescription"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Medical Record Modal -->
    <div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRecordModalLabel">Add New Medical Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="patientSelect" class="form-label">Patient</label>
                                <select class="form-select" id="patientSelect" required>
                                    <option value="" selected disabled>Select Patient</option>
                                    <option value="1">Jane Smith</option>
                                    <option value="2">Robert Johnson</option>
                                    <option value="3">Emily Williams</option>
                                    <option value="4">Michael Brown</option>
                                    <option value="5">Sarah Thompson</option>
                                    <option value="6">David Wilson</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="visitDate" class="form-label">Visit Date</label>
                                <input type="date" class="form-control" id="visitDate" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="recordType" class="form-label">Record Type</label>
                                <select class="form-select" id="recordType" required>
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="consultation">Consultation</option>
                                    <option value="follow-up">Follow-up</option>
                                    <option value="emergency">Emergency</option>
                                    <option value="test-results">Test Results</option>
                                    <option value="prenatal">Prenatal Check-up</option>
                                    <option value="post-surgery">Post-Surgery</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="diagnosis" class="form-label">Diagnosis</label>
                                <input type="text" class="form-control" id="diagnosis" placeholder="Primary diagnosis">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="bloodPressure" class="form-label">Blood Pressure</label>
                                <input type="text" class="form-control" id="bloodPressure" placeholder="e.g. 120/80 mmHg">
                            </div>
                            <div class="col-md-4">
                                <label for="heartRate" class="form-label">Heart Rate</label>
                                <input type="text" class="form-control" id="heartRate" placeholder="e.g. 72 bpm">
                            </div>
                            <div class="col-md-4">
                                <label for="temperature" class="form-label">Temperature</label>
                                <input type="text" class="form-control" id="temperature" placeholder="e.g. 98.6°F">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="symptoms" class="form-label">Symptoms</label>
                            <textarea class="form-control" id="symptoms" rows="2" placeholder="Patient reported symptoms"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="treatment" class="form-label">Treatment Plan</label>
                            <textarea class="form-control" id="treatment" rows="2" placeholder="Prescribed treatment and medications"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes" rows="3" placeholder="Any additional observations or recommendations"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="attachments" class="form-label">Attachments</label>
                            <input class="form-control" type="file" id="attachments" multiple>
                            <div class="form-text">Upload any relevant files (test results, images, etc.)</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Record</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Record Modals -->
    <?php foreach ($medical_records as $record): ?>
    <div class="modal fade" id="viewRecordModal<?php echo $record['id']; ?>" tabindex="-1" aria-labelledby="viewRecordModalLabel<?php echo $record['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRecordModalLabel<?php echo $record['id']; ?>">View Medical Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="medical-record-view">
                        <div class="record-header mb-4 p-3 bg-light border rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($record['patient_name']); ?></h6>
                                    <p class="mb-1 small">Patient ID: <?php echo htmlspecialchars($record['patient_id']); ?></p>
                                    <p class="mb-0 small">Record #: <?php echo htmlspecialchars($record['id']); ?></p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <h6 class="mb-1">Dr. <?php echo htmlspecialchars($doctor_name); ?></h6>
                                    <p class="mb-1 small">
                                        <span class="badge bg-info rounded-pill">
                                            <?php echo htmlspecialchars($record['type']); ?>
                                        </span>
                                    </p>
                                    <p class="mb-0 small">Date: <?php echo htmlspecialchars($record['date']); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="record-details mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Diagnosis & Assessment</h6>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <p class="mb-2"><strong>Diagnosis:</strong> <?php echo htmlspecialchars($record['diagnosis']); ?></p>
                                    <p class="mb-0"><strong>Symptoms:</strong> <?php echo htmlspecialchars($record['symptoms']); ?></p>
                                </div>
                            </div>
                            
                            <h6 class="border-bottom pb-2 mb-3 mt-4">Vital Signs</h6>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <p class="mb-0"><i class="fas fa-heart me-1 text-primary"></i> <strong>Blood Pressure:</strong> <?php echo htmlspecialchars($record['blood_pressure']); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-0"><i class="fas fa-heartbeat me-1 text-danger"></i> <strong>Heart Rate:</strong> <?php echo htmlspecialchars($record['heart_rate']); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-0"><i class="fas fa-thermometer-half me-1 text-warning"></i> <strong>Temperature:</strong> <?php echo htmlspecialchars($record['temperature']); ?></p>
                                </div>
                            </div>
                            
                            <h6 class="border-bottom pb-2 mb-3 mt-4">Treatment & Notes</h6>
                            <div class="mb-3">
                                <p class="mb-2"><strong>Treatment Plan:</strong> <?php echo htmlspecialchars($record['treatment']); ?></p>
                                <p class="mb-0"><strong>Additional Notes:</strong> <?php echo htmlspecialchars($record['notes']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editRecordModal<?php echo $record['id']; ?>">
                        <i class="fas fa-edit me-1"></i> Edit
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#printRecordModal<?php echo $record['id']; ?>">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    
    <!-- Edit Record Modals -->
    <?php foreach ($medical_records as $record): ?>
    <div class="modal fade" id="editRecordModal<?php echo $record['id']; ?>" tabindex="-1" aria-labelledby="editRecordModalLabel<?php echo $record['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRecordModalLabel<?php echo $record['id']; ?>">Edit Medical Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="patientSelect<?php echo $record['id']; ?>" class="form-label">Patient</label>
                                <select class="form-select" id="patientSelect<?php echo $record['id']; ?>" required>
                                    <option value="" disabled>Select Patient</option>
                                    <?php 
                                    $patients = [
                                        1 => 'Jane Smith',
                                        2 => 'Robert Johnson',
                                        3 => 'Emily Williams',
                                        4 => 'Michael Brown',
                                        5 => 'Sarah Thompson',
                                        6 => 'David Wilson'
                                    ];
                                    foreach ($patients as $id => $name): 
                                        $selected = ($id == $record['patient_id']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $id; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="recordDate<?php echo $record['id']; ?>" class="form-label">Record Date</label>
                                <input type="date" class="form-control" id="recordDate<?php echo $record['id']; ?>" value="<?php echo $record['date']; ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="recordType<?php echo $record['id']; ?>" class="form-label">Record Type</label>
                                <select class="form-select" id="recordType<?php echo $record['id']; ?>" required>
                                    <?php 
                                    $types = [
                                        'Consultation' => 'Consultation',
                                        'Follow-up' => 'Follow-up',
                                        'Emergency' => 'Emergency',
                                        'Routine Check-up' => 'Routine Check-up',
                                        'Prenatal Check-up' => 'Prenatal Check-up',
                                        'Post-Surgery' => 'Post-Surgery',
                                        'Vaccination' => 'Vaccination',
                                        'Specialist Referral' => 'Specialist Referral',
                                        'Lab Results Review' => 'Lab Results Review',
                                        'Other' => 'Other'
                                    ];
                                    foreach ($types as $value => $label): 
                                        $selected = ($value === $record['type']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="diagnosis<?php echo $record['id']; ?>" class="form-label">Diagnosis</label>
                                <input type="text" class="form-control" id="diagnosis<?php echo $record['id']; ?>" value="<?php echo htmlspecialchars($record['diagnosis']); ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="bloodPressure<?php echo $record['id']; ?>" class="form-label">Blood Pressure</label>
                                <input type="text" class="form-control" id="bloodPressure<?php echo $record['id']; ?>" value="<?php echo htmlspecialchars($record['blood_pressure']); ?>" placeholder="e.g. 120/80 mmHg">
                            </div>
                            <div class="col-md-4">
                                <label for="heartRate<?php echo $record['id']; ?>" class="form-label">Heart Rate</label>
                                <input type="text" class="form-control" id="heartRate<?php echo $record['id']; ?>" value="<?php echo htmlspecialchars($record['heart_rate']); ?>" placeholder="e.g. 72 bpm">
                            </div>
                            <div class="col-md-4">
                                <label for="temperature<?php echo $record['id']; ?>" class="form-label">Temperature</label>
                                <input type="text" class="form-control" id="temperature<?php echo $record['id']; ?>" value="<?php echo htmlspecialchars($record['temperature']); ?>" placeholder="e.g. 98.6°F">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="symptoms<?php echo $record['id']; ?>" class="form-label">Symptoms</label>
                            <textarea class="form-control" id="symptoms<?php echo $record['id']; ?>" rows="2"><?php echo htmlspecialchars($record['symptoms']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="treatment<?php echo $record['id']; ?>" class="form-label">Treatment Plan</label>
                            <textarea class="form-control" id="treatment<?php echo $record['id']; ?>" rows="2" required><?php echo htmlspecialchars($record['treatment']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes<?php echo $record['id']; ?>" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes<?php echo $record['id']; ?>" rows="3"><?php echo htmlspecialchars($record['notes']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="attachments<?php echo $record['id']; ?>" class="form-label">Attachments</label>
                            <input class="form-control" type="file" id="attachments<?php echo $record['id']; ?>" multiple>
                            <div class="form-text">Upload any relevant files (test results, images, etc.)</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    
    <!-- Print Record Modals -->
    <?php foreach ($medical_records as $record): ?>
    <div class="modal fade" id="printRecordModal<?php echo $record['id']; ?>" tabindex="-1" aria-labelledby="printRecordModalLabel<?php echo $record['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printRecordModalLabel<?php echo $record['id']; ?>">Print Medical Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="medical-record-print p-4 border rounded" id="printable-record-<?php echo $record['id']; ?>">
                        <div class="text-center mb-4">
                            <h4 class="mb-1">HealthConnect Medical Center</h4>
                            <p class="mb-1 small">123 Healthcare Ave, Medical City, MC 12345</p>
                            <p class="mb-0 small">Phone: (555) 123-4567 | Fax: (555) 987-6543</p>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-6">
                                <h5 class="border-bottom pb-2">Patient Information</h5>
                                <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($record['patient_name']); ?></p>
                                <p class="mb-1"><strong>Patient ID:</strong> <?php echo htmlspecialchars($record['patient_id']); ?></p>
                                <p class="mb-0"><strong>Date:</strong> <?php echo htmlspecialchars($record['date']); ?></p>
                            </div>
                            <div class="col-6">
                                <h5 class="border-bottom pb-2">Provider Information</h5>
                                <p class="mb-1"><strong>Physician:</strong> Dr. <?php echo htmlspecialchars($doctor_name); ?></p>
                                <p class="mb-1"><strong>Record Type:</strong> <?php echo htmlspecialchars($record['type']); ?></p>
                                <p class="mb-0"><strong>Record ID:</strong> MR-<?php echo date('ymd', strtotime($record['date'])); ?>-<?php echo sprintf('%03d', $record['id']); ?></p>
                            </div>
                        </div>
                        
                        <div class="medical-record-content mb-4">
                            <h5 class="border-bottom pb-2">Diagnosis & Assessment</h5>
                            <p class="mb-2"><strong>Diagnosis:</strong> <?php echo htmlspecialchars($record['diagnosis']); ?></p>
                            <p class="mb-3"><strong>Symptoms:</strong> <?php echo htmlspecialchars($record['symptoms']); ?></p>
                            
                            <h5 class="border-bottom pb-2 mt-4">Vital Signs</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <p class="mb-0"><strong>Blood Pressure:</strong> <?php echo htmlspecialchars($record['blood_pressure']); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-0"><strong>Heart Rate:</strong> <?php echo htmlspecialchars($record['heart_rate']); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-0"><strong>Temperature:</strong> <?php echo htmlspecialchars($record['temperature']); ?></p>
                                </div>
                            </div>
                            
                            <h5 class="border-bottom pb-2 mt-4">Treatment & Notes</h5>
                            <p class="mb-2"><strong>Treatment Plan:</strong> <?php echo htmlspecialchars($record['treatment']); ?></p>
                            <p class="mb-0"><strong>Additional Notes:</strong> <?php echo htmlspecialchars($record['notes']); ?></p>
                        </div>
                        
                        <div class="row mt-5">
                            <div class="col-6">
                                <div class="border-top pt-2">
                                    <p class="mb-0">Physician Signature</p>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <p class="mb-0 small">Printed on: <?php echo date('d/m/Y h:i A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="printMedicalRecord(<?php echo $record['id']; ?>)">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    
    <!-- Add Prescription Modal -->
    <?php foreach ($medical_records as $record): ?>
    <div class="modal fade" id="addPrescriptionModal<?php echo $record['id']; ?>" tabindex="-1" aria-labelledby="addPrescriptionModalLabel<?php echo $record['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPrescriptionModalLabel<?php echo $record['id']; ?>">Create Prescription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Creating prescription for <strong><?php echo htmlspecialchars($record['patient_name']); ?></strong> based on medical record #<?php echo htmlspecialchars($record['id']); ?>
                    </div>
                    
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="prescriptionDate<?php echo $record['id']; ?>" class="form-label">Prescription Date</label>
                                <input type="date" class="form-control" id="prescriptionDate<?php echo $record['id']; ?>" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="diagnosis<?php echo $record['id']; ?>" class="form-label">Diagnosis</label>
                                <input type="text" class="form-control" id="diagnosis<?php echo $record['id']; ?>" value="<?php echo htmlspecialchars($record['diagnosis']); ?>" readonly>
                            </div>
                        </div>
                        
                        <h6 class="border-bottom pb-2 mb-3">Medication Details</h6>
                        
                        <div id="medicationContainer<?php echo $record['id']; ?>">
                            <div class="medication-entry border rounded p-3 mb-3">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="medication1<?php echo $record['id']; ?>" class="form-label">Medication</label>
                                        <input type="text" class="form-control" id="medication1<?php echo $record['id']; ?>" placeholder="Medication name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="dosage1<?php echo $record['id']; ?>" class="form-label">Dosage</label>
                                        <input type="text" class="form-control" id="dosage1<?php echo $record['id']; ?>" placeholder="e.g. 10mg tablet" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="frequency1<?php echo $record['id']; ?>" class="form-label">Frequency</label>
                                        <select class="form-select" id="frequency1<?php echo $record['id']; ?>" required>
                                            <option value="" selected disabled>Select frequency</option>
                                            <option value="once_daily">Once Daily</option>
                                            <option value="twice_daily">Twice Daily</option>
                                            <option value="three_daily">Three Times Daily</option>
                                            <option value="four_daily">Four Times Daily</option>
                                            <option value="every_morning">Every Morning</option>
                                            <option value="every_evening">Every Evening</option>
                                            <option value="every_hour">Every Hour</option>
                                            <option value="every_4_hours">Every 4 Hours</option>
                                            <option value="every_6_hours">Every 6 Hours</option>
                                            <option value="every_8_hours">Every 8 Hours</option>
                                            <option value="every_12_hours">Every 12 Hours</option>
                                            <option value="as_needed">As Needed (PRN)</option>
                                            <option value="other">Other (Specify)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="duration1<?php echo $record['id']; ?>" class="form-label">Duration</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="duration1<?php echo $record['id']; ?>" value="7" min="1" required>
                                            <select class="form-select" id="durationUnit1<?php echo $record['id']; ?>">
                                                <option value="day">Days</option>
                                                <option value="week">Weeks</option>
                                                <option value="month">Months</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="refills1<?php echo $record['id']; ?>" class="form-label">Refills</label>
                                        <input type="number" class="form-control" id="refills1<?php echo $record['id']; ?>" value="0" min="0" max="12">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="directions1<?php echo $record['id']; ?>" class="form-label">Directions for Use</label>
                                    <textarea class="form-control" id="directions1<?php echo $record['id']; ?>" rows="2" placeholder="Instructions for taking medication" required></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addMedicationField(<?php echo $record['id']; ?>)">
                                <i class="fas fa-plus me-1"></i> Add Another Medication
                            </button>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes<?php echo $record['id']; ?>" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes<?php echo $record['id']; ?>" rows="3" placeholder="Any special instructions or notes"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="dispenseAsWritten<?php echo $record['id']; ?>">
                                <label class="form-check-label" for="dispenseAsWritten<?php echo $record['id']; ?>">
                                    Dispense As Written (No Substitution)
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Create Prescription</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.1.3 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/main.js"></script>
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('#medicalRecordsTable').DataTable({
                responsive: true,
                order: [[2, 'desc']] // Sort by date (newest first)
            });
        });

        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
        });
        
        // Function to print medical record
        function printMedicalRecord(recordId) {
            // Store the current body content
            const originalContent = document.body.innerHTML;
            
            // Get only the printable section
            const printContent = document.getElementById('printable-record-' + recordId).innerHTML;
            
            // Replace the body content with the printable section
            document.body.innerHTML = `
                <div style="max-width: 800px; margin: 0 auto; padding: 20px;">
                    ${printContent}
                </div>
            `;
            
            // Print the document
            window.print();
            
            // Restore the original content
            document.body.innerHTML = originalContent;
            
            // Reattach event listeners and reinitialize components
            $(document).ready(function() {
                $('#medicalRecordsTable').DataTable({
                    responsive: true,
                    order: [[2, 'desc']] // Sort by date (newest first)
                });
                
                // Reattach the print function to buttons
                const printButtons = document.querySelectorAll('[onclick^="printMedicalRecord"]');
                printButtons.forEach(button => {
                    const id = button.getAttribute('onclick').match(/\d+/)[0];
                    button.onclick = function() { printMedicalRecord(id); };
                });
                
                // Reattach sidebar toggle event listener
                document.getElementById('sidebarToggle').addEventListener('click', function() {
                    document.querySelector('.dashboard-sidebar').classList.toggle('show');
                });
            });
        }
        
        // Counter for medication fields
        let medicationCounts = {};
        
        // Function to add another medication field
        function addMedicationField(recordId) {
            // Initialize counter for this record if not exists
            if (!medicationCounts[recordId]) {
                medicationCounts[recordId] = 1;
            }
            
            // Increment counter
            medicationCounts[recordId]++;
            const count = medicationCounts[recordId];
            
            // Create new medication entry
            const newEntry = `
                <div class="medication-entry border rounded p-3 mb-3" id="medication-entry-${recordId}-${count}">
                    <div class="d-flex justify-content-end mb-2">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMedicationField(${recordId}, ${count})">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="medication${count}${recordId}" class="form-label">Medication</label>
                            <input type="text" class="form-control" id="medication${count}${recordId}" placeholder="Medication name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="dosage${count}${recordId}" class="form-label">Dosage</label>
                            <input type="text" class="form-control" id="dosage${count}${recordId}" placeholder="e.g. 10mg tablet" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="frequency${count}${recordId}" class="form-label">Frequency</label>
                            <select class="form-select" id="frequency${count}${recordId}" required>
                                <option value="" selected disabled>Select frequency</option>
                                <option value="once_daily">Once Daily</option>
                                <option value="twice_daily">Twice Daily</option>
                                <option value="three_daily">Three Times Daily</option>
                                <option value="four_daily">Four Times Daily</option>
                                <option value="every_morning">Every Morning</option>
                                <option value="every_evening">Every Evening</option>
                                <option value="every_hour">Every Hour</option>
                                <option value="every_4_hours">Every 4 Hours</option>
                                <option value="every_6_hours">Every 6 Hours</option>
                                <option value="every_8_hours">Every 8 Hours</option>
                                <option value="every_12_hours">Every 12 Hours</option>
                                <option value="as_needed">As Needed (PRN)</option>
                                <option value="other">Other (Specify)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="duration${count}${recordId}" class="form-label">Duration</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="duration${count}${recordId}" value="7" min="1" required>
                                <select class="form-select" id="durationUnit${count}${recordId}">
                                    <option value="day">Days</option>
                                    <option value="week">Weeks</option>
                                    <option value="month">Months</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="refills${count}${recordId}" class="form-label">Refills</label>
                            <input type="number" class="form-control" id="refills${count}${recordId}" value="0" min="0" max="12">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="directions${count}${recordId}" class="form-label">Directions for Use</label>
                        <textarea class="form-control" id="directions${count}${recordId}" rows="2" placeholder="Instructions for taking medication" required></textarea>
                    </div>
                </div>
            `;
            
            // Append to container
            document.getElementById('medicationContainer' + recordId).insertAdjacentHTML('beforeend', newEntry);
        }
        
        // Function to remove a medication field
        function removeMedicationField(recordId, count) {
            const entryToRemove = document.getElementById(`medication-entry-${recordId}-${count}`);
            if (entryToRemove) {
                entryToRemove.remove();
            }
        }
        
        // Function to print medical record
        function printMedicalRecord(recordId) {
            const printContents = document.getElementById(`printable-record-${recordId}`).innerHTML;
            const originalContents = document.body.innerHTML;
            
            // Create a new window with only the printable content
            document.body.innerHTML = `
                <html>
                <head>
                    <title>Medical Record #${recordId}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .text-center { text-align: center; }
                        .mb-1 { margin-bottom: 0.25rem; }
                        .mb-0 { margin-bottom: 0; }
                        .mb-2 { margin-bottom: 0.5rem; }
                        .mb-3 { margin-bottom: 1rem; }
                        .mb-4 { margin-bottom: 1.5rem; }
                        .mt-4 { margin-top: 1.5rem; }
                        .mt-5 { margin-top: 3rem; }
                        .pb-2 { padding-bottom: 0.5rem; }
                        .pt-2 { padding-top: 0.5rem; }
                        .border-bottom { border-bottom: 1px solid #dee2e6; }
                        .border-top { border-top: 1px solid #dee2e6; }
                        .row { display: flex; flex-wrap: wrap; margin-right: -0.75rem; margin-left: -0.75rem; }
                        .col-6 { flex: 0 0 50%; max-width: 50%; padding-right: 0.75rem; padding-left: 0.75rem; }
                        .col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; padding-right: 0.75rem; padding-left: 0.75rem; }
                        .small { font-size: 0.875em; }
                        .text-end { text-align: right; }
                        h4, h5, h6 { margin-top: 0; }
                        p { margin-top: 0; }
                        @media print {
                            body { margin: 0; padding: 15px; }
                        }
                    </style>
                </head>
                <body>
                    ${printContents}
                </body>
                </html>
            `;
            
            // Print the document
            window.print();
            
            // Restore the original content
            setTimeout(function() {
                document.body.innerHTML = originalContents;
                // Reinitialize any event listeners or scripts that were lost
                location.reload();
            }, 1000);
        }
    </script>
</body>
</html> 