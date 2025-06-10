<?php
// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get profile image path if not already set
if (!isset($profile_image)) {
    $profile_image = getPatientProfileImage($conn, $_SESSION['user_id']);
}

// Get patient name if not already set
if (!isset($patient_name)) {
    $patient_name = $_SESSION['user_name'];
}

// Notifications data
$notifications = [
    [
        'id' => 1,
        'title' => 'Appointment Confirmed',
        'message' => 'Your appointment with Dr. John Williams has been confirmed',
        'time' => '1 hour ago',
        'is_read' => false,
        'type' => 'appointment',
        'icon' => 'calendar-check'
    ],
    [
        'id' => 2,
        'title' => 'New Prescription',
        'message' => 'Dr. John Williams has prescribed a new medication for you',
        'time' => '1 day ago',
        'is_read' => true,
        'type' => 'prescription',
        'icon' => 'prescription'
    ],
    [
        'id' => 3,
        'title' => 'Appointment Reminder',
        'message' => 'Reminder: You have an appointment tomorrow with Dr. Sarah Johnson',
        'time' => '2 days ago',
        'is_read' => true,
        'type' => 'reminder',
        'icon' => 'bell'
    ]
];

// Messages data
$messages = [
    [
        'id' => 1,
        'sender' => 'Dr. John Williams',
        'message' => 'Your lab results are now available. Please review them at your earliest convenience.',
        'time' => '2h',
        'image' => '../img/doctor-1.jpg',
        'status' => 'online'
    ],
    [
        'id' => 2,
        'sender' => 'Dr. Sarah Johnson',
        'message' => 'Please let me know if you have any questions about the new prescription.',
        'time' => '1d',
        'image' => '../img/doctor-2.jpg',
        'status' => 'offline'
    ]
];
?>
<!-- Sidebar and Navigation -->
<div class="dashboard-container">
    <style>
        /* Messages Dropdown Styling */
        .messages-dropdown .dropdown-menu {
            min-width: 320px;
            padding: 0;
        }

        .messages-dropdown .dropdown-header {
            background-color: #4e73df;
            color: white;
            padding: 1rem;
            font-weight: 600;
            border-top-left-radius: 0.35rem;
            border-top-right-radius: 0.35rem;
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

        .messages-dropdown .dropdown-list-image {
            position: relative;
            height: 3rem;
            width: 3rem;
        }

        .messages-dropdown .dropdown-list-image img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            border: 2px solid #e3e6f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .messages-dropdown .status-indicator {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 1rem;
            height: 1rem;
            border: 2px solid #fff;
            border-radius: 50%;
        }

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

        .messages-dropdown .read-more {
            background-color: #f8f9fc;
            padding: 1rem;
            color: #4e73df !important;
            font-weight: 600;
            text-align: center;
            border-bottom-left-radius: 0.35rem;
            border-bottom-right-radius: 0.35rem;
            transition: all 0.2s ease;
        }

        /* .messages-dropdown .read-more:hover {
            background-color: #eaecf4;
        } */
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
            <div class="user-avatar mb-3 position-relative">
                <img src="<?php echo $profile_image; ?>" alt="Patient" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                <button type="button" class="btn btn-sm btn-primary position-absolute" style="bottom: 0; right: 0;" data-bs-toggle="modal" data-bs-target="#changeProfileImageModal" title="Change Profile Picture">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            <div class="user-info">
                <h6 class="mb-0 text-white"><?php echo htmlspecialchars($patient_name); ?></h6>
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
                        <span class="badge bg-danger badge-counter"><?php echo count(array_filter($notifications, function($n) { return !$n['is_read']; })); ?>+</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">Notifications Center</h6>
                        <?php foreach ($notifications as $notification): ?>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-<?php echo $notification['is_read'] ? 'secondary' : 'primary'; ?>">
                                        <i class="fas fa-<?php echo htmlspecialchars($notification['icon']); ?> text-white"></i>
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
                                <div>
                                    <div class="text-truncate"><?php echo htmlspecialchars($message['message']); ?></div>
                                    <div class="small text-muted"><?php echo htmlspecialchars($message['sender']); ?> · <?php echo htmlspecialchars($message['time']); ?></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                        <a class="dropdown-item text-center small text-primary read-more" href="#">Read More Messages</a>
                    </div>
                </li>

                <div class="topbar-divider d-none d-sm-block"></div>                <!-- User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($patient_name); ?></span>
                        <img class="img-profile rounded-circle" src="<?php echo $profile_image; ?>">
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

        <!-- Profile Image Change Modal -->
        <div class="modal fade" id="changeProfileImageModal" tabindex="-1" aria-labelledby="changeProfileImageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeProfileImageModalLabel">Change Profile Picture</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="update_profile_image.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="profileImage" class="form-label">Choose New Profile Picture</label>
                                <input type="file" class="form-control" id="profileImage" name="profileImage" accept="image/*" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Upload Picture</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
