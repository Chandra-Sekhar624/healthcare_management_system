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
$upcoming_appointments = [
    [
        'id' => 1,
        'patient_name' => 'Jane Smith',
        'date' => '2023-06-15',
        'time' => '10:00 AM',
        'status' => 'confirmed'
    ],
    [
        'id' => 2,
        'patient_name' => 'Robert Johnson',
        'date' => '2023-06-15',
        'time' => '11:30 AM',
        'status' => 'confirmed'
    ],
    [
        'id' => 3,
        'patient_name' => 'Emily Williams',
        'date' => '2023-06-16',
        'time' => '09:15 AM',
        'status' => 'pending'
    ]
];

$recent_patients = [
    [
        'id' => 1,
        'name' => 'Jane Smith',
        'age' => 35,
        'last_visit' => '2023-06-01',
        'condition' => 'Hypertension'
    ],
    [
        'id' => 2,
        'name' => 'Robert Johnson',
        'age' => 42,
        'last_visit' => '2023-05-28',
        'condition' => 'Diabetes'
    ],
    [
        'id' => 3,
        'name' => 'Emily Williams',
        'age' => 29,
        'last_visit' => '2023-05-15',
        'condition' => 'Pregnancy'
    ]
];

$notifications = [
    [
        'id' => 1,
        'title' => 'New Appointment',
        'message' => 'You have a new appointment request from Emily Williams',
        'time' => '1 hour ago',
        'is_read' => false
    ],
    [
        'id' => 2,
        'title' => 'Appointment Cancelled',
        'message' => 'John Doe cancelled the appointment scheduled for tomorrow',
        'time' => '3 hours ago',
        'is_read' => true
    ],
    [
        'id' => 3,
        'title' => 'Medical Record Updated',
        'message' => 'You have updated Jane Smith\'s medical record',
        'time' => '1 day ago',
        'is_read' => true
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href = "../css/responsive.css">
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
                <li class="nav-item active">
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
                <li class="nav-item">
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
                            <span class="badge bg-danger badge-counter">7</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="messagesDropdown">
                            <h6 class="dropdown-header">Message Center</h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image me-3">
                                    <img class="rounded-circle" src="../img/patient-1.jpg" alt="Patient">
                                    <div class="status-indicator bg-success"></div>
                                </div>
                                <div class="fw-bold">
                                    <div class="text-truncate">Hi there! I was wondering about my test results.</div>
                                    <div class="small text-muted">Jane Smith · 58m</div>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image me-3">
                                    <img class="rounded-circle" src="../img/patient-2.jpg" alt="Patient">
                                    <div class="status-indicator"></div>
                                </div>
                                <div>
                                    <div class="text-truncate">When can I schedule my next appointment?</div>
                                    <div class="small text-muted">Robert Johnson · 1d</div>
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-primary" href="#">Read More Messages</a>
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
                            <a class="dropdown-item" href="#">
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
                    <h1 class="h3 mb-0 text-gray-800">Doctor Dashboard</h1>
                    <a href="generate_report.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                    </a>
                </div>

                <!-- Dashboard Widgets -->
                <div class="row">
                    <!-- Total Patients -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Patients</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">42</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-injured fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Appointments -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Today's Appointments</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">5</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Requests -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Pending Requests</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">3</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Monthly Earnings</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">$4,000</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Appointments and Recent Patients -->
                <div class="row ">
                    <!-- Upcoming Appointments -->
                    <div class="col-lg-6 mb-4 col-12">
                        <div class="card card1 shadow mb-4 col-12">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between col-12">
                                <h6 class="m-0 fw-bold text-primary">Upcoming Appointments</h6>
                                <a href="appointment.php" class="btn btn-sm btn-primary">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Patient</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class='col-12'>
                                            <?php foreach ($upcoming_appointments as $appointment): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                                                    <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $appointment['status'] === 'confirmed' ? 'success' : 'warning'; ?>">
                                                            <?php echo ucfirst(htmlspecialchars($appointment['status'])); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                                            <a href="#" class="btn btn-sm btn-outline-danger">Cancel</a>
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

                    <!-- Recent Patients -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 fw-bold text-primary">Recent Patients</h6>
                                <a href="my-patients.php" class="btn btn-sm btn-primary">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Patient</th>
                                                <th>Age</th>
                                                <th>Last Visit</th>
                                                <th>Condition</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_patients as $patient): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($patient['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($patient['age']); ?></td>
                                                    <td><?php echo htmlspecialchars($patient['last_visit']); ?></td>
                                                    <td><?php echo htmlspecialchars($patient['condition']); ?></td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="#" class="btn btn-sm btn-outline-primary">Records</a>
                                                            <a href="#" class="btn btn-sm btn-outline-success">Message</a>
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
                    <!-- Appointments Overview -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">Appointments Overview</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <div style="height: 300px; background-color: #f8f9fc; display: flex; align-items: center; justify-content: center;">
                                        <p class="text-center text-muted">Appointment chart will be displayed here.</p>
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
                                    <a href="schedule.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-calendar-plus me-2"></i> Schedule Appointment
                                    </a>
                                    <a href="medical-records.php" class="btn btn-success btn-sm">
                                        <i class="fas fa-file-medical me-2"></i> Create Medical Record
                                    </a>
                                    <a href="prescription.php" class="btn btn-info btn-sm">
                                        <i class="fas fa-prescription-bottle-alt me-2"></i> Write Prescription
                                    </a>
                                    <a href="#" class="btn btn-warning btn-sm">
                                        <i class="fas fa-envelope me-2"></i> Send Message to Patient
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm">
                                        <i class="fas fa-briefcase-medical me-2"></i> Emergency Case
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
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