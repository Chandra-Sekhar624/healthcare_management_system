<?php
// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get admin information if not already set
if (!isset($admin_name)) {
    $admin_name = $_SESSION['user_name'];
}

// Placeholder data for system notifications (in a real system, fetch from database)
$system_notifications = [
    [
        'id' => 1,
        'title' => 'New Doctor Registration',
        'message' => 'Dr. Michael Chen has registered as a neurologist',
        'time' => '2 hours ago',
        'type' => 'registration',
        'icon' => 'user-md',
        'color' => 'primary'
    ],
    [
        'id' => 2,
        'title' => 'System Update',
        'message' => 'System will be under maintenance on 25th June, 2:00 AM',
        'time' => '1 day ago',
        'type' => 'system',
        'icon' => 'cog',
        'color' => 'info'
    ],
    [
        'id' => 3,
        'title' => 'Payment Gateway Issue',
        'message' => 'Payment gateway reported temporary issues with transactions',
        'time' => '2 days ago',
        'type' => 'payment',
        'icon' => 'exclamation-triangle',
        'color' => 'warning'
    ]
];

// Messages data
$messages = [
    [
        'sender' => 'Dr. Sarah Johnson',
        'message' => 'I need approval for my account.',
        'time' => '58m',
        'image' => '../img/doctor-1.jpg',
        'status' => 'online'
    ],
    [
        'sender' => 'Dr. Michael Chen',
        'message' => 'When will my profile be verified?',
        'time' => '1d',
        'image' => '../img/doctor-2.jpg',
        'status' => 'offline'
    ]
];
?>
<!-- Sidebar and Navigation -->
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="dashboard-sidebar bg-dark text-white">
        <div class="sidebar-header p-3 text-center">
            <a href="../index.php" class="sidebar-brand text-decoration-none">
                <i class="fas fa-heartbeat text-primary me-2"></i>
                <span class="fw-bold fs-4 text-white">HealthTech</span>
            </a>
        </div>
        <hr class="sidebar-divider">
        <div class="sidebar-user text-center mb-4">
            <div class="user-avatar mb-3">
                <img class="img-profile rounded-circle" src="../img/admin-avatar.jpg" alt="Admin">
            </div>
            <div class="user-info">
                <h6 class="mb-0 text-white"><?php echo htmlspecialchars($admin_name); ?></h6>
                <span class="text-muted small">Administrator</span>
            </div>
        </div>
        <ul class="sidebar-nav list-unstyled">
            <li class="nav-item active">
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
                        <?php foreach ($system_notifications as $notification): ?>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-<?php echo htmlspecialchars($notification['color']); ?>">
                                        <i class="fas fa-<?php echo htmlspecialchars($notification['icon']); ?> text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted"><?php echo htmlspecialchars($notification['time']); ?></div>
                                    <span class="fw-bold"><?php echo htmlspecialchars($notification['title']); ?></span>
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
                        <span class="badge bg-danger badge-counter"><?php echo count($messages); ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="messagesDropdown">
                        <h6 class="dropdown-header">Message Center</h6>
                        <?php foreach ($messages as $message): ?>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image me-3">
                                    <img class="rounded-circle" src="<?php echo htmlspecialchars($message['image']); ?>" alt="<?php echo htmlspecialchars($message['sender']); ?>">
                                    <div class="status-indicator bg-<?php echo $message['status'] === 'online' ? 'success' : 'secondary'; ?>"></div>
                                </div>
                                <div class="fw-bold">
                                    <div class="text-truncate"><?php echo htmlspecialchars($message['message']); ?></div>
                                    <div class="small text-muted"><?php echo htmlspecialchars($message['sender']); ?> · <?php echo htmlspecialchars($message['time']); ?></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                        <a class="dropdown-item text-center small text-primary" href="#">Read More Messages</a>
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
