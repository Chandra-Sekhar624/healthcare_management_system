<?php
// Start session
session_start();

// Include database configuration and functions
include '../includes/config.php';
include '../includes/patient_functions.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get patient information
$patient_id = $_SESSION['user_id'];
$patient_name = $_SESSION['user_name'];
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// Get user details including phone number
$user_sql = "SELECT phone FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->execute([$patient_id]);
$user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);
$phone = $user_data['phone'] ?? '';

// Initialize patient profile array with default values
$patient_profile = [
    'id' => $patient_id,
    'name' => $patient_name,
    'email' => $email, // Add email from session
    'phone' => $phone, // Add phone from database
    'dob' => null,     // Initialize date of birth as null
    'gender' => '',    // Initialize gender as empty string
    'blood_type' => '',
    'height' => '',
    'weight' => '',
    'address' => [
        'street' => '',
        'city' => '',
        'state' => '',
        'zip' => '',
        'country' => ''
    ],
    'emergency_contact' => [
        'name' => '',
        'relationship' => '',
        'phone' => '',
        'email' => ''
    ],
    'medical_info' => [
        'allergies' => [],
        'chronic_conditions' => [],
        'current_medications' => []
    ],
    'insurance' => [
        'provider' => '',
        'policy_number' => '',
        'group_number' => '',
        'primary_holder' => ''
    ],
    'account' => [
        'username' => '',
        'email_verified' => false,
        'phone_verified' => false,
        'two_factor_enabled' => false,
        'last_login' => date('Y-m-d H:i:s'),
        'account_created' => date('Y-m-d H:i:s')
    ]
];

// Try to get patient data from database if available
$sql = "SELECT * FROM patients WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$patient_id]);
$patient_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($patient_data) {
    // Update patient profile with database values
    $patient_profile['blood_type'] = $patient_data['blood_group'] ?? '';
    $patient_profile['gender'] = $patient_data['gender'] ?? '';
    $patient_profile['dob'] = $patient_data['date_of_birth'] ?? null;
    $patient_profile['height'] = $patient_data['height'] ?? '';
    $patient_profile['weight'] = $patient_data['weight'] ?? '';
    
    // Parse address if it's a JSON string
    if (!empty($patient_data['address'])) {
        $address_data = json_decode($patient_data['address'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $patient_profile['address'] = array_merge($patient_profile['address'], $address_data);
        }
    }
}

// Create uploads directory if it doesn't exist
$upload_dir = '../uploads/profile_images/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
} else {
    // Ensure directory is writable
    chmod($upload_dir, 0777);
}

// Debug information
$debug_info = '';
$debug_info .= 'Upload directory: ' . realpath($upload_dir) . '<br>';
$debug_info .= 'Directory exists: ' . (file_exists($upload_dir) ? 'Yes' : 'No') . '<br>';
$debug_info .= 'Directory writable: ' . (is_writable($upload_dir) ? 'Yes' : 'No') . '<br>';

// Handle profile image upload
$image_upload_error = '';
$image_upload_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_image'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $file_name = $_FILES['profile_image']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Check file extension
        $allowed_exts = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($file_ext, $allowed_exts)) {
            // Generate unique filename
            $new_file_name = 'profile_' . $patient_id . '_' . time() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_file_name;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Update database with new image path
                try {
                    $sql = "UPDATE users SET profile_image = :profile_image WHERE id = :user_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':profile_image', $new_file_name);
                    $stmt->bindParam(':user_id', $patient_id);
                    $stmt->execute();
                    
                    $image_upload_success = true;
                } catch (PDOException $e) {
                    $image_upload_error = 'Database error: ' . $e->getMessage();
                }
            } else {
                $image_upload_error = 'Failed to upload image. Please try again.';
            }
        } else {
            $image_upload_error = 'Invalid file type. Only JPG, JPEG, PNG and GIF files are allowed.';
        }
    } else {
        $image_upload_error = 'Please select an image to upload.';
    }
}

// Fetch user data from database
try {
    // Get user data
    $user_sql = "SELECT * FROM users WHERE id = :user_id";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bindParam(':user_id', $patient_id);
    $user_stmt->execute();
    $user_data = $user_stmt->fetch();
    
    // Get patient data
    $patient_sql = "SELECT * FROM patients WHERE user_id = :user_id";
    $patient_stmt = $conn->prepare($patient_sql);
    $patient_stmt->bindParam(':user_id', $patient_id);
    $patient_stmt->execute();
    $patient_data = $patient_stmt->fetch();
    
    // Get profile image path using the function
    $profile_image = getPatientProfileImage($conn, $patient_id);
    
} catch (PDOException $e) {
    // Handle database error
    $db_error = 'Database error: ' . $e->getMessage();
    $profile_image = '../img/patient-avatar.jpg';
}


// Handle profile update form submission
$update_success = false;
$update_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    try {
        // Start transaction
        $conn->beginTransaction();
        
        // Process profile image if uploaded
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['profile_image']['tmp_name'];
            $file_name = $_FILES['profile_image']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Check file extension
            $allowed_exts = array('jpg', 'jpeg', 'png', 'gif');
            
            if (in_array($file_ext, $allowed_exts)) {
                // Generate unique filename
                $new_file_name = 'profile_' . $patient_id . '_' . time() . '.' . $file_ext;
                $target_path = $upload_dir . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $target_path)) {
                    // Update user table with new profile image
                    $update_image_sql = "UPDATE users SET profile_image = :profile_image WHERE id = :user_id";
                    $update_image_stmt = $conn->prepare($update_image_sql);
                    $update_image_stmt->bindParam(':profile_image', $new_file_name);
                    $update_image_stmt->bindParam(':user_id', $patient_id);
                    $update_image_stmt->execute();
                    
                    $image_upload_success = true;
                } else {
                    throw new Exception('Failed to upload profile image.');
                }
            } else {
                throw new Exception('Invalid file type. Only JPG, JPEG, PNG and GIF files are allowed.');
            }
        }
        
        // Process other profile data
        // Update users table
        $sql = "UPDATE users SET 
                first_name = :first_name,
                last_name = :last_name,
                email = :email,
                phone = :phone
                WHERE id = :user_id";
                
        $stmt = $conn->prepare($sql);
        
        // Extract first and last name from full name
        $full_name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $name_parts = explode(' ', $full_name);
        $first_name = $name_parts[0];
        $last_name = count($name_parts) > 1 ? end($name_parts) : '';
        
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':user_id', $patient_id);
        $stmt->execute();
        
        // Prepare address data
        $address = [
            'street' => isset($_POST['street']) ? trim($_POST['street']) : '',
            'city' => isset($_POST['city']) ? trim($_POST['city']) : '',
            'state' => isset($_POST['state']) ? trim($_POST['state']) : '',
            'zip' => isset($_POST['zip']) ? trim($_POST['zip']) : '',
            'country' => isset($_POST['country']) ? trim($_POST['country']) : ''
        ];
        
        // Prepare emergency contact data
        $emergency_contact = [
            'name' => isset($_POST['ec_name']) ? trim($_POST['ec_name']) : '',
            'relationship' => isset($_POST['ec_relationship']) ? trim($_POST['ec_relationship']) : '',
            'phone' => isset($_POST['ec_phone']) ? trim($_POST['ec_phone']) : '',
            'email' => isset($_POST['ec_email']) ? trim($_POST['ec_email']) : ''
        ];
        
        // Prepare medical info data
        $allergies = isset($_POST['allergies']) ? array_map('trim', explode(',', $_POST['allergies'])) : [];
        $chronic_conditions = isset($_POST['chronic_conditions']) ? array_map('trim', explode(',', $_POST['chronic_conditions'])) : [];
        $current_medications = isset($_POST['current_medications']) ? array_map('trim', explode(',', $_POST['current_medications'])) : [];
        
        // Update patients table with all fields including height and weight
        $sql = "UPDATE patients SET 
                address = :address,
                date_of_birth = :dob,
                gender = :gender,
                blood_group = :blood_type,
                height = :height,
                weight = :weight
                WHERE user_id = :user_id";
                
        $stmt = $conn->prepare($sql);
        
        $dob = !empty($_POST['dob']) ? date('Y-m-d', strtotime($_POST['dob'])) : null;
        $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
        $blood_type = isset($_POST['blood_type']) ? $_POST['blood_type'] : '';
        $height = !empty($_POST['height']) ? (float)$_POST['height'] : null;
        $weight = !empty($_POST['weight']) ? (float)$_POST['weight'] : null;
        
        $stmt->bindParam(':address', json_encode($address));
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':blood_type', $blood_type);
        $stmt->bindParam(':height', $height, PDO::PARAM_STR);
        $stmt->bindParam(':weight', $weight, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $patient_id);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        // Update session data
        $_SESSION['user_name'] = $full_name;
        $_SESSION['email'] = $email;
        
        // Refresh the page to show updated data
        header('Location: profile.php?updated=1');
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        $update_error = 'Error updating profile: ' . $e->getMessage();
    }
}

// Check if coming back from a successful update
if (isset($_GET['updated']) && $_GET['updated'] == 1) {
    $update_success = true;
    
    // Debug: Verify data was saved correctly
    try {
        $debug_sql = "SELECT * FROM patients WHERE user_id = :user_id";
        $debug_stmt = $conn->prepare($debug_sql);
        $debug_stmt->bindParam(':user_id', $patient_id);
        $debug_stmt->execute();
        $patient_data = $debug_stmt->fetch(PDO::FETCH_ASSOC);
        
        // Store debug info
        $debug_info .= '<h4>Patient Data Saved:</h4>';
        $debug_info .= '<pre>' . print_r($patient_data, true) . '</pre>';
    } catch (PDOException $e) {
        $debug_info .= '<p>Error retrieving patient data: ' . $e->getMessage() . '</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Patient Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .profile-header {
            background-color: #f8f9fc;
            border-radius: 0.5rem;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .profile-info-card {
            margin-bottom: 1.5rem;
            border-left: 4px solid #4e73df;
        }
        .profile-section-heading {
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.25rem;
            color: #4e73df;
        }
        .info-label {
            font-weight: 600;
            color: #5a5c69;
        }
        .badge-verified {
            background-color: #1cc88a;
        }
        .badge-not-verified {
            background-color: #e74a3b;
        }
    </style>
</head>
<body class="dashboard-body">
    <!-- Sidebar Navigation -->
     <?php include 'sidebar_nav.php'; ?>    

            <!-- Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">My Profile</h1>
                    <div>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-user-edit fa-sm text-white-50 me-1"></i> Edit Profile
                        </a>
                        <a href="../logout.php" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                            <i class="fas fa-sign-out-alt fa-sm text-white-50 me-1"></i> Logout
                        </a>
                    </div>
                </div>

                <!-- Update Success Alert -->
                <?php if ($update_success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Your profile has been updated successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php if ($update_error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?php echo htmlspecialchars($update_error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <!-- Profile Header -->
                <div class="profile-header shadow">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img src="<?php echo $profile_image; ?>" alt="<?php echo htmlspecialchars($patient_profile['name']); ?>" class="profile-avatar">
                        </div>
                        <div class="col">
                            <h2 class="mb-1"><?php echo htmlspecialchars($patient_profile['name']); ?></h2>
                            <p class="text-muted mb-2">
                                <i class="fas fa-id-card me-2"></i> Patient ID: <?php echo htmlspecialchars($patient_profile['id']); ?>
                            </p>
                            <p class="mb-0">
                                <span class="badge bg-primary me-2">Patient</span>
                                <span class="badge bg-info me-2">
                                    <i class="fas fa-tint me-1"></i> <?php echo htmlspecialchars($patient_profile['blood_type']); ?>
                                </span>
                                
                                <?php if ($patient_profile['account']['email_verified']): ?>
                                <span class="badge badge-verified me-2">
                                    <i class="fas fa-check-circle me-1"></i> Email Verified
                                </span>
                                <?php endif; ?>
                                
                                <?php if ($patient_profile['account']['phone_verified']): ?>
                                <span class="badge badge-verified">
                                    <i class="fas fa-check-circle me-1"></i> Phone Verified
                                </span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-xl-6 col-lg-6">
                        <div class="card shadow mb-4 profile-info-card">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Full Name</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['name']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Email</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['email']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Phone</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['phone']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Date of Birth</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo !empty($patient_profile['dob']) ? date('F j, Y', strtotime($patient_profile['dob'])) : 'Not specified'; ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Gender</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo !empty($patient_profile['gender']) ? htmlspecialchars(ucfirst($patient_profile['gender'])) : 'Not specified'; ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Address</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0">
                                            <?php echo htmlspecialchars($patient_profile['address']['street']); ?><br>
                                            <?php echo htmlspecialchars($patient_profile['address']['city']); ?>, 
                                            <?php echo htmlspecialchars($patient_profile['address']['state']); ?> 
                                            <?php echo htmlspecialchars($patient_profile['address']['zip']); ?><br>
                                            <?php echo htmlspecialchars($patient_profile['address']['country']); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Information -->
                    <div class="col-xl-6 col-lg-6">
                        <div class="card shadow mb-4 profile-info-card">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Medical Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Blood Type</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['blood_type']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Height</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['height']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Weight</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['weight']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Allergies</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php if (empty($patient_profile['medical_info']['allergies'])): ?>
                                            <p class="mb-0">No known allergies</p>
                                        <?php else: ?>
                                            <ul class="mb-0">
                                                <?php foreach ($patient_profile['medical_info']['allergies'] as $allergy): ?>
                                                    <li><?php echo htmlspecialchars($allergy); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Chronic Conditions</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php if (empty($patient_profile['medical_info']['chronic_conditions'])): ?>
                                            <p class="mb-0">None</p>
                                        <?php else: ?>
                                            <ul class="mb-0">
                                                <?php foreach ($patient_profile['medical_info']['chronic_conditions'] as $condition): ?>
                                                    <li><?php echo htmlspecialchars($condition); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Current Medications</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php if (empty($patient_profile['medical_info']['current_medications'])): ?>
                                            <p class="mb-0">None</p>
                                        <?php else: ?>
                                            <ul class="mb-0">
                                                <?php foreach ($patient_profile['medical_info']['current_medications'] as $medication): ?>
                                                    <li><?php echo htmlspecialchars($medication); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Emergency Contact -->
                    <div class="col-xl-6 col-lg-6">
                        <div class="card shadow mb-4 profile-info-card">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Emergency Contact</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Name</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['emergency_contact']['name']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Relationship</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['emergency_contact']['relationship']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Phone</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['emergency_contact']['phone']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Email</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['emergency_contact']['email']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Insurance Information -->
                    <div class="col-xl-6 col-lg-6">
                        <div class="card shadow mb-4 profile-info-card">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Insurance Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Provider</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['insurance']['provider']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Policy Number</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['insurance']['policy_number']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Group Number</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['insurance']['group_number']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Primary Holder</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['insurance']['primary_holder']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="card shadow mb-4 profile-info-card">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Account Information</h6>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Username</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo htmlspecialchars($patient_profile['account']['username']); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Email Verification</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php if ($patient_profile['account']['email_verified']): ?>
                                            <span class="badge badge-verified">Verified</span>
                                        <?php else: ?>
                                            <span class="badge badge-not-verified">Not Verified</span>
                                            <a href="#" class="btn btn-sm btn-outline-primary ms-2">Verify Now</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Phone Verification</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php if ($patient_profile['account']['phone_verified']): ?>
                                            <span class="badge badge-verified">Verified</span>
                                        <?php else: ?>
                                            <span class="badge badge-not-verified">Not Verified</span>
                                            <a href="#" class="btn btn-sm btn-outline-primary ms-2">Verify Now</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Two-Factor Authentication</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <?php if ($patient_profile['account']['two_factor_enabled']): ?>
                                            <span class="badge badge-verified">Enabled</span>
                                            <a href="#" class="btn btn-sm btn-outline-danger ms-2">Disable</a>
                                        <?php else: ?>
                                            <span class="badge badge-not-verified">Disabled</span>
                                            <a href="#" class="btn btn-sm btn-outline-success ms-2">Enable</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Last Login</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo date('F j, Y g:i A', strtotime($patient_profile['account']['last_login'])); ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="info-label mb-0">Account Created</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <p class="mb-0"><?php echo date('F j, Y', strtotime($patient_profile['account']['account_created'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-12">
                                <a href="#" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="fas fa-key me-1"></i> Change Password
                                </a>
                                <a href="../logout.php" class="btn btn-danger">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="profile.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class=" row mb-3">
                                <label for="profile_image" class="form-label">Upload Profile Image</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image">
                                </div> 
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($patient_profile['name']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($patient_profile['email']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($patient_profile['phone']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($patient_profile['dob']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="Male" <?php echo $patient_profile['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo $patient_profile['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo $patient_profile['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="blood_type" class="form-label">Blood Type</label>
                                <select class="form-select" id="blood_type" name="blood_type">
                                    <option value="A+" <?php echo $patient_profile['blood_type'] === 'A+' ? 'selected' : ''; ?>>A+</option>
                                    <option value="A-" <?php echo $patient_profile['blood_type'] === 'A-' ? 'selected' : ''; ?>>A-</option>
                                    <option value="B+" <?php echo $patient_profile['blood_type'] === 'B+' ? 'selected' : ''; ?>>B+</option>
                                    <option value="B-" <?php echo $patient_profile['blood_type'] === 'B-' ? 'selected' : ''; ?>>B-</option>
                                    <option value="AB+" <?php echo $patient_profile['blood_type'] === 'AB+' ? 'selected' : ''; ?>>AB+</option>
                                    <option value="AB-" <?php echo $patient_profile['blood_type'] === 'AB-' ? 'selected' : ''; ?>>AB-</option>
                                    <option value="O+" <?php echo $patient_profile['blood_type'] === 'O+' ? 'selected' : ''; ?>>O+</option>
                                    <option value="O-" <?php echo $patient_profile['blood_type'] === 'O-' ? 'selected' : ''; ?>>O-</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="height" class="form-label">Height</label>
                                <input type="text" class="form-control" id="height" name="height" value="<?php echo htmlspecialchars($patient_profile['height']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="weight" class="form-label">Weight</label>
                                <input type="text" class="form-control" id="weight" name="weight" value="<?php echo htmlspecialchars($patient_profile['weight']); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="street" class="form-label">Street Address</label>
                            <input type="text" class="form-control" id="street" name="street" value="<?php echo htmlspecialchars($patient_profile['address']['street']); ?>">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($patient_profile['address']['city']); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state" value="<?php echo htmlspecialchars($patient_profile['address']['state']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="zip" class="form-label">Zip Code</label>
                                <input type="text" class="form-control" id="zip" name="zip" value="<?php echo htmlspecialchars($patient_profile['address']['zip']); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="<?php echo htmlspecialchars($patient_profile['address']['country']); ?>">
                        </div>

                        <h5 class="mt-4 mb-3">Emergency Contact</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ec_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="ec_name" name="ec_name" value="<?php echo htmlspecialchars($patient_profile['emergency_contact']['name']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="ec_relationship" class="form-label">Relationship</label>
                                <input type="text" class="form-control" id="ec_relationship" name="ec_relationship" value="<?php echo htmlspecialchars($patient_profile['emergency_contact']['relationship']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ec_phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="ec_phone" name="ec_phone" value="<?php echo htmlspecialchars($patient_profile['emergency_contact']['phone']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="ec_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="ec_email" name="ec_email" value="<?php echo htmlspecialchars($patient_profile['emergency_contact']['email']); ?>">
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Medical Information</h5>
                        
                        <div class="mb-3">
                            <label for="allergies" class="form-label">Allergies</label>
                            <textarea class="form-control" id="allergies" name="allergies" rows="2"><?php echo htmlspecialchars(implode(", ", $patient_profile['medical_info']['allergies'])); ?></textarea>
                            <div class="form-text">Separate multiple allergies with commas</div>
                        </div>

                        <div class="mb-3">
                            <label for="chronic_conditions" class="form-label">Chronic Conditions</label>
                            <textarea class="form-control" id="chronic_conditions" name="chronic_conditions" rows="2"><?php echo htmlspecialchars(implode(", ", $patient_profile['medical_info']['chronic_conditions'])); ?></textarea>
                            <div class="form-text">Separate multiple conditions with commas</div>
                        </div>

                        <div class="mb-3">
                            <label for="current_medications" class="form-label">Current Medications</label>
                            <textarea class="form-control" id="current_medications" name="current_medications" rows="2"><?php echo htmlspecialchars(implode(", ", $patient_profile['medical_info']['current_medications'])); ?></textarea>
                            <div class="form-text">Separate multiple medications with commas</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="update_profile">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="profile.php" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <div class="form-text">Password must be at least 8 characters long and include a mix of letters, numbers, and special characters</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="change_password">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile Image Upload Modal -->
    <div class="modal fade" id="changeProfileImageModal" tabindex="-1" aria-labelledby="changeProfileImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeProfileImageModalLabel">Change Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="profile.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <?php if (!empty($image_upload_error)): ?>
                            <div class="alert alert-danger">
                                <?php echo $image_upload_error; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($image_upload_success): ?>
                            <div class="alert alert-success">
                                Profile image updated successfully!
                            </div>
                        <?php endif; ?>
                        
                        <div class="text-center mb-4">
                            <img src="<?php echo $profile_image; ?>" alt="Current Profile Image" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        
                        <div class="mb-3">
                            <label for="profile_image" class="form-label">Select New Image</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" required>
                            <div class="form-text">Recommended size: 300x300 pixels. Maximum file size: 2MB.</div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirm_change" required>
                                <label class="form-check-label" for="confirm_change">
                                    I confirm this is my profile picture
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="update_profile_image">Upload Image</button>
                    </div>
                </form>
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

        // Password validation
        document.getElementById('changePasswordModal').addEventListener('submit', function(e) {
            var newPassword = document.getElementById('new_password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (newPassword.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
            
            return true;
        });
        
        // Preview image before upload
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.querySelector('#changeProfileImageModal img').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>