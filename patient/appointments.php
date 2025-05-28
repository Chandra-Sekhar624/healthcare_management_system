<?php
// Start session
session_start();

// Include database configuration
// include '../includes/config.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get patient information
$patient_id = $_SESSION['user_id'];
$patient_name = $_SESSION['user_name'];

// Placeholder data (in a real system, you would fetch this from the database)
$upcoming_appointments = [
    [
        'id' => 1,
        'doctor_name' => 'Dr. John Williams',
        'specialty' => 'Cardiology',
        'date' => '2023-06-15',
        'time' => '10:00 AM',
        'status' => 'confirmed',
        'purpose' => 'Regular check-up',
        'location' => 'Main Hospital, Room 205'
    ],
    [
        'id' => 2,
        'doctor_name' => 'Dr. Sarah Johnson',
        'specialty' => 'Dermatology',
        'date' => '2023-06-20',
        'time' => '02:30 PM',
        'status' => 'pending',
        'purpose' => 'Skin consultation',
        'location' => 'North Clinic, Room 102'
    ],
    [
        'id' => 3,
        'doctor_name' => 'Dr. Michael Brown',
        'specialty' => 'Orthopedics',
        'date' => '2023-06-25',
        'time' => '11:15 AM',
        'status' => 'confirmed',
        'purpose' => 'Follow-up',
        'location' => 'Main Hospital, Room 310'
    ]
];

$past_appointments = [
    [
        'id' => 4,
        'doctor_name' => 'Dr. John Williams',
        'specialty' => 'Cardiology',
        'date' => '2023-05-15',
        'time' => '09:00 AM',
        'status' => 'completed',
        'purpose' => 'Blood pressure check',
        'location' => 'Main Hospital, Room 205',
        'notes' => 'Blood pressure normal. Continue medications.'
    ],
    [
        'id' => 5,
        'doctor_name' => 'Dr. Emily Rodriguez',
        'specialty' => 'Endocrinology',
        'date' => '2023-04-22',
        'time' => '11:30 AM',
        'status' => 'completed',
        'purpose' => 'Diabetes management',
        'location' => 'East Clinic, Room 118',
        'notes' => 'Blood sugar levels improved. Adjusted insulin dosage.'
    ],
    [
        'id' => 6,
        'doctor_name' => 'Dr. Michael Brown',
        'specialty' => 'Orthopedics',
        'date' => '2023-03-10',
        'time' => '03:45 PM',
        'status' => 'cancelled',
        'purpose' => 'Knee pain',
        'location' => 'Main Hospital, Room 310',
        'notes' => 'Cancelled by patient.'
    ]
];

// Available time slots for booking new appointments
$available_time_slots = [
    'Dr. John Williams' => [
        ['date' => '2023-06-30', 'time' => '09:00 AM'],
        ['date' => '2023-06-30', 'time' => '10:00 AM'],
        ['date' => '2023-07-01', 'time' => '02:00 PM'],
        ['date' => '2023-07-01', 'time' => '03:00 PM'],
    ],
    'Dr. Sarah Johnson' => [
        ['date' => '2023-06-28', 'time' => '11:00 AM'],
        ['date' => '2023-06-29', 'time' => '01:30 PM'],
        ['date' => '2023-07-02', 'time' => '10:30 AM'],
        ['date' => '2023-07-02', 'time' => '02:30 PM'],
    ],
    'Dr. Michael Brown' => [
        ['date' => '2023-06-29', 'time' => '09:15 AM'],
        ['date' => '2023-06-30', 'time' => '11:45 AM'],
        ['date' => '2023-07-03', 'time' => '02:15 PM'],
        ['date' => '2023-07-03', 'time' => '04:00 PM'],
    ],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments | Patient Dashboard | HealthConnect</title>
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
                    <img src="../img/patient-avatar.jpg" alt="Patient" class="rounded-circle">
                </div>
                <div class="user-info">
                    <h6 class="mb-0"><?php echo htmlspecialchars($patient_name); ?></h6>
                    <span class="text-muted small">Patient</span>
                </div>
            </div>
            <ul class="sidebar-nav list-unstyled">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item active">
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
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-calendar-check text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">June 12, 2023</div>
                                    <span>Your appointment with Dr. John Williams has been confirmed</span>
                                </div>
                            </a>
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
                                    <div class="text-truncate">Your lab results are now available.</div>
                                    <div class="small text-muted">Dr. John Williams · 2h</div>
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
                    <h1 class="h3 mb-0 text-gray-800">My Appointments</h1>
                    <button class="d-none d-sm-inline-block btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#newAppointmentModal">
                        <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Book New Appointment
                    </button>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Appointment Summary -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col me-2">
                                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Upcoming Appointments</div>
                                                <div class="h5 mb-0 fw-bold text-gray-800"><?php echo count($upcoming_appointments); ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col me-2">
                                                <div class="text-xs fw-bold text-success text-uppercase mb-1">Completed Appointments</div>
                                                <div class="h5 mb-0 fw-bold text-gray-800">
                                                    <?php 
                                                    $completed_count = 0;
                                                    foreach ($past_appointments as $appointment) {
                                                        if ($appointment['status'] === 'completed') {
                                                            $completed_count++;
                                                        }
                                                    }
                                                    echo $completed_count;
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col me-2">
                                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">Cancelled Appointments</div>
                                                <div class="h5 mb-0 fw-bold text-gray-800">
                                                    <?php 
                                                    $cancelled_count = 0;
                                                    foreach ($past_appointments as $appointment) {
                                                        if ($appointment['status'] === 'cancelled') {
                                                            $cancelled_count++;
                                                        }
                                                    }
                                                    echo $cancelled_count;
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Appointments Tabs -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <ul class="nav nav-tabs card-header-tabs" id="appointmentTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">Upcoming Appointments</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false">Past Appointments</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="appointmentTabsContent">
                                    <!-- Upcoming Appointments Tab -->
                                    <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                                        <?php if (count($upcoming_appointments) > 0): ?>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Doctor</th>
                                                            <th>Specialty</th>
                                                            <th>Date</th>
                                                            <th>Time</th>
                                                            <th>Location</th>
                                                            <th>Purpose</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($upcoming_appointments as $appointment): ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                                                                <td><?php echo htmlspecialchars($appointment['specialty']); ?></td>
                                                                <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                                                                <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                                                <td><?php echo htmlspecialchars($appointment['location']); ?></td>
                                                                <td><?php echo htmlspecialchars($appointment['purpose']); ?></td>
                                                                <td>
                                                                    <span class="badge bg-<?php echo $appointment['status'] === 'confirmed' ? 'success' : 'warning'; ?>">
                                                                        <?php echo ucfirst(htmlspecialchars($appointment['status'])); ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group btn-group-sm" role="group">
                                                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal<?php echo $appointment['id']; ?>">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#rescheduleModal<?php echo $appointment['id']; ?>">
                                                                            <i class="fas fa-clock"></i>
                                                                        </button>
                                                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal<?php echo $appointment['id']; ?>">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center py-5">
                                                <i class="fas fa-calendar fa-4x text-muted mb-3"></i>
                                                <h5 class="text-muted">No upcoming appointments</h5>
                                                <p>You don't have any scheduled appointments at the moment.</p>
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAppointmentModal">
                                                    <i class="fas fa-plus me-1"></i> Book New Appointment
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Past Appointments Tab -->
                                    <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                                        <?php if (count($past_appointments) > 0): ?>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Doctor</th>
                                                            <th>Specialty</th>
                                                            <th>Date</th>
                                                            <th>Time</th>
                                                            <th>Location</th>
                                                            <th>Purpose</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($past_appointments as $appointment): ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                                                                <td><?php echo htmlspecialchars($appointment['specialty']); ?></td>
                                                                <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                                                                <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                                                <td><?php echo htmlspecialchars($appointment['location']); ?></td>
                                                                <td><?php echo htmlspecialchars($appointment['purpose']); ?></td>
                                                                <td>
                                                                    <span class="badge bg-<?php echo ($appointment['status'] === 'completed') ? 'success' : (($appointment['status'] === 'cancelled') ? 'danger' : 'secondary'); ?>">
                                                                        <?php echo ucfirst(htmlspecialchars($appointment['status'])); ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group btn-group-sm" role="group">
                                                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewPastAppointmentModal<?php echo $appointment['id']; ?>">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                        <?php if ($appointment['status'] === 'completed'): ?>
                                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookFollowUpModal<?php echo $appointment['id']; ?>">
                                                                            <i class="fas fa-calendar-plus"></i>
                                                                        </button>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center py-5">
                                                <i class="fas fa-history fa-4x text-muted mb-3"></i>
                                                <h5 class="text-muted">No past appointments</h5>
                                                <p>You don't have any past appointment history.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Appointment Modal -->
    <div class="modal fade" id="newAppointmentModal" tabindex="-1" aria-labelledby="newAppointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newAppointmentModalLabel">Book New Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="doctorSelect" class="form-label">Select Doctor</label>
                                <select class="form-select" id="doctorSelect" required>
                                    <option value="" selected disabled>Choose a doctor...</option>
                                    <option value="Dr. John Williams">Dr. John Williams - Cardiology</option>
                                    <option value="Dr. Sarah Johnson">Dr. Sarah Johnson - Dermatology</option>
                                    <option value="Dr. Michael Brown">Dr. Michael Brown - Orthopedics</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="appointmentType" class="form-label">Appointment Type</label>
                                <select class="form-select" id="appointmentType" required>
                                    <option value="" selected disabled>Select type...</option>
                                    <option value="Regular Check-up">Regular Check-up</option>
                                    <option value="Consultation">Consultation</option>
                                    <option value="Follow-up">Follow-up</option>
                                    <option value="Emergency">Emergency</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="appointmentDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="appointmentDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="appointmentTime" class="form-label">Time</label>
                                <select class="form-select" id="appointmentTime" required>
                                    <option value="" selected disabled>Available time slots</option>
                                    <option value="09:00 AM">09:00 AM</option>
                                    <option value="10:00 AM">10:00 AM</option>
                                    <option value="11:00 AM">11:00 AM</option>
                                    <option value="01:00 PM">01:00 PM</option>
                                    <option value="02:00 PM">02:00 PM</option>
                                    <option value="03:00 PM">03:00 PM</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentReason" class="form-label">Reason for Visit</label>
                            <textarea class="form-control" id="appointmentReason" rows="3" placeholder="Please describe your symptoms or reason for this appointment"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="insuranceCheck">
                            <label class="form-check-label" for="insuranceCheck">
                                I'll be using insurance for this visit
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Book Appointment</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Appointment Modal (Sample for first appointment) -->
    <div class="modal fade" id="viewAppointmentModal1" tabindex="-1" aria-labelledby="viewAppointmentModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewAppointmentModalLabel1">Appointment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="appointment-details">
                        <p class="text-center mb-4">
                            <span class="badge bg-success">Confirmed</span>
                        </p>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Doctor:</div>
                            <div class="col-sm-8">Dr. John Williams</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Specialty:</div>
                            <div class="col-sm-8">Cardiology</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Date:</div>
                            <div class="col-sm-8">June 15, 2023</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Time:</div>
                            <div class="col-sm-8">10:00 AM</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Location:</div>
                            <div class="col-sm-8">Main Hospital, Room 205</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Purpose:</div>
                            <div class="col-sm-8">Regular check-up</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Notes:</div>
                            <div class="col-sm-8">Please arrive 15 minutes before your appointment. Bring your insurance card and list of current medications.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#rescheduleModal1">Reschedule</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal1">Cancel Appointment</button>
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

        // Dynamic appointment time slots based on doctor and date selection
        document.getElementById('doctorSelect').addEventListener('change', function() {
            updateAvailableTimeSlots();
        });
        
        document.getElementById('appointmentDate').addEventListener('change', function() {
            updateAvailableTimeSlots();
        });
        
        function updateAvailableTimeSlots() {
            let doctor = document.getElementById('doctorSelect').value;
            let date = document.getElementById('appointmentDate').value;
            let timeSelect = document.getElementById('appointmentTime');
            
            // Clear existing options except the placeholder
            while (timeSelect.options.length > 1) {
                timeSelect.remove(1);
            }
            
            // If both doctor and date are selected, populate available times
            if (doctor && date) {
                // In a real app, this would make an AJAX call to get available slots
                // For now, we'll add some dummy time slots
                const times = ['09:00 AM', '10:00 AM', '11:00 AM', '01:00 PM', '02:00 PM', '03:00 PM'];
                
                times.forEach(time => {
                    let option = document.createElement('option');
                    option.value = time;
                    option.text = time;
                    timeSelect.add(option);
                });
            }
        }
    </script>
</body>
</html> 