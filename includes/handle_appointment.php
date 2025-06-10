<?php
session_start();
header('Content-Type: application/json');

// Include database configuration
include 'config.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    try {
        // Get appointment data
        $appointment_data = json_decode(file_get_contents('php://input'), true);
        
        // Validate data
        if (!isset($appointment_data['action'])) {
            throw new Exception("Action not specified");
        }

        // Connect to database
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        switch ($appointment_data['action']) {
            case 'confirm':
                $stmt = $conn->prepare("UPDATE appointments SET status = 'confirmed' WHERE id = ?");
                $stmt->bind_param("i", $appointment_data['appointmentId']);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Appointment confirmed successfully!";
                } else {
                    throw new Exception("Error confirming appointment");
                }
                break;

            case 'cancel':
                $stmt = $conn->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ?");
                $stmt->bind_param("i", $appointment_data['appointmentId']);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Appointment cancelled successfully!";
                } else {
                    throw new Exception("Error cancelling appointment");
                }
                break;

            case 'create':
                $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, 
                    type, reason, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
                
                $stmt->bind_param("iisssss", 
                    $appointment_data['patientId'],
                    $appointment_data['doctorId'],
                    $appointment_data['date'],
                    $appointment_data['time'],
                    $appointment_data['type'],
                    $appointment_data['reason'],
                    $appointment_data['notes']
                );

                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Appointment scheduled successfully!";
                    $response['appointmentId'] = $conn->insert_id;
                } else {
                    throw new Exception("Error scheduling appointment");
                }
                break;

            default:
                throw new Exception("Invalid action");
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    exit;
}
?>
