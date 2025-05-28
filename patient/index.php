<?php
// Start session
session_start();

// Include database configuration and functions
include '../includes/config.php';
include '../includes/patient_functions.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get patient information
$patient_id = $_SESSION['user_id'];
$patient_name = $_SESSION['user_name'];

// Get profile image path
$profile_image = getPatientProfileImage($conn, $patient_id);

// Placeholder data (in a real system, you would fetch this from the database)
$upcoming_appointments = [
    [
        'id' => 1,
        'doctor_name' => 'Dr. John Williams',
        'specialty' => 'Cardiology',
        'date' => '2023-06-15',
        'time' => '10:00 AM',
        'status' => 'confirmed'
    ],
    [
        'id' => 2,
        'doctor_name' => 'Dr. Sarah Johnson',
        'specialty' => 'Dermatology',
        'date' => '2023-06-20',
        'time' => '02:30 PM',
        'status' => 'pending'
    ],
    [
        'id' => 3,
        'doctor_name' => 'Dr. Michael Brown',
        'specialty' => 'Orthopedics',
        'date' => '2023-06-25',
        'time' => '11:15 AM',
        'status' => 'confirmed'
    ]
];

$recent_prescriptions = [
    [
        'id' => 1,
        'medication' => 'Lisinopril 10mg',
        'doctor' => 'Dr. John Williams',
        'date_prescribed' => '2023-06-01',
        'instructions' => 'Take once daily'
    ],
    [
        'id' => 2,
        'medication' => 'Metformin 500mg',
        'doctor' => 'Dr. John Williams',
        'date_prescribed' => '2023-05-15',
        'instructions' => 'Take twice daily with meals'
    ],
    [
        'id' => 3,
        'medication' => 'Ibuprofen 400mg',
        'doctor' => 'Dr. Michael Brown',
        'date_prescribed' => '2023-05-10',
        'instructions' => 'Take as needed for pain'
    ]
];

$notifications = [
    [
        'id' => 1,
        'title' => 'Appointment Confirmed',
        'message' => 'Your appointment with Dr. John Williams has been confirmed',
        'time' => '1 hour ago',
        'is_read' => false
    ],
    [
        'id' => 2,
        'title' => 'New Prescription',
        'message' => 'Dr. John Williams has prescribed a new medication for you',
        'time' => '1 day ago',
        'is_read' => true
    ],
    [
        'id' => 3,
        'title' => 'Appointment Reminder',
        'message' => 'Reminder: You have an appointment tomorrow with Dr. Sarah Johnson',
        'time' => '2 days ago',
        'is_read' => true
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
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
                    <img src="<?php echo $profile_image; ?>" alt="Patient" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                </div>
                <div class="user-info">
                    <h6 class="mb-0"><?php echo htmlspecialchars($patient_name); ?></h6>
                    <span class="text-muted small">Patient</span>
                </div>
            </div>
            <ul class="sidebar-nav list-unstyled">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="appointments.php">
                        <i class="fas fa-calendar-check me-2"></i> My Appointments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="find-doctor.php">
                        <i class="fas fa-user-md me-2"></i> Find Doctor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="medical-records.php">
                        <i class="fas fa-notes-medical me-2"></i> Medical Records
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="prescriptions.php">
                        <i class="fas fa-prescription me-2"></i> Prescriptions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="messages.php">
                        <i class="fas fa-envelope me-2"></i> Messages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="payments.php">
                        <i class="fas fa-credit-card me-2"></i> Payments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">
                        <i class="fas fa-user me-2"></i> My Profile
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
        <div class="dashboard-content col-12">
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
                            <?php foreach ($notifications as $notification): ?>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="me-3">
                                        <div class="icon-circle bg-<?php echo $notification['is_read'] ? 'secondary' : 'primary'; ?>">
                                            <i class="fas fa-<?php echo $notification['is_read'] ? 'check' : 'bell'; ?> text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($notification['time']); ?></div>
                                        <span class="<?php echo $notification['is_read'] ? '' : 'fw-bold'; ?>"><?php echo htmlspecialchars($notification['title']); ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            <a class="dropdown-item text-center small text-primary" href="#">Show All Notifications</a>
                        </div>
                    </li>

                    <!-- Messages -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-envelope fa-fw"></i>
                            <span class="badge bg-danger badge-counter">2</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="messagesDropdown">
                            <h6 class="dropdown-header">Message Center</h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image me-3">
                                    <img class="rounded-circle" src="../img/doctor-1.jpg" alt="Doctor">
                                    <div class="status-indicator bg-success"></div>
                                </div>
                                <div class="fw-bold">
                                    <div class="text-truncate">Your lab results are now available. Please review them at your earliest convenience.</div>
                                    <div class="small text-muted">Dr. John Williams · 2h</div>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image me-3">
                                    <img class="rounded-circle" src="../img/doctor-2.jpg" alt="Doctor">
                                    <div class="status-indicator"></div>
                                </div>
                                <div>
                                    <div class="text-truncate">Please let me know if you have any questions about the new prescription.</div>
                                    <div class="small text-muted">Dr. Sarah Johnson · 1d</div>
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-primary" href="#">Read More Messages</a>
                        </div>
                    </li>

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($patient_name); ?></span>
                            <img class="img-profile rounded-circle" src="../img/patient-avatar.jpg">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="profile.php">
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
                    <h1 class="h3 mb-0 text-gray-800">Patient Dashboard</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-download fa-sm text-white-50"></i> Download Medical Summary
                    </a>
                </div>

                <!-- Dashboard Widgets -->
                <div class="row">
                    <!-- Upcoming Appointments -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Upcoming Appointments</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">3</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prescriptions -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Active Prescriptions</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">3</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-prescription fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">New Messages</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">2</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-envelope fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Health Stats -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Health Status</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 mb-0 me-3 fw-bold text-gray-800">Good</div>
                                            </div>
                                            <div class="col">
                                                <div class="progress progress-sm me-2">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-heartbeat fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Appointments and Prescriptions -->
                <div class="row">
                    <!-- Upcoming Appointments -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 fw-bold text-primary">Upcoming Appointments</h6>
                                <a href="appointments.php" class="btn btn-sm btn-primary">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Specialty</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($upcoming_appointments as $appointment): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($appointment['specialty']); ?></td>
                                                    <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                                                    <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $appointment['status'] === 'confirmed' ? 'success' : 'warning'; ?>">
                                                            <?php echo ucfirst(htmlspecialchars($appointment['status'])); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="appointments.php" class="btn btn-sm btn-outline-primary">Details</a>
                                                            <a href="appointments.php" class="btn btn-sm btn-outline-danger">Cancel</a>
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

                    <!-- Recent Prescriptions -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 fw-bold text-primary">Recent Prescriptions</h6>
                                <a href="prescriptions.php" class="btn btn-sm btn-primary">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Medication</th>
                                                <th>Prescribed By</th>
                                                <th>Date</th>
                                                <th>Instructions</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_prescriptions as $prescription): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($prescription['medication']); ?></td>
                                                    <td><?php echo htmlspecialchars($prescription['doctor']); ?></td>
                                                    <td><?php echo htmlspecialchars($prescription['date_prescribed']); ?></td>
                                                    <td><?php echo htmlspecialchars($prescription['instructions']); ?></td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="prescriptions.php" class="btn btn-sm btn-outline-primary">Details</a>
                                                            <a href="prescriptions.php" class="btn btn-sm btn-outline-success">Refill</a>
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

                <!-- Additional Content -->
                <div class="row">
                    <!-- Health Metrics -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">Health Metrics Overview</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <div style="height: 300px; background-color: #f8f9fc; display: flex; align-items: center; justify-content: center;">
                                        <p class="text-center text-muted">Health metrics chart will be displayed here.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="find-doctor.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-calendar-plus me-2"></i> Book New Appointment
                                    </a>
                                    <a href="messages.php" class="btn btn-success btn-sm">
                                        <i class="fas fa-envelope me-2"></i> Contact Doctor
                                    </a>
                                    <a href="prescriptions.php" class="btn btn-info btn-sm">
                                        <i class="fas fa-prescription-bottle-alt me-2"></i> Request Refill
                                    </a>
                                    <a href="medical-records.php" class="btn btn-warning btn-sm">
                                        <i class="fas fa-file-medical me-2"></i> View Medical Records
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#emergencyServicesModal">
                                        <i class="fas fa-ambulance me-2"></i> Emergency Services
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Services Modal -->
    <div class="modal fade" id="emergencyServicesModal" tabindex="-1" aria-labelledby="emergencyServicesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="emergencyServicesModalLabel">
                        <i class="fas fa-ambulance me-2"></i> Emergency Services
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> For life-threatening emergencies:</h5>
                                <p class="mb-0">Please call <strong>911</strong> or your local emergency number immediately.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-phone me-2"></i> Emergency Contacts</h5>
                                    <ul class="list-group list-group-flush mt-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Ambulance
                                            <span class="badge bg-danger rounded-pill">911</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Hospital Emergency
                                            <span class="badge bg-danger rounded-pill">+1-555-123-4567</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Poison Control
                                            <span class="badge bg-danger rounded-pill">+1-800-222-1222</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-hospital me-2"></i> Nearest Hospitals</h5>
                                    <ul class="list-group list-group-flush mt-3">
                                        <li class="list-group-item">
                                            <h6 class="mb-1">City General Hospital</h6>
                                            <p class="mb-1 small">123 Main Street, City</p>
                                            <p class="mb-0 small">Distance: 2.3 miles</p>
                                        </li>
                                        <li class="list-group-item">
                                            <h6 class="mb-1">Memorial Medical Center</h6>
                                            <p class="mb-1 small">456 Oak Avenue, City</p>
                                            <p class="mb-0 small">Distance: 3.7 miles</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-video me-2"></i> Telemedicine Emergency</h5>
                                    <p class="card-text">Connect with an emergency doctor via video call for urgent but non-life-threatening situations.</p>
                                    <a href="#" class="btn btn-danger"><i class="fas fa-video me-2"></i> Start Emergency Video Call</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-first-aid me-2"></i> First Aid Information</h5>
                                    <p class="card-text">Access first aid guides for common emergency situations.</p>
                                    <div class="d-grid gap-2">
                                        <a href="https://youtu.be/EZaG6MbU7wQ?si=bs6TLG1hYUAWON5e" target="_blank" class="btn btn-outline-danger">CPR Guide</a>
                                        <a href="https://youtu.be/XOTbjDGZ7wg?si=tK5WieNcnmwr3sSX" target="_blank" class="btn btn-outline-danger">Choking</a>
                                        <a href="https://youtu.be/MZQ7nYsK11Q?si=6MEFeHESwGO7sS6w" target="_blank" class="btn btn-outline-danger">Bleeding Control</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="tel:911" class="btn btn-danger"><i class="fas fa-phone me-2"></i> Call 911</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.1.3 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/main.js"></script>
    <script>
        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
        });
    </script>
</body>
</html> 