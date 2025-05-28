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
$doctors = [
    [
        'id' => 1,
        'name' => 'Dr. Sarah Johnson',
        'specialty' => 'Cardiologist',
        'experience' => 10,
        'email' => 'sarah.johnson@example.com',
        'phone' => '(123) 456-7890',
        'status' => 'active',
        'rating' => 4.8
    ],
    [
        'id' => 2,
        'name' => 'Dr. Michael Chen',
        'specialty' => 'Neurologist',
        'experience' => 8,
        'email' => 'michael.chen@example.com',
        'phone' => '(234) 567-8901',
        'status' => 'pending',
        'rating' => 0
    ],
    [
        'id' => 3,
        'name' => 'Dr. Emily Rodriguez',
        'specialty' => 'Pediatrician',
        'experience' => 5,
        'email' => 'emily.rodriguez@example.com',
        'phone' => '(345) 678-9012',
        'status' => 'active',
        'rating' => 4.2
    ],
    [
        'id' => 4,
        'name' => 'Dr. James Wilson',
        'specialty' => 'Dermatologist',
        'experience' => 12,
        'email' => 'james.wilson@example.com',
        'phone' => '(456) 789-0123',
        'status' => 'active',
        'rating' => 4.7
    ],
    [
        'id' => 5,
        'name' => 'Dr. Robert Kim',
        'specialty' => 'Orthopedic Surgeon',
        'experience' => 15,
        'email' => 'robert.kim@example.com',
        'phone' => '(567) 890-1234',
        'status' => 'active',
        'rating' => 4.9
    ]
];

// Handle doctor status changes (in a real system, this would update the database)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $doctor_id = $_GET['id'];
    
    // Placeholder for status change actions
    $status_message = '';
    
    if ($action === 'approve') {
        $status_message = "Doctor ID $doctor_id has been approved.";
    } elseif ($action === 'reject') {
        $status_message = "Doctor ID $doctor_id has been rejected.";
    } elseif ($action === 'delete') {
        $status_message = "Doctor ID $doctor_id has been deleted.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors | Admin Dashboard | HealthConnect</title>
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
                <li class="nav-item active">
                    <a class="nav-link" href="doctors.php">
                        <i class="fas fa-user-md me-2"></i> Doctors
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="patients.php">
                        <i class="fas fa-user-injured me-2"></i> Patients
                    </a>
                </li>
                <li class="nav-item">
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
                                        <i class="fas fa-bell text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">2 hours ago</div>
                                    <span class="fw-bold">New Doctor Registration</span>
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
                    <h1 class="h3 mb-0 text-gray-800">Manage Doctors</h1>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                        <i class="fas fa-user-plus fa-sm text-white-50 me-2"></i>Add New Doctor
                    </a>
                </div>

                <?php if (isset($status_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($status_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Doctor Filters -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Filters</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="specialtyFilter">
                                    <option value="">All Specialties</option>
                                    <option value="Cardiologist">Cardiologist</option>
                                    <option value="Dermatologist">Dermatologist</option>
                                    <option value="Neurologist">Neurologist</option>
                                    <option value="Orthopedic Surgeon">Orthopedic Surgeon</option>
                                    <option value="Pediatrician">Pediatrician</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search doctors..." id="searchDoctors">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-secondary w-100" id="resetFilters">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctors List -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Doctors List</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="doctorsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Specialty</th>
                                        <th>Experience</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Rating</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($doctors as $doctor): ?>
                                        <tr>
                                            <td><?php echo $doctor['id']; ?></td>
                                            <td><?php echo htmlspecialchars($doctor['name']); ?></td>
                                            <td><?php echo htmlspecialchars($doctor['specialty']); ?></td>
                                            <td><?php echo $doctor['experience']; ?> years</td>
                                            <td><?php echo htmlspecialchars($doctor['email']); ?></td>
                                            <td><?php echo htmlspecialchars($doctor['phone']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $doctor['status'] === 'active' ? 'success' : 
                                                        ($doctor['status'] === 'pending' ? 'warning' : 'danger'); 
                                                ?>">
                                                    <?php echo ucfirst(htmlspecialchars($doctor['status'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($doctor['rating'] > 0): ?>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-1"><?php echo $doctor['rating']; ?></div>
                                                        <div class="text-warning">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <?php if ($i <= floor($doctor['rating'])): ?>
                                                                    <i class="fas fa-star"></i>
                                                                <?php elseif ($i - 0.5 <= $doctor['rating']): ?>
                                                                    <i class="fas fa-star-half-alt"></i>
                                                                <?php else: ?>
                                                                    <i class="far fa-star"></i>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">No ratings</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewDoctorModal<?php echo $doctor['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDoctorModal<?php echo $doctor['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($doctor['status'] === 'pending'): ?>
                                                        <a href="?action=approve&id=<?php echo $doctor['id']; ?>" class="btn btn-success">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                        <a href="?action=reject&id=<?php echo $doctor['id']; ?>" class="btn btn-warning">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDoctorModal<?php echo $doctor['id']; ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- View Doctor Modal -->
                                        <div class="modal fade" id="viewDoctorModal<?php echo $doctor['id']; ?>" tabindex="-1" aria-labelledby="viewDoctorModalLabel<?php echo $doctor['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewDoctorModalLabel<?php echo $doctor['id']; ?>">
                                                            Doctor Details: <?php echo htmlspecialchars($doctor['name']); ?>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-4 text-center mb-3">
                                                                <img src="../img/doctor-<?php echo $doctor['id']; ?>.jpg" class="img-fluid rounded-circle mb-3" alt="Doctor Avatar" onerror="this.src='../img/doctor-avatar.jpg';" style="width: 150px; height: 150px; object-fit: cover;">
                                                                <h5><?php echo htmlspecialchars($doctor['name']); ?></h5>
                                                                <p class="text-muted"><?php echo htmlspecialchars($doctor['specialty']); ?></p>
                                                                <div class="text-warning mb-2">
                                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                        <?php if ($i <= floor($doctor['rating'])): ?>
                                                                            <i class="fas fa-star"></i>
                                                                        <?php elseif ($i - 0.5 <= $doctor['rating']): ?>
                                                                            <i class="fas fa-star-half-alt"></i>
                                                                        <?php else: ?>
                                                                            <i class="far fa-star"></i>
                                                                        <?php endif; ?>
                                                                    <?php endfor; ?>
                                                                    <span class="text-dark">(<?php echo $doctor['rating']; ?>)</span>
                                                                </div>
                                                                <span class="badge bg-<?php 
                                                                    echo $doctor['status'] === 'active' ? 'success' : 
                                                                        ($doctor['status'] === 'pending' ? 'warning' : 'danger'); 
                                                                ?>">
                                                                    <?php echo ucfirst(htmlspecialchars($doctor['status'])); ?>
                                                                </span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <h6 class="text-primary mb-3">Personal Information</h6>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Email:</div>
                                                                    <div class="col-md-8"><?php echo htmlspecialchars($doctor['email']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Phone:</div>
                                                                    <div class="col-md-8"><?php echo htmlspecialchars($doctor['phone']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Experience:</div>
                                                                    <div class="col-md-8"><?php echo $doctor['experience']; ?> years</div>
                                                                </div>
                                                                
                                                                <h6 class="text-primary mb-3 mt-4">Professional Information</h6>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Specialty:</div>
                                                                    <div class="col-md-8"><?php echo htmlspecialchars($doctor['specialty']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">License Number:</div>
                                                                    <div class="col-md-8">MED<?php echo 10000 + $doctor['id']; ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Consultation Fee:</div>
                                                                    <div class="col-md-8">$<?php echo (75 + ($doctor['experience'] * 5)); ?></div>
                                                                </div>
                                                                
                                                                <h6 class="text-primary mb-3 mt-4">Biography</h6>
                                                                <p>
                                                                    <?php echo htmlspecialchars($doctor['name']); ?> is a dedicated and experienced <?php echo htmlspecialchars($doctor['specialty']); ?> with <?php echo $doctor['experience']; ?> years of clinical experience. 
                                                                    Specializing in the diagnosis and treatment of various conditions, the doctor is committed to providing comprehensive care to patients of all ages.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <a href="#" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editDoctorModal<?php echo $doctor['id']; ?>">Edit Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Delete Doctor Confirmation Modal -->
                                        <div class="modal fade" id="deleteDoctorModal<?php echo $doctor['id']; ?>" tabindex="-1" aria-labelledby="deleteDoctorModalLabel<?php echo $doctor['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteDoctorModalLabel<?php echo $doctor['id']; ?>">Confirm Delete</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete <?php echo htmlspecialchars($doctor['name']); ?>? This action cannot be undone.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <a href="?action=delete&id=<?php echo $doctor['id']; ?>" class="btn btn-danger">Delete</a>
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

    <!-- Add Doctor Modal -->
    <div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDoctorModalLabel">Add New Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label for="specialty" class="form-label">Specialty</label>
                                <select class="form-select" id="specialty" name="specialty" required>
                                    <option value="" selected disabled>Select specialty</option>
                                    <option value="Cardiology">Cardiology</option>
                                    <option value="Dermatology">Dermatology</option>
                                    <option value="Endocrinology">Endocrinology</option>
                                    <option value="Gastroenterology">Gastroenterology</option>
                                    <option value="Neurology">Neurology</option>
                                    <option value="Obstetrics and Gynecology">Obstetrics and Gynecology</option>
                                    <option value="Oncology">Oncology</option>
                                    <option value="Ophthalmology">Ophthalmology</option>
                                    <option value="Orthopedics">Orthopedics</option>
                                    <option value="Pediatrics">Pediatrics</option>
                                    <option value="Psychiatry">Psychiatry</option>
                                    <option value="Urology">Urology</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="experience" class="form-label">Years of Experience</label>
                                <input type="number" class="form-control" id="experience" name="experience" min="0" max="70" required>
                            </div>
                            <div class="col-md-6">
                                <label for="licenseNumber" class="form-label">License Number</label>
                                <input type="text" class="form-control" id="licenseNumber" name="license_number" required>
                            </div>
                            <div class="col-md-6">
                                <label for="consultationFee" class="form-label">Consultation Fee ($)</label>
                                <input type="number" class="form-control" id="consultationFee" name="consultation_fee" min="0" required>
                            </div>
                            <div class="col-12">
                                <label for="bio" class="form-label">Biography</label>
                                <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Password must be at least 8 characters long.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                            </div>
                            <div class="col-12">
                                <label for="profileImage" class="form-label">Profile Image</label>
                                <input class="form-control" type="file" id="profileImage" name="profile_image">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Doctor</button>
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