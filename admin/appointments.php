<?php
// Start session
session_start();

// Include database configuration
// include '../includes/config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get admin information
$admin_id = $_SESSION['user_id'];
$admin_name = $_SESSION['user_name'];

// Placeholder data (in a real system, you would fetch this from the database)
$appointments = [
    [
        'id' => 1,
        'patient_name' => 'Jane Smith',
        'patient_id' => 1,
        'doctor_name' => 'Dr. Robert Wilson',
        'doctor_id' => 3,
        'department' => 'Cardiology',
        'date' => '2023-06-22',
        'time' => '09:00 AM',
        'status' => 'confirmed',
        'type' => 'Regular Checkup',
        'notes' => 'Follow-up on previous treatment',
        'created_at' => '2023-06-15'
    ],
    [
        'id' => 2,
        'patient_name' => 'Robert Johnson',
        'patient_id' => 2,
        'doctor_name' => 'Dr. Sarah Adams',
        'doctor_id' => 2,
        'department' => 'Neurology',
        'date' => '2023-06-22',
        'time' => '11:30 AM',
        'status' => 'pending',
        'type' => 'New Consultation',
        'notes' => 'Recurring headaches',
        'created_at' => '2023-06-16'
    ],
    [
        'id' => 3,
        'patient_name' => 'Emily Williams',
        'patient_id' => 3,
        'doctor_name' => 'Dr. Michael Chen',
        'doctor_id' => 4,
        'department' => 'Dermatology',
        'date' => '2023-06-23',
        'time' => '02:15 PM',
        'status' => 'confirmed',
        'type' => 'Follow-up',
        'notes' => 'Skin rash treatment follow-up',
        'created_at' => '2023-06-17'
    ],
    [
        'id' => 4,
        'patient_name' => 'Thomas Walker',
        'patient_id' => 4,
        'doctor_name' => 'Dr. James Peterson',
        'doctor_id' => 1,
        'department' => 'Orthopedics',
        'date' => '2023-06-21',
        'time' => '10:00 AM',
        'status' => 'completed',
        'type' => 'Post-Surgery Checkup',
        'notes' => 'Recovery progress after knee surgery',
        'created_at' => '2023-06-14'
    ],
    [
        'id' => 5,
        'patient_name' => 'Julia Martinez',
        'patient_id' => 5,
        'doctor_name' => 'Dr. Sarah Adams',
        'doctor_id' => 2,
        'department' => 'Neurology',
        'date' => '2023-06-24',
        'time' => '01:30 PM',
        'status' => 'canceled',
        'type' => 'Regular Checkup',
        'notes' => 'Patient requested cancellation',
        'created_at' => '2023-06-18'
    ]
];

// Handle appointment status changes (in a real system, this would update the database)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $appointment_id = $_GET['id'];
    
    // Placeholder for status change actions
    $status_message = '';
    
    if ($action === 'confirm') {
        $status_message = "Appointment ID $appointment_id has been confirmed.";
    } elseif ($action === 'cancel') {
        $status_message = "Appointment ID $appointment_id has been canceled.";
    } elseif ($action === 'complete') {
        $status_message = "Appointment ID $appointment_id has been marked as completed.";
    } elseif ($action === 'delete') {
        $status_message = "Appointment ID $appointment_id has been deleted.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments | Admin Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                    <img src="../img/admin-avatar.jpg" alt="Admin" class="rounded-circle">
                </div>
                <div class="user-info">
                    <h6 class="mb-0"><?php echo htmlspecialchars($admin_name); ?></h6>
                    <span class="text-muted small">Administrator</span>
                </div>
            </div>
            <ul class="sidebar-nav list-unstyled">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="doctors.php">
                        <i class="fas fa-user-md me-2"></i> Doctors
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="patients.php">
                        <i class="fas fa-user-injured me-2"></i> Patients
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="appointments.php">
                        <i class="fas fa-calendar-check me-2"></i> Appointments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="specialties.php">
                        <i class="fas fa-stethoscope me-2"></i> Specialties
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reports.php">
                        <i class="fas fa-chart-bar me-2"></i> Reports
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
                                        <i class="fas fa-calendar-plus text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">1 hour ago</div>
                                    <span class="fw-bold">New Appointment Request</span>
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-primary" href="#">Show All Notifications</a>
                        </div>
                    </li>

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($admin_name); ?></span>
                            <img class="img-profile rounded-circle" src="../img/admin-avatar.jpg">
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
                    <h1 class="h3 mb-0 text-gray-800">Manage Appointments</h1>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
                        <i class="fas fa-calendar-plus fa-sm text-white-50 me-2"></i>Schedule New Appointment
                    </a>
                </div>

                <?php if (isset($status_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($status_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Appointment Filters -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Filters</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Statuses</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                    <option value="canceled">Canceled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="departmentFilter">
                                    <option value="">All Departments</option>
                                    <option value="Cardiology">Cardiology</option>
                                    <option value="Neurology">Neurology</option>
                                    <option value="Orthopedics">Orthopedics</option>
                                    <option value="Dermatology">Dermatology</option>
                                    <option value="Pediatrics">Pediatrics</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="dateFilter" placeholder="Filter by date">
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search..." id="searchAppointments">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointments List -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Appointments List</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="appointmentsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient Name</th>
                                        <th>Doctor Name</th>
                                        <th>Department</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appointments as $appointment): ?>
                                        <tr>
                                            <td><?php echo $appointment['id']; ?></td>
                                            <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['department']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['type']); ?></td>
                                            <td>
                                                <?php 
                                                $status_class = '';
                                                switch($appointment['status']) {
                                                    case 'confirmed':
                                                        $status_class = 'success';
                                                        break;
                                                    case 'pending':
                                                        $status_class = 'warning';
                                                        break;
                                                    case 'completed':
                                                        $status_class = 'primary';
                                                        break;
                                                    case 'canceled':
                                                        $status_class = 'danger';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge bg-<?php echo $status_class; ?>">
                                                    <?php echo ucfirst(htmlspecialchars($appointment['status'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal<?php echo $appointment['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAppointmentModal<?php echo $appointment['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($appointment['status'] === 'pending'): ?>
                                                        <a href="?action=confirm&id=<?php echo $appointment['id']; ?>" class="btn btn-success">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($appointment['status'] !== 'canceled' && $appointment['status'] !== 'completed'): ?>
                                                        <a href="?action=cancel&id=<?php echo $appointment['id']; ?>" class="btn btn-warning">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($appointment['status'] === 'confirmed'): ?>
                                                        <a href="?action=complete&id=<?php echo $appointment['id']; ?>" class="btn btn-primary">
                                                            <i class="fas fa-calendar-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAppointmentModal<?php echo $appointment['id']; ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- View Appointment Modal -->
                                        <div class="modal fade" id="viewAppointmentModal<?php echo $appointment['id']; ?>" tabindex="-1" aria-labelledby="viewAppointmentModalLabel<?php echo $appointment['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewAppointmentModalLabel<?php echo $appointment['id']; ?>">
                                                            Appointment Details
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6 class="text-primary mb-3">Appointment Information</h6>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Appointment ID:</div>
                                                                    <div class="col-md-7">APT<?php echo str_pad($appointment['id'], 5, '0', STR_PAD_LEFT); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Date:</div>
                                                                    <div class="col-md-7"><?php echo htmlspecialchars($appointment['date']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Time:</div>
                                                                    <div class="col-md-7"><?php echo htmlspecialchars($appointment['time']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Type:</div>
                                                                    <div class="col-md-7"><?php echo htmlspecialchars($appointment['type']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Status:</div>
                                                                    <div class="col-md-7">
                                                                        <span class="badge bg-<?php echo $status_class; ?>">
                                                                            <?php echo ucfirst(htmlspecialchars($appointment['status'])); ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Created:</div>
                                                                    <div class="col-md-7"><?php echo htmlspecialchars($appointment['created_at']); ?></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6 class="text-primary mb-3">Patient Information</h6>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Name:</div>
                                                                    <div class="col-md-7"><?php echo htmlspecialchars($appointment['patient_name']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Patient ID:</div>
                                                                    <div class="col-md-7">PAT<?php echo str_pad($appointment['patient_id'], 5, '0', STR_PAD_LEFT); ?></div>
                                                                </div>
                                                                
                                                                <h6 class="text-primary mb-3 mt-4">Doctor Information</h6>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Name:</div>
                                                                    <div class="col-md-7"><?php echo htmlspecialchars($appointment['doctor_name']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Doctor ID:</div>
                                                                    <div class="col-md-7">DOC<?php echo str_pad($appointment['doctor_id'], 5, '0', STR_PAD_LEFT); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-5 fw-bold">Department:</div>
                                                                    <div class="col-md-7"><?php echo htmlspecialchars($appointment['department']); ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row mt-4">
                                                            <div class="col-12">
                                                                <h6 class="text-primary mb-3">Notes</h6>
                                                                <p><?php echo htmlspecialchars($appointment['notes']); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <a href="#" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editAppointmentModal<?php echo $appointment['id']; ?>">Edit Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Delete Appointment Confirmation Modal -->
                                        <div class="modal fade" id="deleteAppointmentModal<?php echo $appointment['id']; ?>" tabindex="-1" aria-labelledby="deleteAppointmentModalLabel<?php echo $appointment['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteAppointmentModalLabel<?php echo $appointment['id']; ?>">Confirm Delete</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete the appointment for <?php echo htmlspecialchars($appointment['patient_name']); ?> with <?php echo htmlspecialchars($appointment['doctor_name']); ?> on <?php echo htmlspecialchars($appointment['date']); ?> at <?php echo htmlspecialchars($appointment['time']); ?>? This action cannot be undone.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <a href="?action=delete&id=<?php echo $appointment['id']; ?>" class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Appointment Modal -->
    <div class="modal fade" id="addAppointmentModal" tabindex="-1" aria-labelledby="addAppointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAppointmentModalLabel">Schedule New Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="patientSelect" class="form-label">Patient</label>
                                <select class="form-select" id="patientSelect" name="patient_id" required>
                                    <option value="" selected disabled>Select patient</option>
                                    <option value="1">Jane Smith</option>
                                    <option value="2">Robert Johnson</option>
                                    <option value="3">Emily Williams</option>
                                    <option value="4">Thomas Walker</option>
                                    <option value="5">Julia Martinez</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="departmentSelect" class="form-label">Department</label>
                                <select class="form-select" id="departmentSelect" name="department" required>
                                    <option value="" selected disabled>Select department</option>
                                    <option value="Cardiology">Cardiology</option>
                                    <option value="Neurology">Neurology</option>
                                    <option value="Orthopedics">Orthopedics</option>
                                    <option value="Dermatology">Dermatology</option>
                                    <option value="Pediatrics">Pediatrics</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="doctorSelect" class="form-label">Doctor</label>
                                <select class="form-select" id="doctorSelect" name="doctor_id" required>
                                    <option value="" selected disabled>Select doctor</option>
                                    <option value="1">Dr. James Peterson (Orthopedics)</option>
                                    <option value="2">Dr. Sarah Adams (Neurology)</option>
                                    <option value="3">Dr. Robert Wilson (Cardiology)</option>
                                    <option value="4">Dr. Michael Chen (Dermatology)</option>
                                    <option value="5">Dr. Jessica Lee (Pediatrics)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="appointmentType" class="form-label">Appointment Type</label>
                                <select class="form-select" id="appointmentType" name="appointment_type" required>
                                    <option value="" selected disabled>Select type</option>
                                    <option value="New Consultation">New Consultation</option>
                                    <option value="Follow-up">Follow-up</option>
                                    <option value="Regular Checkup">Regular Checkup</option>
                                    <option value="Emergency">Emergency</option>
                                    <option value="Post-Surgery Checkup">Post-Surgery Checkup</option>
                                    <option value="Lab Test">Lab Test</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="appointmentDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="appointmentDate" name="appointment_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="appointmentTime" class="form-label">Time</label>
                                <input type="time" class="form-control" id="appointmentTime" name="appointment_time" required>
                            </div>
                            <div class="col-12">
                                <label for="appointmentNotes" class="form-label">Notes</label>
                                <textarea class="form-control" id="appointmentNotes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Schedule Appointment</button>
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
        
        // Date filtering functionality
        document.getElementById('dateFilter').addEventListener('change', function() {
            // Filter table by date
            // In a real implementation, this would filter the table rows
            console.log('Filtering by date:', this.value);
        });
        
        // Reset filters
        document.getElementById('resetFilters').addEventListener('click', function() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('departmentFilter').value = '';
            document.getElementById('dateFilter').value = '';
            document.getElementById('searchAppointments').value = '';
            // Reset the table
        });
    </script>
</body>
</html> 