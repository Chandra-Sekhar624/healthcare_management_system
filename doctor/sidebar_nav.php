<?php
// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Demo notification data
$notifications = [
    [
        'id' => 1,
        'title' => 'New Appointment Request',
        'message' => 'You have a new appointment request from Sarah Wilson',
        'time' => '5 minutes ago',
        'is_read' => false,
        'type' => 'appointment',
        'icon' => 'calendar-check'
    ],
    [
        'id' => 2,
        'title' => 'Test Results Ready',
        'message' => 'Blood test results for patient John Smith are ready',
        'time' => '30 minutes ago',
        'is_read' => false,
        'type' => 'lab',
        'icon' => 'flask'
    ],
    [
        'id' => 3,
        'title' => 'Emergency Case',
        'message' => 'Urgent: Patient Mike Johnson needs immediate attention',
        'time' => '1 hour ago',
        'is_read' => false,
        'type' => 'emergency',
        'icon' => 'exclamation-circle'
    ],
    [
        'id' => 4,
        'title' => 'Medical Record Updated',
        'message' => 'Medical records for Emma Davis have been updated',
        'time' => '2 hours ago',
        'is_read' => true,
        'type' => 'records',
        'icon' => 'file-medical'
    ],
    [
        'id' => 5,
        'title' => 'Payment Received',
        'message' => 'Payment received for consultation #12345',
        'time' => '3 hours ago',
        'is_read' => true,
        'type' => 'payment',
        'icon' => 'money-bill'
    ]
];

// Demo messages
$messages = [
    [
        'id' => 1,
        'sender' => 'Sarah Wilson',
        'message' => 'Hi Doctor, I need to reschedule my appointment...',
        'time' => '5m ago',
        'image' => '../img/patient-1.jpg',
        'status' => 'online'
    ],
    [
        'id' => 2,
        'sender' => 'John Smith',
        'message' => 'Thank you for the prescription, doctor...',
        'time' => '30m ago',
        'image' => '../img/patient-2.jpg',
        'status' => 'offline'
    ],
    [
        'id' => 3,
        'sender' => 'Emma Davis',
        'message' => 'When will my test results be ready?',
        'time' => '1h ago',
        'image' => '../img/patient-1.jpg',
        'status' => 'online'
    ]
];

// Count unread notifications
$unread_count = count(array_filter($notifications, function($n) { return !$n['is_read']; }));
?>
<!-- Sidebar and Navigation -->
<div class="dashboard-container">
    <style>
        .dropdown-list-image {
            position: relative;
            height: 3rem;
            width: 3rem;
        }
        
        .dropdown-list-image img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            border: 2px solid #e3e6f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .dropdown-list-image .status-indicator {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 1rem;
            height: 1rem;
            border: 2px solid #fff;
            border-radius: 50%;
        }

        .messages-dropdown .dropdown-menu {
            min-width: 320px;
            padding: 0;
        }

        .messages-dropdown .dropdown-item {
            padding: 1rem;
            border-bottom: 1px solid #e3e6f0;
            transition: all 0.2s ease;
        }

        /* .messages-dropdown .dropdown-item:hover {
            background-color: #f8f9fc;
            transform: translateX(5px);
        } */

        .messages-dropdown .text-truncate {
            max-width: 200px;
            font-size: 0.9rem;
            color: #4e73df;
            font-weight: 500;
        }

        .messages-dropdown .small {
            font-size: 0.85rem;
        }

        .messages-dropdown .sender-name {
            color: #5a5c69;
            font-weight: 600;
        }

        .messages-dropdown .time {
            color: #858796;
        }

        .messages-dropdown .dropdown-header {
            background-color: #4e73df;
            color: white;
            padding: 1rem;
            font-weight: 600;
        }

        .messages-dropdown .text-center {
            padding: 1rem;
            background-color: #f8f9fc;
            color: #4e73df !important;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .messages-dropdown .text-center:hover {
            background-color: #eaecf4;
        }
    </style>
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
                <?php
                // Display the uploaded image if available, otherwise show default
                $profile_image = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : '../img/doctor-avatar.jpg';
                ?>
                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Doctor" class="rounded-circle">
            </div>
            <div class="user-info">
                <h6 class="mb-0 text-white"><?php echo htmlspecialchars($doctor_name); ?></h6>
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
                        <span class="badge bg-danger badge-counter"><?php echo $unread_count; ?>+</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">Notifications Center</h6>
                        <?php foreach ($notifications as $notification): ?>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-<?php 
                                        switch($notification['type']) {
                                            case 'emergency':
                                                echo 'danger';
                                                break;
                                            case 'appointment':
                                                echo 'primary';
                                                break;
                                            case 'lab':
                                                echo 'info';
                                                break;
                                            case 'records':
                                                echo 'success';
                                                break;
                                            case 'payment':
                                                echo 'warning';
                                                break;
                                            default:
                                                echo 'secondary';
                                        }
                                    ?>">
                                        <i class="fas fa-<?php echo htmlspecialchars($notification['icon']); ?> text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted"><?php echo htmlspecialchars($notification['time']); ?></div>
                                    <span class="<?php echo $notification['is_read'] ? '' : 'fw-bold'; ?>">
                                        <?php echo htmlspecialchars($notification['message']); ?>
                                    </span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                        <a class="dropdown-item text-center small text-primary" href="#">Show All Notifications</a>
                    </div>
                </li>

                <!-- Messages -->
                <li class="nav-item dropdown no-arrow mx-1 messages-dropdown">
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
                                <div class="flex-grow-1">
                                    <div class="sender-name mb-1"><?php echo htmlspecialchars($message['sender']); ?></div>
                                    <div class="text-truncate"><?php echo htmlspecialchars($message['message']); ?></div>
                                    <div class="small time"><?php echo htmlspecialchars($message['time']); ?></div>
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
