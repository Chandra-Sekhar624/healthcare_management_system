<?php
// This file contains the HTML for the appointment action buttons with onclick handlers
// It should be included in the schedule.php file where the buttons are displayed

// Function to generate the HTML for the appointment action buttons
function generateAppointmentButtons($appointment) {
    $html = '<div class="appointment-actions mt-2 text-end">';
    $html .= '<button class="btn btn-sm btn-outline-primary me-1" onclick="showAppointmentDetails(' . $appointment['id'] . ')">Details</button>';
    
    if ($appointment['status'] === 'pending') {
        $html .= '<button class="btn btn-sm btn-success me-1" onclick="confirmAppointment(' . $appointment['id'] . ')">Confirm</button>';
        $html .= '<button class="btn btn-sm btn-danger" onclick="declineAppointment(' . $appointment['id'] . ')">Decline</button>';
    }
    
    $html .= '</div>';
    
    return $html;
}
?>
