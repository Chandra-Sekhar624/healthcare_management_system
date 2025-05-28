<?php
/**
 * Patient-specific functions for the DOB system
 */

/**
 * Get the patient's profile image path
 * 
 * @param PDO $conn Database connection
 * @param int $patient_id Patient ID
 * @return string Path to the profile image
 */
function getPatientProfileImage($conn, $patient_id) {
    try {
        // Get user data
        $user_sql = "SELECT profile_image FROM users WHERE id = :user_id";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bindParam(':user_id', $patient_id);
        $user_stmt->execute();
        $user_data = $user_stmt->fetch();
        
        // Set profile image path
        return !empty($user_data['profile_image']) 
            ? '../uploads/profile_images/' . $user_data['profile_image'] 
            : '../img/patient-avatar.jpg';
        
    } catch (PDOException $e) {
        // Handle database error
        return '../img/patient-avatar.jpg';
    }
}
