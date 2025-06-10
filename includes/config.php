<?php
/**
 * Database Configuration
 * HealthConnect - Premium Online Health System Platform
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'healthconnect');
define('DB_USER', 'root');
define('DB_PASS', '');

// Establish database connection
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Display error message in development environment
    // In production, you should log the error and display a user-friendly message
    die("Connection failed: " . $e->getMessage());
}

/**
 * Helper functions
 */

// Function to sanitize user input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to generate a random token
function generate_token($length = 32) {
    return bin2hex(random_bytes($length));
}

// Function to redirect with a message
function redirect($location, $message = '', $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: $location");
    exit;
}

// Function to display a message
function display_message() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'success';
        
        echo '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">';
        echo $message;
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        
        // Clear the message after displaying it
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check user type
function is_user_type($type) {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === $type;
}

// Function to require login
function require_login() {
    if (!is_logged_in()) {
        redirect('login.php', 'Please log in to access this page.', 'warning');
    }
}

// Function to require specific user type
function require_user_type($type) {
    require_login();
    
    if (!is_user_type($type)) {
        redirect('index.php', 'You do not have permission to access this page.', 'danger');
    }
}

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 