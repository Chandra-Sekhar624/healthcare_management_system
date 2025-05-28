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
$patients = [
    [
        'id' => 1,
        'name' => 'Jane Smith',
        'age' => 35,
        'gender' => 'Female',
        'phone' => '202-555-0187',
        'email' => 'jane.smith@example.com',
        'address' => '123 Main St, Anytown, ST 12345',
        'medical_condition' => 'Hypertension',
        'last_visit' => '2023-06-01',
        'blood_group' => 'A+'
    ],
    [
        'id' => 2,
        'name' => 'Robert Johnson',
        'age' => 42,
        'gender' => 'Male',
        'phone' => '202-555-0134',
        'email' => 'robert.johnson@example.com',
        'address' => '456 Oak Ave, Springfield, ST 54321',
        'medical_condition' => 'Diabetes',
        'last_visit' => '2023-05-28',
        'blood_group' => 'O-'
    ],
    [
        'id' => 3,
        'name' => 'Emily Williams',
        'age' => 29,
        'gender' => 'Female',
        'phone' => '202-555-0192',
        'email' => 'emily.williams@example.com',
        'address' => '789 Pine Rd, Lakeville, ST 67890',
        'medical_condition' => 'Pregnancy',
        'last_visit' => '2023-05-15',
        'blood_group' => 'B+'
    ],
    [
        'id' => 4,
        'name' => 'Michael Brown',
        'age' => 58,
        'gender' => 'Male',
        'phone' => '202-555-0178',
        'email' => 'michael.brown@example.com',
        'address' => '101 Cedar Ln, Riverdale, ST 13579',
        'medical_condition' => 'Arthritis',
        'last_visit' => '2023-06-10',
        'blood_group' => 'AB+'
    ],
    [
        'id' => 5,
        'name' => 'Sarah Thompson',
        'age' => 31,
        'gender' => 'Female',
        'phone' => '202-555-0156',
        'email' => 'sarah.thompson@example.com',
        'address' => '202 Elm St, Hilltop, ST 24680',
        'medical_condition' => 'Asthma',
        'last_visit' => '2023-06-05',
        'blood_group' => 'A-'
    ],
    [
        'id' => 6,
        'name' => 'David Wilson',
        'age' => 47,
        'gender' => 'Male',
        'phone' => '202-555-0143',
        'email' => 'david.wilson@example.com',
        'address' => '303 Maple Dr, Westside, ST 97531',
        'medical_condition' => 'Post-surgery recovery',
        'last_visit' => '2023-06-08',
        'blood_group' => 'O+'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Patients | Doctor Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
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
                <li class="nav-item active">
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
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-user-plus text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">Today</div>
                                    <span>A new patient has been assigned to you</span>
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
                    <h1 class="h3 mb-0 text-gray-800">My Patients</h1>
                    <div>
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                            <i class="fas fa-user-plus fa-sm me-2"></i> Add New Patient
                        </a>
                        <a href="#" class="btn btn-info btn-sm shadow-sm ms-2" onclick="window.print();">
                            <i class="fas fa-print fa-sm me-2"></i> Print
                        </a>
                    </div>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <!-- Patient Statistics Cards -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Patients</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">42</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">New This Month</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">8</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-plus fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Upcoming Appointments</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">12</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Critical Cases</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">3</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patient Search and Filter -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">Find Patients</h6>
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
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <label for="searchName" class="form-label">Patient Name</label>
                                    <input type="text" class="form-control" id="searchName" placeholder="Search by name...">
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <label for="medicalCondition" class="form-label">Medical Condition</label>
                                    <select class="form-select" id="medicalCondition">
                                        <option value="" selected>All Conditions</option>
                                        <option value="hypertension">Hypertension</option>
                                        <option value="diabetes">Diabetes</option>
                                        <option value="arthritis">Arthritis</option>
                                        <option value="asthma">Asthma</option>
                                        <option value="pregnancy">Pregnancy</option>
                                    </select>
                                </div>
                                <div class="col-md-4 align-self-end">
                                    <button type="button" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-2"></i> Search Patients
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Patients Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">All Patients</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="patientsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Contact</th>
                                        <th>Medical Condition</th>
                                        <th>Last Visit</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($patients as $patient): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($patient['id']); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2" style="width: 32px; height: 32px; background-color: #4e73df; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold;">
                                                    <?php echo substr(htmlspecialchars($patient['name']), 0, 1); ?>
                                                </div>
                                                <?php echo htmlspecialchars($patient['name']); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($patient['age']); ?></td>
                                        <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                        <td>
                                            <div><?php echo htmlspecialchars($patient['phone']); ?></div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($patient['email']); ?></div>
                                        </td>
                                        <td><?php echo htmlspecialchars($patient['medical_condition']); ?></td>
                                        <td><?php echo htmlspecialchars($patient['last_visit']); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewPatientModal<?php echo $patient['id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editPatientModal<?php echo $patient['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#prescriptionModal<?php echo $patient['id']; ?>">
                                                    <i class="fas fa-notes-medical"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#scheduleAppointmentModal<?php echo $patient['id']; ?>">
                                                    <i class="fas fa-calendar-plus"></i>
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

    <!-- Add New Patient Modal -->
    <div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPatientModalLabel">Add New Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="birthDate" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="birthDate" required>
                            </div>
                            <div class="col-md-4">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" required>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="bloodGroup" class="form-label">Blood Group</label>
                                <select class="form-select" id="bloodGroup">
                                    <option value="" selected disabled>Select Blood Group</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" placeholder="e.g. 202-555-0123" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="name@example.com">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" rows="2" placeholder="Full address"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="medicalHistory" class="form-label">Medical Condition/History</label>
                            <textarea class="form-control" id="medicalHistory" rows="3" placeholder="Pre-existing conditions, allergies, etc."></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="emergencyContact" class="form-label">Emergency Contact</label>
                                <input type="text" class="form-control" id="emergencyContact" placeholder="Name and relation">
                            </div>
                            <div class="col-md-6">
                                <label for="emergencyPhone" class="form-label">Emergency Phone</label>
                                <input type="tel" class="form-control" id="emergencyPhone" placeholder="e.g. 202-555-0123">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Patient</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Patient Modal -->
    <?php foreach ($patients as $patient): ?>
    <div class="modal fade" id="viewPatientModal<?php echo $patient['id']; ?>" tabindex="-1" aria-labelledby="viewPatientModalLabel<?php echo $patient['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPatientModalLabel<?php echo $patient['id']; ?>">Patient Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="avatar mx-auto mb-3" style="width: 100px; height: 100px; background-color: #4e73df; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 36px; font-weight: bold;">
                                <?php echo substr(htmlspecialchars($patient['name']), 0, 1); ?>
                            </div>
                            <h5><?php echo htmlspecialchars($patient['name']); ?></h5>
                            <p class="text-muted">Patient ID: <?php echo htmlspecialchars($patient['id']); ?></p>
                        </div>
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Personal Information</h6>
                                    <p><strong>Age:</strong> <?php echo htmlspecialchars($patient['age']); ?></p>
                                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient['gender']); ?></p>
                                    <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($patient['blood_group']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Contact Information</h6>
                                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient['phone']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['email']); ?></p>
                                    <p><strong>Address:</strong> <?php echo htmlspecialchars($patient['address']); ?></p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-bold">Medical Information</h6>
                                <p><strong>Medical Condition:</strong> <?php echo htmlspecialchars($patient['medical_condition']); ?></p>
                                <p><strong>Last Visit:</strong> <?php echo htmlspecialchars($patient['last_visit']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editPatientModal<?php echo $patient['id']; ?>">
                        <i class="fas fa-edit me-2"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Edit Patient Modal -->
    <?php foreach ($patients as $patient): ?>
    <div class="modal fade" id="editPatientModal<?php echo $patient['id']; ?>" tabindex="-1" aria-labelledby="editPatientModalLabel<?php echo $patient['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPatientModalLabel<?php echo $patient['id']; ?>">Edit Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="firstName<?php echo $patient['id']; ?>" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName<?php echo $patient['id']; ?>" value="<?php echo explode(' ', $patient['name'])[0]; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName<?php echo $patient['id']; ?>" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName<?php echo $patient['id']; ?>" value="<?php echo explode(' ', $patient['name'])[1]; ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="birthDate<?php echo $patient['id']; ?>" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="birthDate<?php echo $patient['id']; ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="gender<?php echo $patient['id']; ?>" class="form-label">Gender</label>
                                <select class="form-select" id="gender<?php echo $patient['id']; ?>" required>
                                    <option value="Male" <?php echo ($patient['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($patient['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo ($patient['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="bloodGroup<?php echo $patient['id']; ?>" class="form-label">Blood Group</label>
                                <select class="form-select" id="bloodGroup<?php echo $patient['id']; ?>">
                                    <option value="A+" <?php echo ($patient['blood_group'] === 'A+') ? 'selected' : ''; ?>>A+</option>
                                    <option value="A-" <?php echo ($patient['blood_group'] === 'A-') ? 'selected' : ''; ?>>A-</option>
                                    <option value="B+" <?php echo ($patient['blood_group'] === 'B+') ? 'selected' : ''; ?>>B+</option>
                                    <option value="B-" <?php echo ($patient['blood_group'] === 'B-') ? 'selected' : ''; ?>>B-</option>
                                    <option value="AB+" <?php echo ($patient['blood_group'] === 'AB+') ? 'selected' : ''; ?>>AB+</option>
                                    <option value="AB-" <?php echo ($patient['blood_group'] === 'AB-') ? 'selected' : ''; ?>>AB-</option>
                                    <option value="O+" <?php echo ($patient['blood_group'] === 'O+') ? 'selected' : ''; ?>>O+</option>
                                    <option value="O-" <?php echo ($patient['blood_group'] === 'O-') ? 'selected' : ''; ?>>O-</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone<?php echo $patient['id']; ?>" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone<?php echo $patient['id']; ?>" value="<?php echo htmlspecialchars($patient['phone']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email<?php echo $patient['id']; ?>" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email<?php echo $patient['id']; ?>" value="<?php echo htmlspecialchars($patient['email']); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address<?php echo $patient['id']; ?>" class="form-label">Address</label>
                            <textarea class="form-control" id="address<?php echo $patient['id']; ?>" rows="2"><?php echo htmlspecialchars($patient['address']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="medicalHistory<?php echo $patient['id']; ?>" class="form-label">Medical Condition/History</label>
                            <textarea class="form-control" id="medicalHistory<?php echo $patient['id']; ?>" rows="3"><?php echo htmlspecialchars($patient['medical_condition']); ?></textarea>
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

    <!-- Prescription Modal -->
    <?php foreach ($patients as $patient): ?>
    <div class="modal fade" id="prescriptionModal<?php echo $patient['id']; ?>" tabindex="-1" aria-labelledby="prescriptionModalLabel<?php echo $patient['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="prescriptionModalLabel<?php echo $patient['id']; ?>">Create Prescription for <?php echo htmlspecialchars($patient['name']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Patient Name</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($patient['name']); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="prescriptionDate<?php echo $patient['id']; ?>" class="form-label">Date</label>
                                <input type="date" class="form-control" id="prescriptionDate<?php echo $patient['id']; ?>" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="diagnosis<?php echo $patient['id']; ?>" class="form-label">Diagnosis</label>
                            <input type="text" class="form-control" id="diagnosis<?php echo $patient['id']; ?>" placeholder="Enter diagnosis">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Medications</label>
                            <div id="medicationList<?php echo $patient['id']; ?>">
                                <div class="medication-item card mb-2 p-3">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" placeholder="Medication name" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" placeholder="Dosage">
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select">
                                                <option value="" selected disabled>Frequency</option>
                                                <option value="Once daily">Once daily</option>
                                                <option value="Twice daily">Twice daily</option>
                                                <option value="Three times daily">Three times daily</option>
                                                <option value="Four times daily">Four times daily</option>
                                                <option value="As needed">As needed</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" placeholder="Duration">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger btn-sm remove-medication"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary mt-2" id="addMedication<?php echo $patient['id']; ?>">
                                <i class="fas fa-plus me-1"></i> Add Medication
                            </button>
                        </div>
                        
                        <div class="mb-3">
                            <label for="instructions<?php echo $patient['id']; ?>" class="form-label">Special Instructions</label>
                            <textarea class="form-control" id="instructions<?php echo $patient['id']; ?>" rows="3" placeholder="Any special instructions or advice"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="followUp<?php echo $patient['id']; ?>" class="form-label">Follow-up</label>
                            <input type="text" class="form-control" id="followUp<?php echo $patient['id']; ?>" placeholder="e.g., 2 weeks, 1 month">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Prescription</button>
                    <button type="button" class="btn btn-info">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Schedule Appointment Modal -->
    <?php foreach ($patients as $patient): ?>
    <div class="modal fade" id="scheduleAppointmentModal<?php echo $patient['id']; ?>" tabindex="-1" aria-labelledby="scheduleAppointmentModalLabel<?php echo $patient['id']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleAppointmentModalLabel<?php echo $patient['id']; ?>">Schedule Appointment for <?php echo htmlspecialchars($patient['name']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Patient Name</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($patient['name']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentType<?php echo $patient['id']; ?>" class="form-label">Appointment Type</label>
                            <select class="form-select" id="appointmentType<?php echo $patient['id']; ?>" required>
                                <option value="" selected disabled>Select Type</option>
                                <option value="consultation">Consultation</option>
                                <option value="followup">Follow-up</option>
                                <option value="physical">Physical Examination</option>
                                <option value="test">Medical Test</option>
                                <option value="emergency">Emergency</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="appointmentDate<?php echo $patient['id']; ?>" class="form-label">Date</label>
                                <input type="date" class="form-control" id="appointmentDate<?php echo $patient['id']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="appointmentTime<?php echo $patient['id']; ?>" class="form-label">Time</label>
                                <input type="time" class="form-control" id="appointmentTime<?php echo $patient['id']; ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentReason<?php echo $patient['id']; ?>" class="form-label">Reason</label>
                            <input type="text" class="form-control" id="appointmentReason<?php echo $patient['id']; ?>" placeholder="Reason for appointment">
                        </div>
                        <div class="mb-3">
                            <label for="appointmentNotes<?php echo $patient['id']; ?>" class="form-label">Notes</label>
                            <textarea class="form-control" id="appointmentNotes<?php echo $patient['id']; ?>" rows="3" placeholder="Additional notes or information"></textarea>
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
            $('#patientsTable').DataTable({
                responsive: true,
                order: [[1, 'asc']] // Sort by name by default
            });
        });

        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
        });
        
        // Handle Add Medication buttons
        <?php foreach ($patients as $patient): ?>
        document.getElementById('addMedication<?php echo $patient['id']; ?>').addEventListener('click', function() {
            const medicationList = document.getElementById('medicationList<?php echo $patient['id']; ?>');
            const newMedication = document.createElement('div');
            newMedication.className = 'medication-item card mb-2 p-3';
            newMedication.innerHTML = `
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Medication name" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="Dosage">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select">
                            <option value="" selected disabled>Frequency</option>
                            <option value="Once daily">Once daily</option>
                            <option value="Twice daily">Twice daily</option>
                            <option value="Three times daily">Three times daily</option>
                            <option value="Four times daily">Four times daily</option>
                            <option value="As needed">As needed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="Duration">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-medication"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            `;
            medicationList.appendChild(newMedication);
            
            // Add event listener to the new remove button
            newMedication.querySelector('.remove-medication').addEventListener('click', function() {
                medicationList.removeChild(newMedication);
            });
        });
        
        // Add event listeners to existing remove medication buttons
        const initialRemoveButtons<?php echo $patient['id']; ?> = document.querySelectorAll('#medicationList<?php echo $patient['id']; ?> .remove-medication');
        initialRemoveButtons<?php echo $patient['id']; ?>.forEach(button => {
            button.addEventListener('click', function() {
                const medicationItem = this.closest('.medication-item');
                medicationItem.remove();
            });
        });
        <?php endforeach; ?>
    </script>
</body>
</html> 