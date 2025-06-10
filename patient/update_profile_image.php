<?php
session_start();
require_once '../includes/config.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $userId = $_SESSION['user_id'];
    $file = $_FILES['profileImage'];
    
    // Validate file
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($file['type'], $allowedTypes)) {
        $_SESSION['error'] = "Only JPG, JPEG & PNG files are allowed.";
        header('Location: profile.php');
        exit;
    }

    // Create upload directory if it doesn't exist
    $uploadDir = '../uploads/profile_images/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate unique filename
    $filename = 'profile_' . $userId . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetPath = $uploadDir . $filename;

    // Upload file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Update database with new image path
        $relativePath = 'uploads/profile_images/' . $filename;
        $stmt = $conn->prepare("UPDATE patients SET profile_image = ? WHERE user_id = ?");
        $stmt->bind_param("si", $relativePath, $userId);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile picture updated successfully!";
        } else {
            $_SESSION['error'] = "Error updating profile picture in database.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error uploading file.";
    }
} else {
    $_SESSION['error'] = "No file uploaded.";
}

header('Location: profile.php');
exit;
?>
