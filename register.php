<?php
// Start session
session_start();

// Include database connection
include 'includes/config.php';

// Initialize variables
$first_name = $last_name = $email = $password = $confirm_password = '';
$phone = $address = $date_of_birth = $gender = '';
$specialty = $license_number = $experience = $bio = '';
$registration_type = isset($_GET['type']) ? $_GET['type'] : 'patient';
$errors = [];
$success_message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $registration_type = $_POST['registration_type'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = trim($_POST['phone']);
    
    // Validate common inputs
    if (empty($first_name)) {
        $errors['first_name'] = 'First name is required';
    }
    
    if (empty($last_name)) {
        $errors['last_name'] = 'Last name is required';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long';
    }
    
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }
    
    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required';
    }
    
    // Type-specific validation
    if ($registration_type === 'doctor') {
        $specialty = trim($_POST['specialty']);
        $license_number = trim($_POST['license_number']);
        $experience = trim($_POST['experience']);
        $bio = trim($_POST['bio']);
        
        if (empty($specialty)) {
            $errors['specialty'] = 'Specialty is required';
        }
        
        if (empty($license_number)) {
            $errors['license_number'] = 'License number is required';
        }
        
        if (empty($experience)) {
            $errors['experience'] = 'Years of experience is required';
        } elseif (!is_numeric($experience)) {
            $errors['experience'] = 'Years of experience must be a number';
        }
    } else {
        $address = trim($_POST['address']);
        $date_of_birth = trim($_POST['date_of_birth']);
        $gender = $_POST['gender'];
        
        if (empty($address)) {
            $errors['address'] = 'Address is required';
        }
        
        if (empty($date_of_birth)) {
            $errors['date_of_birth'] = 'Date of birth is required';
        }
        
        if (empty($gender)) {
            $errors['gender'] = 'Gender is required';
        }
    }
    
    // If no errors, process registration
    if (empty($errors)) {
        try {
            // Begin transaction
            $conn->beginTransaction();
            
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert into users table
            $user_sql = "INSERT INTO users (first_name, last_name, email, password, phone, user_type) 
                        VALUES (:first_name, :last_name, :email, :password, :phone, :user_type)";
            $user_stmt = $conn->prepare($user_sql);
            $user_stmt->bindParam(':first_name', $first_name);
            $user_stmt->bindParam(':last_name', $last_name);
            $user_stmt->bindParam(':email', $email);
            $user_stmt->bindParam(':password', $hashed_password);
            $user_stmt->bindParam(':phone', $phone);
            $user_stmt->bindParam(':user_type', $registration_type);
            $user_stmt->execute();
            
            // Get the inserted user ID
            $user_id = $conn->lastInsertId();
            
            if ($registration_type === 'doctor') {
                // Insert into doctors table with approval_status as 'pending'
                $doctor_sql = "INSERT INTO doctors (user_id, specialty, license_number, experience, bio, approval_status) 
                              VALUES (:user_id, :specialty, :license_number, :experience, :bio, 'pending')";
                $doctor_stmt = $conn->prepare($doctor_sql);
                $doctor_stmt->bindParam(':user_id', $user_id);
                $doctor_stmt->bindParam(':specialty', $specialty);
                $doctor_stmt->bindParam(':license_number', $license_number);
                $doctor_stmt->bindParam(':experience', $experience);
                $doctor_stmt->bindParam(':bio', $bio);
                $doctor_stmt->execute();
                
                // Create notification for admin
                $notification_sql = "INSERT INTO notifications (user_id, title, message, type) 
                                    SELECT id, 'New Doctor Registration', CONCAT(:first_name, ' ', :last_name, ' has registered as a doctor and is awaiting approval'), 'system' 
                                    FROM users WHERE user_type = 'admin'";
                $notification_stmt = $conn->prepare($notification_sql);
                $notification_stmt->bindParam(':first_name', $first_name);
                $notification_stmt->bindParam(':last_name', $last_name);
                $notification_stmt->execute();
                
                // Commit transaction
                $conn->commit();
                
                // Set success message
                $success_message = "Your doctor registration has been submitted successfully. An administrator will review your application and you will be notified once approved.";
            } else {
                // Insert into patients table
                $patient_sql = "INSERT INTO patients (user_id, address, date_of_birth, gender) 
                              VALUES (:user_id, :address, :date_of_birth, :gender)";
                $patient_stmt = $conn->prepare($patient_sql);
                $patient_stmt->bindParam(':user_id', $user_id);
                $patient_stmt->bindParam(':address', $address);
                $patient_stmt->bindParam(':date_of_birth', $date_of_birth);
                $patient_stmt->bindParam(':gender', $gender);
                $patient_stmt->execute();
                
                // Commit transaction
                $conn->commit();
                
                // Set session variables for immediate login
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_type'] = 'patient';
                $_SESSION['user_name'] = $first_name . ' ' . $last_name;
                $_SESSION['email'] = $email;
                
                // Redirect to patient dashboard
                header('Location: patient/index.php');
                exit;
            }
            
            // Clear form data after successful submission
            $first_name = $last_name = $email = $password = $confirm_password = '';
            $phone = $address = $date_of_birth = $gender = '';
            $specialty = $license_number = $experience = $bio = '';
            
        } catch (PDOException $e) {
            // Rollback transaction on error
            $conn->rollBack();
            $errors['database'] = "Registration failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | HealthTech</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-heartbeat text-primary me-2"></i>
                <span class="fw-bold">HealthTech</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-outline-primary" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Registration Section -->
    <section class="registration-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card registration-card shadow-lg border-0 my-5">
                        <div class="card-body p-4 p-lg-5">
                            <div class="text-center mb-4">
                                <h2 class="fw-bold">Create Your Account</h2>
                                <p class="text-muted">Join HealthTech as a patient or healthcare provider</p>
                                <?php if (!empty($success_message)): ?>
                                    <div class="alert alert-success">
                                        <?php echo $success_message; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($errors['database'])): ?>
                                    <div class="alert alert-danger">
                                        <?php echo $errors['database']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <ul class="nav nav-pills mb-4 justify-content-center" id="registrationTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?php echo $registration_type === 'patient' ? 'active' : ''; ?>" id="patient-tab" data-bs-toggle="pill" data-bs-target="#patient" type="button" role="tab" aria-controls="patient" aria-selected="<?php echo $registration_type === 'patient' ? 'true' : 'false'; ?>">
                                        <i class="fas fa-user me-2"></i> Patient
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?php echo $registration_type === 'doctor' ? 'active' : ''; ?>" id="doctor-tab" data-bs-toggle="pill" data-bs-target="#doctor" type="button" role="tab" aria-controls="doctor" aria-selected="<?php echo $registration_type === 'doctor' ? 'true' : 'false'; ?>">
                                        <i class="fas fa-user-md me-2"></i> Doctor
                                    </button>
                                </li>
                            </ul>
                            
                            <div class="tab-content" id="registrationTabContent">
                                <!-- Patient Registration Form -->
                                <div class="tab-pane fade <?php echo $registration_type === 'patient' ? 'show active' : ''; ?>" id="patient" role="tabpanel" aria-labelledby="patient-tab">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                                        <input type="hidden" name="registration_type" value="patient">
                                        
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="patient_first_name" class="form-label">First Name</label>
                                                <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" id="patient_first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                                                <?php if (isset($errors['first_name'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['first_name']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="patient_last_name" class="form-label">Last Name</label>
                                                <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" id="patient_last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                                                <?php if (isset($errors['last_name'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['last_name']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="patient_email" class="form-label">Email Address</label>
                                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="patient_email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                                <?php if (isset($errors['email'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['email']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="patient_phone" class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" id="patient_phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                                                <?php if (isset($errors['phone'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['phone']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="patient_dob" class="form-label">Date of Birth</label>
                                                <input type="date" class="form-control <?php echo isset($errors['date_of_birth']) ? 'is-invalid' : ''; ?>" id="patient_dob" name="date_of_birth" value="<?php echo htmlspecialchars($date_of_birth); ?>" required>
                                                <?php if (isset($errors['date_of_birth'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['date_of_birth']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Gender</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input <?php echo isset($errors['gender']) ? 'is-invalid' : ''; ?>" type="radio" name="gender" id="male" value="male" <?php echo $gender === 'male' ? 'checked' : ''; ?> required>
                                                        <label class="form-check-label" for="male">Male</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input <?php echo isset($errors['gender']) ? 'is-invalid' : ''; ?>" type="radio" name="gender" id="female" value="female" <?php echo $gender === 'female' ? 'checked' : ''; ?> required>
                                                        <label class="form-check-label" for="female">Female</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input <?php echo isset($errors['gender']) ? 'is-invalid' : ''; ?>" type="radio" name="gender" id="other" value="other" <?php echo $gender === 'other' ? 'checked' : ''; ?> required>
                                                        <label class="form-check-label" for="other">Other</label>
                                                    </div>
                                                    <?php if (isset($errors['gender'])): ?>
                                                        <div class="invalid-feedback d-block">
                                                            <?php echo $errors['gender']; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label for="patient_address" class="form-label">Address</label>
                                                <textarea class="form-control <?php echo isset($errors['address']) ? 'is-invalid' : ''; ?>" id="patient_address" name="address" rows="3" required><?php echo htmlspecialchars($address); ?></textarea>
                                                <?php if (isset($errors['address'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['address']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="patient_password" class="form-label">Password</label>
                                                <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" id="patient_password" name="password" required>
                                                <?php if (isset($errors['password'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['password']; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="form-text">Password must be at least 8 characters long.</div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="patient_confirm_password" class="form-label">Confirm Password</label>
                                                <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" id="patient_confirm_password" name="confirm_password" required>
                                                <?php if (isset($errors['confirm_password'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['confirm_password']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="patient_terms" required>
                                                    <label class="form-check-label" for="patient_terms">
                                                        I agree to the <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 mt-4">
                                                <button type="submit" class="btn btn-primary w-100 py-2">Create Patient Account</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Doctor Registration Form -->
                                <div class="tab-pane fade <?php echo $registration_type === 'doctor' ? 'show active' : ''; ?>" id="doctor" role="tabpanel" aria-labelledby="doctor-tab">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                                        <input type="hidden" name="registration_type" value="doctor">
                                        
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="doctor_first_name" class="form-label">First Name</label>
                                                <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" id="doctor_first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                                                <?php if (isset($errors['first_name'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['first_name']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="doctor_last_name" class="form-label">Last Name</label>
                                                <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" id="doctor_last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                                                <?php if (isset($errors['last_name'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['last_name']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="doctor_email" class="form-label">Email Address</label>
                                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="doctor_email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                                <?php if (isset($errors['email'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['email']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="doctor_phone" class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" id="doctor_phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                                                <?php if (isset($errors['phone'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['phone']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="doctor_specialty" class="form-label">Specialty</label>
                                                <select class="form-select <?php echo isset($errors['specialty']) ? 'is-invalid' : ''; ?>" id="doctor_specialty" name="specialty" required>
                                                    <option value="" selected disabled>Select your specialty</option>
                                                    <option value="Cardiology" <?php echo $specialty === 'Cardiology' ? 'selected' : ''; ?>>Cardiology</option>
                                                    <option value="Dermatology" <?php echo $specialty === 'Dermatology' ? 'selected' : ''; ?>>Dermatology</option>
                                                    <option value="Endocrinology" <?php echo $specialty === 'Endocrinology' ? 'selected' : ''; ?>>Endocrinology</option>
                                                    <option value="Gastroenterology" <?php echo $specialty === 'Gastroenterology' ? 'selected' : ''; ?>>Gastroenterology</option>
                                                    <option value="Neurology" <?php echo $specialty === 'Neurology' ? 'selected' : ''; ?>>Neurology</option>
                                                    <option value="Obstetrics and Gynecology" <?php echo $specialty === 'Obstetrics and Gynecology' ? 'selected' : ''; ?>>Obstetrics and Gynecology</option>
                                                    <option value="Oncology" <?php echo $specialty === 'Oncology' ? 'selected' : ''; ?>>Oncology</option>
                                                    <option value="Ophthalmology" <?php echo $specialty === 'Ophthalmology' ? 'selected' : ''; ?>>Ophthalmology</option>
                                                    <option value="Orthopedics" <?php echo $specialty === 'Orthopedics' ? 'selected' : ''; ?>>Orthopedics</option>
                                                    <option value="Pediatrics" <?php echo $specialty === 'Pediatrics' ? 'selected' : ''; ?>>Pediatrics</option>
                                                    <option value="Psychiatry" <?php echo $specialty === 'Psychiatry' ? 'selected' : ''; ?>>Psychiatry</option>
                                                    <option value="Urology" <?php echo $specialty === 'Urology' ? 'selected' : ''; ?>>Urology</option>
                                                </select>
                                                <?php if (isset($errors['specialty'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['specialty']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="doctor_experience" class="form-label">Years of Experience</label>
                                                <input type="number" class="form-control <?php echo isset($errors['experience']) ? 'is-invalid' : ''; ?>" id="doctor_experience" name="experience" value="<?php echo htmlspecialchars($experience); ?>" min="0" max="70" required>
                                                <?php if (isset($errors['experience'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['experience']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label for="doctor_license" class="form-label">Medical License Number</label>
                                                <input type="text" class="form-control <?php echo isset($errors['license_number']) ? 'is-invalid' : ''; ?>" id="doctor_license" name="license_number" value="<?php echo htmlspecialchars($license_number); ?>" required>
                                                <?php if (isset($errors['license_number'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['license_number']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label for="doctor_bio" class="form-label">Professional Bio</label>
                                                <textarea class="form-control <?php echo isset($errors['bio']) ? 'is-invalid' : ''; ?>" id="doctor_bio" name="bio" rows="3"><?php echo htmlspecialchars($bio); ?></textarea>
                                                <?php if (isset($errors['bio'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['bio']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="doctor_password" class="form-label">Password</label>
                                                <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" id="doctor_password" name="password" required>
                                                <?php if (isset($errors['password'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['password']; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="form-text">Password must be at least 8 characters long.</div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="doctor_confirm_password" class="form-label">Confirm Password</label>
                                                <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" id="doctor_confirm_password" name="confirm_password" required>
                                                <?php if (isset($errors['confirm_password'])): ?>
                                                    <div class="invalid-feedback">
                                                        <?php echo $errors['confirm_password']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="doctor_terms" required>
                                                    <label class="form-check-label" for="doctor_terms">
                                                        I agree to the <a href="#" class="text-primary">Terms of Service</a>, <a href="#" class="text-primary">Privacy Policy</a>, and <a href="#" class="text-primary">Medical Information Usage Policy</a>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 mt-4">
                                                <button type="submit" class="btn btn-primary w-100 py-2">Create Doctor Account</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <p>Already have an account? <a href="login.php" class="text-primary fw-bold">Sign In</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-4 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p>&copy; 2025 HealthTech . All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="social-links">
                        <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.1.3 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="js/main.js"></script>
    
    <script>
        // Set active tab based on URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            // Get registration type from URL
            const urlParams = new URLSearchParams(window.location.search);
            const type = urlParams.get('type');
            
            if (type === 'doctor') {
                const doctorTab = document.getElementById('doctor-tab');
                if (doctorTab) {
                    doctorTab.click();
                }
            } else if (type === 'patient') {
                const patientTab = document.getElementById('patient-tab');
                if (patientTab) {
                    patientTab.click();
                }
            }
        });
    </script>
</body>
</html> 