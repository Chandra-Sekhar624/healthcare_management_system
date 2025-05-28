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
$specialties = [
    [
        'id' => 1,
        'name' => 'Cardiology',
        'description' => 'Diagnosis and treatment of disorders of the heart and blood vessels',
        'icon' => 'fa-heart',
        'doctors_count' => 8,
        'status' => 'active'
    ],
    [
        'id' => 2,
        'name' => 'Neurology',
        'description' => 'Diagnosis and treatment of disorders of the nervous system',
        'icon' => 'fa-brain',
        'doctors_count' => 6,
        'status' => 'active'
    ],
    [
        'id' => 3,
        'name' => 'Orthopedics',
        'description' => 'Prevention and correction of disorders of the skeletal system and associated structures',
        'icon' => 'fa-bone',
        'doctors_count' => 5,
        'status' => 'active'
    ],
    [
        'id' => 4,
        'name' => 'Dermatology',
        'description' => 'Diagnosis and treatment of disorders of the skin, hair, and nails',
        'icon' => 'fa-allergies',
        'doctors_count' => 4,
        'status' => 'active'
    ],
    [
        'id' => 5,
        'name' => 'Pediatrics',
        'description' => 'Medical care of infants, children, and adolescents',
        'icon' => 'fa-child',
        'doctors_count' => 7,
        'status' => 'active'
    ],
    [
        'id' => 6,
        'name' => 'Psychiatry',
        'description' => 'Diagnosis, prevention, and treatment of mental disorders',
        'icon' => 'fa-brain',
        'doctors_count' => 3,
        'status' => 'inactive'
    ],
    [
        'id' => 7,
        'name' => 'Ophthalmology',
        'description' => 'Diagnosis and treatment of disorders of the eye',
        'icon' => 'fa-eye',
        'doctors_count' => 2,
        'status' => 'active'
    ]
];

// Handle specialty status changes (in a real system, this would update the database)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $specialty_id = $_GET['id'];
    
    // Placeholder for status change actions
    $status_message = '';
    
    if ($action === 'activate') {
        $status_message = "Specialty ID $specialty_id has been activated.";
    } elseif ($action === 'deactivate') {
        $status_message = "Specialty ID $specialty_id has been deactivated.";
    } elseif ($action === 'delete') {
        $status_message = "Specialty ID $specialty_id has been deleted.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Specialties | Admin Dashboard | HealthConnect</title>
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
                <li class="nav-item">
                    <a class="nav-link" href="appointments.php">
                        <i class="fas fa-calendar-check me-2"></i> Appointments
                    </a>
                </li>
                <li class="nav-item active">
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
                                        <i class="fas fa-user-md text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">3 hours ago</div>
                                    <span class="fw-bold">New doctor registered in Cardiology</span>
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
                    <h1 class="h3 mb-0 text-gray-800">Manage Specialties</h1>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpecialtyModal">
                        <i class="fas fa-plus fa-sm text-white-50 me-2"></i>Add New Specialty
                    </a>
                </div>

                <?php if (isset($status_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($status_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Specialty Stats -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Specialties</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($specialties); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Active Specialties</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php 
                                            $active_count = 0;
                                            foreach ($specialties as $specialty) {
                                                if ($specialty['status'] === 'active') {
                                                    $active_count++;
                                                }
                                            }
                                            echo $active_count;
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

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Doctors</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php 
                                            $total_doctors = 0;
                                            foreach ($specialties as $specialty) {
                                                $total_doctors += $specialty['doctors_count'];
                                            }
                                            echo $total_doctors;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-md fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Avg Doctors per Specialty</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php 
                                            $avg_doctors = $total_doctors / count($specialties);
                                            echo round($avg_doctors, 1);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Specialty Filters -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Filters</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="doctorsFilter">
                                    <option value="">All Doctor Counts</option>
                                    <option value="1-3">1-3 Doctors</option>
                                    <option value="4-6">4-6 Doctors</option>
                                    <option value="7+">7+ Doctors</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search specialties..." id="searchSpecialties">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Specialties List -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Specialties List</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="specialtiesTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Doctors</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($specialties as $specialty): ?>
                                        <tr>
                                            <td><?php echo $specialty['id']; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="specialty-icon me-2">
                                                        <i class="fas <?php echo htmlspecialchars($specialty['icon']); ?> text-primary"></i>
                                                    </div>
                                                    <span><?php echo htmlspecialchars($specialty['name']); ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($specialty['description']); ?></td>
                                            <td><?php echo $specialty['doctors_count']; ?> doctors</td>
                                            <td>
                                                <span class="badge bg-<?php echo $specialty['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                    <?php echo ucfirst(htmlspecialchars($specialty['status'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewSpecialtyModal<?php echo $specialty['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editSpecialtyModal<?php echo $specialty['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($specialty['status'] === 'active'): ?>
                                                        <a href="?action=deactivate&id=<?php echo $specialty['id']; ?>" class="btn btn-warning">
                                                            <i class="fas fa-ban"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="?action=activate&id=<?php echo $specialty['id']; ?>" class="btn btn-success">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSpecialtyModal<?php echo $specialty['id']; ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- View Specialty Modal -->
                                        <div class="modal fade" id="viewSpecialtyModal<?php echo $specialty['id']; ?>" tabindex="-1" aria-labelledby="viewSpecialtyModalLabel<?php echo $specialty['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewSpecialtyModalLabel<?php echo $specialty['id']; ?>">
                                                            Specialty Details: <?php echo htmlspecialchars($specialty['name']); ?>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-4 text-center mb-3">
                                                                <div class="specialty-icon-large mb-3">
                                                                    <i class="fas <?php echo htmlspecialchars($specialty['icon']); ?> fa-4x text-primary"></i>
                                                                </div>
                                                                <h5><?php echo htmlspecialchars($specialty['name']); ?></h5>
                                                                <p class="mb-1">ID: SP<?php echo str_pad($specialty['id'], 3, '0', STR_PAD_LEFT); ?></p>
                                                                <span class="badge bg-<?php echo $specialty['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                                    <?php echo ucfirst(htmlspecialchars($specialty['status'])); ?>
                                                                </span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <h6 class="text-primary mb-3">Specialty Information</h6>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Description:</div>
                                                                    <div class="col-md-8"><?php echo htmlspecialchars($specialty['description']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Number of Doctors:</div>
                                                                    <div class="col-md-8"><?php echo $specialty['doctors_count']; ?></div>
                                                                </div>
                                                                
                                                                <h6 class="text-primary mb-3 mt-4">Doctors in this Specialty</h6>
                                                                <ul class="list-group">
                                                                    <?php 
                                                                    // In a real system, you would fetch and display the actual doctors
                                                                    $example_doctors = [
                                                                        "Dr. John Smith",
                                                                        "Dr. Emily Johnson",
                                                                        "Dr. Michael Brown"
                                                                    ];
                                                                    
                                                                    for($i = 0; $i < min(3, $specialty['doctors_count']); $i++): 
                                                                    ?>
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <?php echo $example_doctors[$i]; ?>
                                                                            <a href="doctors.php" class="btn btn-sm btn-outline-primary">View Profile</a>
                                                                        </li>
                                                                    <?php endfor; ?>
                                                                    
                                                                    <?php if($specialty['doctors_count'] > 3): ?>
                                                                        <li class="list-group-item text-center text-primary">
                                                                            <a href="doctors.php">View all <?php echo $specialty['doctors_count']; ?> doctors</a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <a href="#" class="btn btn-primary" data-bs-dismiss="modal" data-bs-target="#editSpecialtyModal<?php echo $specialty['id']; ?>" data-bs-toggle="modal">Edit Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Delete Specialty Confirmation Modal -->
                                        <div class="modal fade" id="deleteSpecialtyModal<?php echo $specialty['id']; ?>" tabindex="-1" aria-labelledby="deleteSpecialtyModalLabel<?php echo $specialty['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteSpecialtyModalLabel<?php echo $specialty['id']; ?>">Confirm Delete</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php if($specialty['doctors_count'] > 0): ?>
                                                            <div class="alert alert-warning" role="alert">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                Warning: This specialty has <?php echo $specialty['doctors_count']; ?> doctors assigned to it. Deleting it may affect these doctor records.
                                                            </div>
                                                        <?php endif; ?>
                                                        <p>Are you sure you want to delete the specialty <strong><?php echo htmlspecialchars($specialty['name']); ?></strong>? This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <a href="?action=delete&id=<?php echo $specialty['id']; ?>" class="btn btn-danger">Delete</a>
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

    <!-- Add Specialty Modal -->
    <div class="modal fade" id="addSpecialtyModal" tabindex="-1" aria-labelledby="addSpecialtyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSpecialtyModalLabel">Add New Specialty</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="specialtyName" class="form-label">Specialty Name</label>
                            <input type="text" class="form-control" id="specialtyName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="specialtyDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="specialtyDescription" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="specialtyIcon" class="form-label">Icon</label>
                            <select class="form-select" id="specialtyIcon" name="icon" required>
                                <option value="" selected disabled>Select an icon</option>
                                <option value="fa-heart">Heart (Cardiology)</option>
                                <option value="fa-brain">Brain (Neurology)</option>
                                <option value="fa-bone">Bone (Orthopedics)</option>
                                <option value="fa-allergies">Skin (Dermatology)</option>
                                <option value="fa-child">Child (Pediatrics)</option>
                                <option value="fa-teeth">Teeth (Dentistry)</option>
                                <option value="fa-eye">Eye (Ophthalmology)</option>
                                <option value="fa-lungs">Lungs (Pulmonology)</option>
                                <option value="fa-kidneys">Kidneys (Nephrology)</option>
                                <option value="fa-stethoscope">Stethoscope (General)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="specialtyStatus" class="form-label">Status</label>
                            <select class="form-select" id="specialtyStatus" name="status" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Specialty</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Specialty Modal (Example for one specialty - in practice, you'd generate this for each) -->
    <div class="modal fade" id="editSpecialtyModal1" tabindex="-1" aria-labelledby="editSpecialtyModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSpecialtyModalLabel1">Edit Specialty: Cardiology</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="editSpecialtyName" class="form-label">Specialty Name</label>
                            <input type="text" class="form-control" id="editSpecialtyName" name="name" value="Cardiology" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSpecialtyDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editSpecialtyDescription" name="description" rows="3" required>Diagnosis and treatment of disorders of the heart and blood vessels</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editSpecialtyIcon" class="form-label">Icon</label>
                            <select class="form-select" id="editSpecialtyIcon" name="icon" required>
                                <option value="fa-heart" selected>Heart (Cardiology)</option>
                                <option value="fa-brain">Brain (Neurology)</option>
                                <option value="fa-bone">Bone (Orthopedics)</option>
                                <option value="fa-allergies">Skin (Dermatology)</option>
                                <option value="fa-child">Child (Pediatrics)</option>
                                <option value="fa-teeth">Teeth (Dentistry)</option>
                                <option value="fa-eye">Eye (Ophthalmology)</option>
                                <option value="fa-lungs">Lungs (Pulmonology)</option>
                                <option value="fa-kidneys">Kidneys (Nephrology)</option>
                                <option value="fa-stethoscope">Stethoscope (General)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editSpecialtyStatus" class="form-label">Status</label>
                            <select class="form-select" id="editSpecialtyStatus" name="status" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
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

    <!-- Bootstrap 5.1.3 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/main.js"></script>
    <script>
        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
        });
        
        // Filter functionality
        document.getElementById('statusFilter').addEventListener('change', function() {
            // Filter table by status
            // In a real implementation, this would filter the table rows
            console.log('Filtering by status:', this.value);
        });
        
        document.getElementById('doctorsFilter').addEventListener('change', function() {
            // Filter table by doctor count
            // In a real implementation, this would filter the table rows
            console.log('Filtering by doctor count:', this.value);
        });
        
        document.getElementById('searchSpecialties').addEventListener('input', function() {
            // Search in table
            // In a real implementation, this would search through the table
            console.log('Searching for:', this.value);
        });
    </script>
</body>
</html> 