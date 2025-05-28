<?php
// Start session
session_start();

// Include database connection
include 'includes/config.php';

// Initialize variables
$email = $password = '';
$errors = [];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }
    
    // If no errors, process login
    if (empty($errors)) {
        try {
            // Check if user exists in database
            $sql = "SELECT id, first_name, last_name, email, password, user_type FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            // If user found
            if ($user = $stmt->fetch()) {
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Check if doctor is approved (if user is a doctor)
                    if ($user['user_type'] === 'doctor') {
                        $doctor_sql = "SELECT approval_status FROM doctors WHERE user_id = :user_id";
                        $doctor_stmt = $conn->prepare($doctor_sql);
                        $doctor_stmt->bindParam(':user_id', $user['id']);
                        $doctor_stmt->execute();
                        $doctor = $doctor_stmt->fetch();
                        
                        // If doctor is not approved
                        if ($doctor && $doctor['approval_status'] !== 'approved') {
                            $errors['login'] = 'Your doctor account is pending approval. Please wait for admin approval.';
                            return;
                        }
                    }
                    
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_type'] = $user['user_type'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['email'] = $user['email'];
                    
                    // Redirect to appropriate dashboard
                    if ($user['user_type'] === 'doctor') {
                        header('Location: doctor/index.php');
                    } elseif ($user['user_type'] === 'patient') {
                        header('Location: patient/index.php');
                    } elseif ($user['user_type'] === 'admin') {
                        header('Location: admin/index.php');
                    }
                    exit;
                } else {
                    $errors['login'] = 'Invalid email or password';
                }
            } else {
                $errors['login'] = 'Invalid email or password';
            }
        } catch (PDOException $e) {
            $errors['login'] = 'Login error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | HealthConnect</title>
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
                <span class="fw-bold">HealthConnect</span>
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
                        <a class="btn btn-primary" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="login-section py-5">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-md-8 col-lg-6">
                    <div class="card login-card shadow-lg border-0">
                        <div class="card-body p-4 p-lg-5">
                            <div class="text-center mb-4">
                                <h2 class="fw-bold">Welcome Back</h2>
                                <p class="text-muted">Sign in to your HealthConnect account</p>
                            </div>
                            
                            <?php if (isset($errors['login'])): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $errors['login']; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="fas fa-envelope text-primary"></i></span>
                                        <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter your email">
                                        <?php if (isset($errors['email'])): ?>
                                            <div class="invalid-feedback">
                                                <?php echo $errors['email']; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="fas fa-lock text-primary"></i></span>
                                        <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Enter your password">
                                        <?php if (isset($errors['password'])): ?>
                                            <div class="invalid-feedback">
                                                <?php echo $errors['password']; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <a href="forgot-password.php" class="text-primary">Forgot Password?</a>
                                </div>
                                
                                <div class="mb-4">
                                    <button type="submit" class="btn btn-primary w-100 py-2">Sign In</button>
                                </div>
                                
                                <div class="text-center">
                                    <p class="mb-0">Don't have an account? <a href="register.php" class="text-primary fw-bold">Sign Up</a></p>
                                </div>
                            </form>
                            
                            <div class="login-separator my-4">
                                <span>Or continue with</span>
                            </div>
                            
                            <div class="d-flex justify-content-center gap-3">
                                <a href="#" class="btn btn-outline-secondary">
                                    <i class="fab fa-google me-2"></i> Google
                                </a>
                                <a href="#" class="btn btn-outline-secondary">
                                    <i class="fab fa-facebook-f me-2"></i> Facebook
                                </a>
                                <a href="#" class="btn btn-outline-secondary">
                                    <i class="fab fa-apple me-2"></i> Apple
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="small text-muted">
                            Demo Accounts:<br>
                            Doctor: doctor@example.com / password<br>
                            Patient: patient@example.com / password<br>
                            Admin: admin@example.com / password
                        </p>
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
                    <p>&copy; 2023 HealthConnect. All rights reserved.</p>
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
</body>
</html> 