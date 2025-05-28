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

// Process settings form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_general'])) {
        // Process general settings update
        $success_message = "General settings updated successfully!";
    } elseif (isset($_POST['update_admin_password'])) {
        // Process admin password update
        $success_message = "Administrator password updated successfully!";
    } elseif (isset($_POST['update_email'])) {
        // Process email settings update
        $success_message = "Email settings updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings | Admin Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
        }
        .dashboard-sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 999;
            background: #2c3e50;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        .dashboard-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        .dashboard-sidebar .sidebar-header {
            padding: 20px;
            background: #1a2a3a;
        }
        .dashboard-sidebar .sidebar-user {
            padding: 20px 0;
        }
        .dashboard-sidebar .user-avatar img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }
        .sidebar-nav .nav-item {
            margin-bottom: 5px;
        }
        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 10px 20px;
            border-radius: 5px;
            margin: 0 10px;
            transition: all 0.3s;
        }
        .sidebar-nav .nav-link:hover, 
        .sidebar-nav .nav-item.active .nav-link {
            background: #3498db;
            color: #fff;
        }
        .sidebar-nav .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 24px;
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .card-header h6 {
            font-weight: 600;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 1rem 1.5rem;
        }
        .nav-tabs .nav-link.active {
            color: #4e73df;
            border-bottom: 2px solid #4e73df;
            background-color: transparent;
        }
        .nav-tabs .nav-link:hover {
            color: #4e73df;
            border-color: transparent;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        @media (max-width: 768px) {
            .dashboard-sidebar {
                margin-left: -250px;
            }
            .dashboard-sidebar.show {
                margin-left: 0;
            }
            .dashboard-content {
                margin-left: 0;
            }
        }
    </style>
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
                <li class="nav-item active">
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
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">Today</div>
                                    <span class="fw-bold">System update scheduled for tonight</span>
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
                    <h1 class="h3 mb-0 text-gray-800">System Settings</h1>
                </div>

                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($success_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($error_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Settings Content -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                                    <i class="fas fa-sliders-h me-2"></i> General
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab" aria-controls="appearance" aria-selected="false">
                                    <i class="fas fa-palette me-2"></i> Appearance
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab" aria-controls="email" aria-selected="false">
                                    <i class="fas fa-envelope me-2"></i> Email
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                                    <i class="fas fa-shield-alt me-2"></i> Security
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="backup-tab" data-bs-toggle="tab" data-bs-target="#backup" type="button" role="tab" aria-controls="backup" aria-selected="false">
                                    <i class="fas fa-database me-2"></i> Backup
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="settingsTabsContent">
                            <!-- General Settings Tab -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                <h5 class="card-title mb-4">General Settings</h5>
                                <form action="settings.php" method="POST">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="siteName" class="form-label">Site Name</label>
                                                <input type="text" class="form-control" id="siteName" name="site_name" value="HealthConnect">
                                                <div class="form-text">The name of your healthcare platform.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="siteTagline" class="form-label">Tagline</label>
                                                <input type="text" class="form-control" id="siteTagline" name="site_tagline" value="Your Health, Our Priority">
                                                <div class="form-text">A short description or slogan for your platform.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="adminEmail" class="form-label">Admin Email</label>
                                                <input type="email" class="form-control" id="adminEmail" name="admin_email" value="admin@healthconnect.com">
                                                <div class="form-text">The main administrative email address.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="timezone" class="form-label">Default Timezone</label>
                                                <select class="form-select" id="timezone" name="timezone">
                                                    <option value="UTC" selected>UTC</option>
                                                    <option value="America/New_York">Eastern Time (ET)</option>
                                                    <option value="America/Chicago">Central Time (CT)</option>
                                                    <option value="America/Denver">Mountain Time (MT)</option>
                                                    <option value="America/Los_Angeles">Pacific Time (PT)</option>
                                                    <option value="Asia/Kolkata">India Standard Time (IST)</option>
                                                </select>
                                                <div class="form-text">The default timezone for the system.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="dateFormat" class="form-label">Date Format</label>
                                                <select class="form-select" id="dateFormat" name="date_format">
                                                    <option value="Y-m-d" selected>2023-01-31</option>
                                                    <option value="m/d/Y">01/31/2023</option>
                                                    <option value="d/m/Y">31/01/2023</option>
                                                    <option value="M j, Y">Jan 31, 2023</option>
                                                </select>
                                                <div class="form-text">How dates should be displayed throughout the system.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="timeFormat" class="form-label">Time Format</label>
                                                <select class="form-select" id="timeFormat" name="time_format">
                                                    <option value="H:i" selected>24 Hour (14:30)</option>
                                                    <option value="h:i A">12 Hour (02:30 PM)</option>
                                                </select>
                                                <div class="form-text">How times should be displayed throughout the system.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="defaultLanguage" class="form-label">Default Language</label>
                                                <select class="form-select" id="defaultLanguage" name="default_language">
                                                    <option value="en" selected>English</option>
                                                    <option value="es">Spanish</option>
                                                    <option value="fr">French</option>
                                                    <option value="de">German</option>
                                                    <option value="hi">Hindi</option>
                                                </select>
                                                <div class="form-text">The default language for the system.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="enableRegistration" class="form-label">User Registration</label>
                                                <select class="form-select" id="enableRegistration" name="enable_registration">
                                                    <option value="1" selected>Enabled</option>
                                                    <option value="0">Disabled</option>
                                                </select>
                                                <div class="form-text">Allow new users to register on the platform.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="update_general" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Save Settings
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Appearance Settings Tab -->
                            <div class="tab-pane fade" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                                <h5 class="card-title mb-4">Appearance Settings</h5>
                                <form action="settings.php" method="POST">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="primaryColor" class="form-label">Primary Color</label>
                                                <div class="input-group">
                                                    <input type="color" class="form-control form-control-color" id="primaryColor" name="primary_color" value="#4e73df">
                                                    <input type="text" class="form-control" value="#4e73df" id="primaryColorText" readonly>
                                                </div>
                                                <div class="form-text">Main brand color for buttons and highlights.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="secondaryColor" class="form-label">Secondary Color</label>
                                                <div class="input-group">
                                                    <input type="color" class="form-control form-control-color" id="secondaryColor" name="secondary_color" value="#1cc88a">
                                                    <input type="text" class="form-control" value="#1cc88a" id="secondaryColorText" readonly>
                                                </div>
                                                <div class="form-text">Secondary color for accents and UI elements.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="logo" class="form-label">Logo</label>
                                                <input type="file" class="form-control" id="logo" name="logo">
                                                <div class="form-text">Recommended size: 200x60 pixels. PNG or SVG format preferred.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="favicon" class="form-label">Favicon</label>
                                                <input type="file" class="form-control" id="favicon" name="favicon">
                                                <div class="form-text">Recommended size: 32x32 pixels. ICO or PNG format.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="theme" class="form-label">Admin Theme</label>
                                                <select class="form-select" id="theme" name="theme">
                                                    <option value="light" selected>Light</option>
                                                    <option value="dark">Dark</option>
                                                    <option value="blue">Blue</option>
                                                </select>
                                                <div class="form-text">The theme for the admin dashboard.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="defaultFontSize" class="form-label">Default Font Size</label>
                                                <select class="form-select" id="defaultFontSize" name="default_font_size">
                                                    <option value="small">Small</option>
                                                    <option value="medium" selected>Medium</option>
                                                    <option value="large">Large</option>
                                                </select>
                                                <div class="form-text">Base font size for the entire application.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="update_appearance" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Save Appearance
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Email Settings Tab -->
                            <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                                <h5 class="card-title mb-4">Email Settings</h5>
                                <form action="settings.php" method="POST">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtpHost" class="form-label">SMTP Host</label>
                                                <input type="text" class="form-control" id="smtpHost" name="smtp_host" value="smtp.example.com">
                                                <div class="form-text">Your mail server hostname.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtpPort" class="form-label">SMTP Port</label>
                                                <input type="number" class="form-control" id="smtpPort" name="smtp_port" value="587">
                                                <div class="form-text">Mail server port (usually 587 for TLS or 465 for SSL).</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtpUsername" class="form-label">SMTP Username</label>
                                                <input type="text" class="form-control" id="smtpUsername" name="smtp_username" value="user@example.com">
                                                <div class="form-text">The username for your mail server.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtpPassword" class="form-label">SMTP Password</label>
                                                <input type="password" class="form-control" id="smtpPassword" name="smtp_password" value="password">
                                                <div class="form-text">The password for your mail server.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtpEncryption" class="form-label">Encryption</label>
                                                <select class="form-select" id="smtpEncryption" name="smtp_encryption">
                                                    <option value="tls" selected>TLS</option>
                                                    <option value="ssl">SSL</option>
                                                    <option value="none">None</option>
                                                </select>
                                                <div class="form-text">The encryption method used by your mail server.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="fromEmail" class="form-label">From Email</label>
                                                <input type="email" class="form-control" id="fromEmail" name="from_email" value="no-reply@healthconnect.com">
                                                <div class="form-text">The email address that will be shown as the sender.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="fromName" class="form-label">From Name</label>
                                                <input type="text" class="form-control" id="fromName" name="from_name" value="HealthConnect">
                                                <div class="form-text">The name that will be shown as the sender.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="testEmail" class="form-label">Test Email</label>
                                                <div class="input-group">
                                                    <input type="email" class="form-control" id="testEmail" name="test_email" placeholder="Enter email to test">
                                                    <button class="btn btn-outline-secondary" type="button" id="sendTestEmail">Send Test</button>
                                                </div>
                                                <div class="form-text">Send a test email to verify your settings.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="update_email" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Save Email Settings
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Security Settings Tab -->
                            <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                <h5 class="card-title mb-4">Security Settings</h5>
                                <form action="settings.php" method="POST">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="currentPassword" class="form-label">Current Administrator Password</label>
                                                <input type="password" class="form-control" id="currentPassword" name="current_password">
                                                <div class="form-text">Enter your current password to make changes.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="newPassword" class="form-label">New Password</label>
                                                <input type="password" class="form-control" id="newPassword" name="new_password">
                                                <div class="form-text">Enter a new secure password.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password">
                                                <div class="form-text">Confirm your new password.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="passwordPolicy" class="form-label">Password Policy</label>
                                                <select class="form-select" id="passwordPolicy" name="password_policy">
                                                    <option value="low">Low (Minimum 6 characters)</option>
                                                    <option value="medium" selected>Medium (8+ characters, letters & numbers)</option>
                                                    <option value="high">High (8+ chars, uppercase, lowercase, numbers, special)</option>
                                                </select>
                                                <div class="form-text">Password requirements for all users.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="twoFactorAuth" class="form-label">Two-Factor Authentication</label>
                                                <select class="form-select" id="twoFactorAuth" name="two_factor_auth">
                                                    <option value="0">Disabled</option>
                                                    <option value="1" selected>Optional for users</option>
                                                    <option value="2">Required for all users</option>
                                                </select>
                                                <div class="form-text">Enable 2FA for additional security.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sessionTimeout" class="form-label">Session Timeout (minutes)</label>
                                                <input type="number" class="form-control" id="sessionTimeout" name="session_timeout" value="30">
                                                <div class="form-text">How long until an inactive user is logged out.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="update_admin_password" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Update Security Settings
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Backup & Restore Tab -->
                            <div class="tab-pane fade" id="backup" role="tabpanel" aria-labelledby="backup-tab">
                                <h5 class="card-title mb-4">Database Backup & Restore</h5>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card border-left-primary h-100">
                                            <div class="card-body">
                                                <h5 class="card-title"><i class="fas fa-download me-2"></i> Backup Database</h5>
                                                <p class="card-text">Create a backup of your entire database. This includes all patient records, appointments, and system settings.</p>
                                                <button type="button" class="btn btn-primary">
                                                    <i class="fas fa-database me-1"></i> Create Backup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-left-info h-100">
                                            <div class="card-body">
                                                <h5 class="card-title"><i class="fas fa-upload me-2"></i> Restore Database</h5>
                                                <p class="card-text">Restore your database from a previous backup. This will overwrite your current data.</p>
                                                <div class="input-group mb-3">
                                                    <input type="file" class="form-control" id="backupFile">
                                                    <button class="btn btn-info" type="button">Restore</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="m-0 fw-bold text-primary">Backup History</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Filename</th>
                                                        <th>Date Created</th>
                                                        <th>Size</th>
                                                        <th>Created By</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>backup_2023_06_25_143022.sql</td>
                                                        <td>June 25, 2023 14:30:22</td>
                                                        <td>4.2 MB</td>
                                                        <td>Admin</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info me-1"><i class="fas fa-download"></i></button>
                                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>backup_2023_06_18_091536.sql</td>
                                                        <td>June 18, 2023 09:15:36</td>
                                                        <td>4.1 MB</td>
                                                        <td>Admin</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info me-1"><i class="fas fa-download"></i></button>
                                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>backup_2023_06_11_220145.sql</td>
                                                        <td>June 11, 2023 22:01:45</td>
                                                        <td>3.9 MB</td>
                                                        <td>Admin</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info me-1"><i class="fas fa-download"></i></button>
                                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.1.3 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/main.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
            document.querySelector('.dashboard-content').classList.toggle('shift');
        });
        
        // Update color input text fields when color picker changes
        document.getElementById('primaryColor').addEventListener('input', function() {
            document.getElementById('primaryColorText').value = this.value;
        });
        
        document.getElementById('secondaryColor').addEventListener('input', function() {
            document.getElementById('secondaryColorText').value = this.value;
        });
        
        // Test email button functionality
        document.getElementById('sendTestEmail').addEventListener('click', function() {
            const testEmail = document.getElementById('testEmail').value;
            
            if (testEmail.trim() === '') {
                alert('Please enter an email address for testing.');
                return;
            }
            
            // In a real application, this would make an AJAX call to send a test email
            alert('Test email would be sent to: ' + testEmail);
        });
        
        // Add card hover effect
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
                card.style.boxShadow = '0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2)';
                card.style.transition = 'all 0.3s ease';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = '0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1)';
                card.style.transition = 'all 0.3s ease';
            });
        });
    </script>
</body>
</html> 