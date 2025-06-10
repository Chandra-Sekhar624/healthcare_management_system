<?php
/**
 * Patient-specific functions for the DOB system
 */

/**
 * Save patient's medical information with validation
 * 
 * @param PDO $conn Database connection
 * @param int $patient_id Patient ID
 * @param array $medical_data Array containing medical information
 * @return array ['success' => bool, 'errors' => array] Result of operation and any errors
 */
function saveMedicalInformation($conn, $patient_id, $medical_data) {
    // Initialize result array
    $result = [
        'success' => false,
        'errors' => []
    ];

    // Validate input data
    if (empty($medical_data['blood_group'])) {
        $result['errors'][] = 'Blood group is required';
    }

    if (empty($medical_data['emergency_contact_name'])) {
        $result['errors'][] = 'Emergency contact name is required';
    }

    if (empty($medical_data['emergency_contact_phone'])) {
        $result['errors'][] = 'Emergency contact phone is required';
    }

    // If there are validation errors, return them
    if (!empty($result['errors'])) {
        return $result;
    }

    try {
        // Prepare the update query
        $sql = "UPDATE patients SET 
            blood_group = :blood_group,
            allergies = :allergies,
            medical_conditions = :medical_conditions,
            emergency_contact_name = :emergency_contact_name,
            emergency_contact_phone = :emergency_contact_phone,
            updated_at = NOW()
            WHERE user_id = :user_id";

        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':blood_group', $medical_data['blood_group']);
        $stmt->bindParam(':allergies', $medical_data['allergies']);
        $stmt->bindParam(':medical_conditions', $medical_data['medical_conditions']);
        $stmt->bindParam(':emergency_contact_name', $medical_data['emergency_contact_name']);
        $stmt->bindParam(':emergency_contact_phone', $medical_data['emergency_contact_phone']);
        $stmt->bindParam(':user_id', $patient_id);
        
        if ($stmt->execute()) {
            $result['success'] = true;
        } else {
            $result['errors'][] = 'Failed to update medical information';
        }

    } catch (PDOException $e) {
        error_log("Error saving medical information: " . $e->getMessage());
        $result['errors'][] = 'Database error: ' . $e->getMessage();
    }

    return $result;
}

/**
 * Get patient's profile data
 * 
 * @param PDO $conn Database connection
 * @param int $patient_id Patient ID
 * @return array Patient profile data
 */
function getPatientProfileData($conn, $patient_id) {
    try {
        // Fetch user data
        $user_sql = "SELECT first_name, last_name, email, phone, profile_image FROM users WHERE id = :user_id";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bindParam(':user_id', $patient_id);
        $user_stmt->execute();
        $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);
        
        // Fetch patient data
        $patient_sql = "SELECT * FROM patients WHERE user_id = :user_id";
        $patient_stmt = $conn->prepare($patient_sql);
        $patient_stmt->bindParam(':user_id', $patient_id);
        $patient_stmt->execute();
        $patient_data = $patient_stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$patient_data) {
            // If no patient record exists, return default values
            return [
                'id' => $patient_id,
                'name' => trim(($user_data['first_name'] ?? '') . ' ' . ($user_data['last_name'] ?? '')),
                'email' => $user_data['email'] ?? '',
                'phone' => $user_data['phone'] ?? '',
                'dob' => '',
                'gender' => '',
                'blood_type' => '',
                'address' => [
                    'full' => '',
                    'street' => '',
                    'city' => '',
                    'state' => '',
                    'zip' => '',
                    'country' => ''
                ],
                'emergency_contact' => [
                    'name' => '',
                    'phone' => ''
                ],
                'medical_info' => [
                    'allergies' => [],
                    'chronic_conditions' => []
                ]
            ];
        }
        
        // Parse address if it exists
        $address_parts = [];
        if (!empty($patient_data['address'])) {
            $address_parts = array_map('trim', explode(',', $patient_data['address']));
        }
        
        // Build the profile array with data from database
        return [
            'id' => $patient_id,
            'name' => trim(($user_data['first_name'] ?? '') . ' ' . ($user_data['last_name'] ?? '')),
            'email' => $user_data['email'] ?? '',
            'phone' => $user_data['phone'] ?? '',
            'dob' => $patient_data['date_of_birth'] ?? '',
            'gender' => ucfirst($patient_data['gender'] ?? ''),
            'blood_type' => $patient_data['blood_group'] ?? '',
            'address' => [
                'full' => $patient_data['address'] ?? '',
                'street' => $address_parts[0] ?? '',
                'city' => $address_parts[1] ?? '',
                'state' => $address_parts[2] ?? '',
                'zip' => $address_parts[3] ?? '',
                'country' => $address_parts[4] ?? ''
            ],
            'emergency_contact' => [
                'name' => $patient_data['emergency_contact_name'] ?? '',
                'phone' => $patient_data['emergency_contact_phone'] ?? ''
            ],
            'medical_info' => [
                'allergies' => !empty($patient_data['allergies']) ? explode(', ', $patient_data['allergies']) : [],
                'chronic_conditions' => !empty($patient_data['medical_conditions']) ? explode(', ', $patient_data['medical_conditions']) : []
            ]
        ];
    } catch (PDOException $e) {
        error_log("Error fetching patient profile: " . $e->getMessage());
        return [];
    }
}

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
