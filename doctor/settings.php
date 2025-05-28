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

// Handle profile image upload
$upload_success = false;
$upload_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $upload_dir = '../uploads/profile_images/';
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_name = $doctor_id . '_' . basename($_FILES['profile_image']['name']);
    $target_file = $upload_dir . $file_name;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is an actual image
    $check = getimagesize($_FILES['profile_image']['tmp_name']);
    if ($check !== false) {
        // Check file size (limit to 5MB)
        if ($_FILES['profile_image']['size'] > 5000000) {
            $upload_error = 'Sorry, your file is too large. Max size is 5MB.';
        } else {
            // Allow certain file formats
            if ($image_file_type != 'jpg' && $image_file_type != 'png' && $image_file_type != 'jpeg') {
                $upload_error = 'Sorry, only JPG, JPEG & PNG files are allowed.';
            } else {
                // Upload file
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                    // Save the image path in session for now (in a real app, save to database)
                    $_SESSION['profile_image'] = $target_file;
                    $upload_success = true;
                } else {
                    $upload_error = 'Sorry, there was an error uploading your file.';
                }
            }
        }
    } else {
        $upload_error = 'File is not an image.';
    }
}

// Placeholder doctor profile data (in a real system, you would fetch this from the database)
$doctor_profile = [
    'name' => $doctor_name,
    'email' => 'doctor@healthconnect.com',
    'phone' => '(555) 123-4567',
    'specialization' => 'Cardiology',
    'license_number' => 'MD12345678',
    'education' => 'Harvard Medical School',
    'experience' => '15 years',
    'languages' => 'English, Spanish',
    'bio' => 'Dr. ' . $doctor_name . ' is a board-certified cardiologist with over 15 years of experience in treating heart conditions. Specializing in preventive cardiology and heart failure management.',
    'address' => '456 Medical Avenue, Healthville, CA 67890',
    'notification_preferences' => [
        'email' => true,
        'sms' => true,
        'app' => false
    ],
    'schedule_preferences' => [
        'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        'working_hours' => '9:00 AM - 5:00 PM',
        'appointment_duration' => 30,
        'break_time' => '12:00 PM - 1:00 PM',
        'max_appointments_per_day' => 16
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Doctor Dashboard | HealthConnect</title>
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
                                        <i class="fas fa-calendar-check text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">Today</div>
                                    <span>New appointment with Jane Smith at 3:00 PM</span>
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
                    <h1 class="h3 mb-0 text-gray-800">Settings</h1>
                </div>

                <!-- Settings Tabs -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Profile</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab" aria-controls="account" aria-selected="false">Account</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">Security</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="false">Notifications</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button" role="tab" aria-controls="schedule" aria-selected="false">Schedule</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="settingsTabsContent">
                                    <!-- Profile Tab -->
                                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="row mb-4">
                                                <div class="col-md-3 text-center">
                                                    <div class="mb-3">
                                                        <?php
                                                        // Display the uploaded image if available, otherwise show default
                                                        $profile_image = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : '../img/doctor-avatar.jpg';
                                                        ?>
                                                        <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                                        <div>
                                                            <input type="file" name="profile_image" id="profile_image" class="d-none" accept="image/jpeg,image/png,image/jpg">
                                                            <label for="profile_image" class="btn btn-primary btn-sm">Change Photo</label>
                                                            <button type="button" id="remove_photo" class="btn btn-outline-secondary btn-sm ms-2">Remove</button>
                                                        </div>
                                                        <?php if ($upload_success): ?>
                                                            <div class="alert alert-success mt-2" role="alert">
                                                                Profile image updated successfully!
                                                            </div>
                                                        <?php elseif (!empty($upload_error)): ?>
                                                            <div class="alert alert-danger mt-2" role="alert">
                                                                <?php echo htmlspecialchars($upload_error); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="fullName" class="form-label">Full Name</label>
                                                            <input type="text" class="form-control" id="fullName" value="<?php echo htmlspecialchars($doctor_profile['name']); ?>">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($doctor_profile['email']); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="phone" class="form-label">Phone Number</label>
                                                            <input type="tel" class="form-control" id="phone" value="<?php echo htmlspecialchars($doctor_profile['phone']); ?>">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="specialization" class="form-label">Specialization</label>
                                                            <input type="text" class="form-control" id="specialization" value="<?php echo htmlspecialchars($doctor_profile['specialization']); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="licenseNumber" class="form-label">License Number</label>
                                                            <input type="text" class="form-control" id="licenseNumber" value="<?php echo htmlspecialchars($doctor_profile['license_number']); ?>">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="education" class="form-label">Education</label>
                                                            <input type="text" class="form-control" id="education" value="<?php echo htmlspecialchars($doctor_profile['education']); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="experience" class="form-label">Experience</label>
                                                            <input type="text" class="form-control" id="experience" value="<?php echo htmlspecialchars($doctor_profile['experience']); ?>">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="languages" class="form-label">Languages</label>
                                                            <input type="text" class="form-control" id="languages" value="<?php echo htmlspecialchars($doctor_profile['languages']); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="address" class="form-label">Address</label>
                                                        <input type="text" class="form-control" id="address" value="<?php echo htmlspecialchars($doctor_profile['address']); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="bio" class="form-label">Professional Bio</label>
                                                        <textarea class="form-control" id="bio" rows="4"><?php echo htmlspecialchars($doctor_profile['bio']); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-secondary me-2">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Account Tab -->
                                    <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
                                        <h5 class="mb-4">Account Settings</h5>
                                        <form>
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username" value="dr<?php echo strtolower(str_replace(' ', '', $doctor_profile['name'])); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="accountEmail" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="accountEmail" value="<?php echo htmlspecialchars($doctor_profile['email']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="timeZone" class="form-label">Time Zone</label>
                                                <select class="form-select" id="timeZone">
                                                    <option selected>Pacific Standard Time (PST)</option>
                                                    <option>Eastern Standard Time (EST)</option>
                                                    <option>Central Standard Time (CST)</option>
                                                    <option>Mountain Standard Time (MST)</option>
                                                    <option>Greenwich Mean Time (GMT)</option>
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label for="language" class="form-label">Platform Language</label>
                                                <select class="form-select" id="language">
                                                    <option selected>English</option>
                                                    <option>Spanish</option>
                                                    <option>French</option>
                                                    <option>German</option>
                                                    <option>Chinese</option>
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <h6>Account Actions</h6>
                                                <hr>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Deactivate Account</h6>
                                                        <p class="text-muted small mb-0">Temporarily disable your account</p>
                                                    </div>
                                                    <button type="button" class="btn btn-outline-warning">Deactivate</button>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Delete Account</h6>
                                                        <p class="text-muted small mb-0">Permanently delete your account and all data</p>
                                                    </div>
                                                    <button type="button" class="btn btn-outline-danger">Delete</button>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-secondary me-2">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Security Tab -->
                                    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                        <h5 class="mb-4">Security Settings</h5>
                                        <form>
                                            <div class="mb-4">
                                                <h6>Change Password</h6>
                                                <div class="mb-3">
                                                    <label for="currentPassword" class="form-label">Current Password</label>
                                                    <input type="password" class="form-control" id="currentPassword">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="newPassword" class="form-label">New Password</label>
                                                    <input type="password" class="form-control" id="newPassword">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                                    <input type="password" class="form-control" id="confirmPassword">
                                                </div>
                                                <div class="text-end">
                                                    <button type="button" class="btn btn-primary">Update Password</button>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="mb-4">
                                                <h6>Two-Factor Authentication</h6>
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" id="twoFactorSwitch">
                                                    <label class="form-check-label" for="twoFactorSwitch">Enable two-factor authentication</label>
                                                </div>
                                                <p class="text-muted small">When two-factor authentication is enabled, you'll be required to provide a unique code each time you log in. You can get this code from the authenticator app on your phone.</p>
                                                <button type="button" class="btn btn-outline-primary btn-sm">Set Up Two-Factor Authentication</button>
                                            </div>
                                            <hr>
                                            <div class="mb-4">
                                                <h6>Login History</h6>
                                                <p class="text-muted small">Recent logins to your account.</p>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Device</th>
                                                                <th>Location</th>
                                                                <th>IP Address</th>
                                                                <th>Date/Time</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Chrome on Windows</td>
                                                                <td>Los Angeles, CA</td>
                                                                <td>192.168.1.1</td>
                                                                <td>June 15, 2023, 10:30 AM</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Safari on iPhone</td>
                                                                <td>Los Angeles, CA</td>
                                                                <td>192.168.1.2</td>
                                                                <td>June 14, 2023, 2:15 PM</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Chrome on Windows</td>
                                                                <td>Los Angeles, CA</td>
                                                                <td>192.168.1.1</td>
                                                                <td>June 13, 2023, 9:45 AM</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Notifications Tab -->
                                    <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                                        <h5 class="mb-4">Notification Preferences</h5>
                                        <form>
                                            <div class="mb-4">
                                                <h6>Notification Channels</h6>
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="emailNotify" <?php echo $doctor_profile['notification_preferences']['email'] ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="emailNotify">Email Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="smsNotify" <?php echo $doctor_profile['notification_preferences']['sms'] ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="smsNotify">SMS Notifications</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="appNotify" <?php echo $doctor_profile['notification_preferences']['app'] ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="appNotify">In-App Notifications</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="mb-4">
                                                <h6>Notification Types</h6>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="newAppointmentNotify" checked>
                                                        <label class="form-check-label" for="newAppointmentNotify">New appointment requests</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="appointmentReminder" checked>
                                                        <label class="form-check-label" for="appointmentReminder">Appointment reminders</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="appointmentChange" checked>
                                                        <label class="form-check-label" for="appointmentChange">Appointment changes/cancellations</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="patientMessages" checked>
                                                        <label class="form-check-label" for="patientMessages">Patient messages</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="paymentNotify" checked>
                                                        <label class="form-check-label" for="paymentNotify">Payment confirmations</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="reviewNotify" checked>
                                                        <label class="form-check-label" for="reviewNotify">New patient reviews</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="systemUpdates">
                                                        <label class="form-check-label" for="systemUpdates">System updates and announcements</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-secondary me-2">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Schedule Tab -->
                                    <div class="tab-pane fade" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
                                        <h5 class="mb-4">Schedule Preferences</h5>
                                        <form>
                                            <div class="mb-4">
                                                <h6>Working Days</h6>
                                                <div class="row">
                                                    <?php 
                                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                                    foreach ($days as $day) { 
                                                        $checked = in_array($day, $doctor_profile['schedule_preferences']['working_days']) ? 'checked' : '';
                                                    ?>
                                                    <div class="col-md-4 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="<?php echo strtolower($day); ?>Check" <?php echo $checked; ?>>
                                                            <label class="form-check-label" for="<?php echo strtolower($day); ?>Check"><?php echo $day; ?></label>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h6>Working Hours</h6>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="startTime" class="form-label">Start Time</label>
                                                            <input type="time" class="form-control" id="startTime" value="09:00">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="endTime" class="form-label">End Time</label>
                                                            <input type="time" class="form-control" id="endTime" value="17:00">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Break Time</h6>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="breakStart" class="form-label">Start Time</label>
                                                            <input type="time" class="form-control" id="breakStart" value="12:00">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="breakEnd" class="form-label">End Time</label>
                                                            <input type="time" class="form-control" id="breakEnd" value="13:00">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <label for="appointmentDuration" class="form-label">Appointment Duration (minutes)</label>
                                                    <select class="form-select" id="appointmentDuration">
                                                        <option value="15">15 minutes</option>
                                                        <option value="20">20 minutes</option>
                                                        <option value="30" selected>30 minutes</option>
                                                        <option value="45">45 minutes</option>
                                                        <option value="60">60 minutes</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="maxAppointments" class="form-label">Maximum Appointments Per Day</label>
                                                    <input type="number" class="form-control" id="maxAppointments" value="<?php echo $doctor_profile['schedule_preferences']['max_appointments_per_day']; ?>" min="1" max="50">
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <h6>Appointment Booking Restrictions</h6>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="minAdvanceBooking" class="form-label">Minimum Advance Notice (hours)</label>
                                                        <input type="number" class="form-control" id="minAdvanceBooking" value="24" min="0">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="maxAdvanceBooking" class="form-label">Maximum Advance Booking (days)</label>
                                                        <input type="number" class="form-control" id="maxAdvanceBooking" value="60" min="1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <h6>Time Off / Vacation Settings</h6>
                                                <button type="button" class="btn btn-outline-primary btn-sm mb-3">
                                                    <i class="fas fa-plus me-1"></i> Add Time Off
                                                </button>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Reason</th>
                                                                <th>Start Date</th>
                                                                <th>End Date</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Summer Vacation</td>
                                                                <td>July 15, 2023</td>
                                                                <td>July 22, 2023</td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-outline-danger">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Medical Conference</td>
                                                                <td>August 10, 2023</td>
                                                                <td>August 12, 2023</td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-outline-danger">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-secondary me-2">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
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
        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
        });
        
        // Auto-submit form when file is selected
        document.getElementById('profile_image').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                // Show preview before upload
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.img-fluid').src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
                
                // Submit form
                this.form.submit();
            }
        });
        
        // Handle remove photo button
        document.getElementById('remove_photo').addEventListener('click', function() {
            // In a real application, you would send an AJAX request to remove the photo
            // For now, just reset to default image
            document.querySelector('.img-fluid').src = '../img/doctor-avatar.jpg';
        });
    </script>
</body>
</html>