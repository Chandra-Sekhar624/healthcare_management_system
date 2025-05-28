// Function to show appointment details
function showAppointmentDetails(appointmentId) {
    // In a real application, this would fetch details from the server
    alert('Showing details for appointment #' + appointmentId);
    // You could open a modal with detailed information here
}

// Function to confirm an appointment
function confirmAppointment(appointmentId) {
    // In a real application, this would send an AJAX request to the server
    if (confirm('Are you sure you want to confirm this appointment?')) {
        // Simulate a successful server response
        const appointmentElement = document.querySelector(`.appointment-item:has(button[onclick*="confirmAppointment(${appointmentId})"])`);
        if (appointmentElement) {
            // Update the badge
            const badge = appointmentElement.querySelector('.badge');
            if (badge) {
                badge.classList.remove('bg-warning');
                badge.classList.add('bg-success');
                badge.textContent = 'Confirmed';
            }
            
            // Update the border
            appointmentElement.classList.remove('border-warning');
            appointmentElement.classList.add('border-success');
            
            // Remove the confirm/decline buttons
            const actionsDiv = appointmentElement.querySelector('.appointment-actions');
            if (actionsDiv) {
                const confirmBtn = actionsDiv.querySelector('button.btn-success');
                const declineBtn = actionsDiv.querySelector('button.btn-danger');
                if (confirmBtn) confirmBtn.remove();
                if (declineBtn) declineBtn.remove();
            }
            
            // Update the calendar event
            const calendarEvent = calendar.getEventById(appointmentId.toString());
            if (calendarEvent) {
                calendarEvent.setProp('backgroundColor', '#28a745');
                calendarEvent.setProp('borderColor', '#28a745');
            }
            
            alert('Appointment confirmed successfully!');
        }
    }
}

// Function to decline an appointment
function declineAppointment(appointmentId) {
    // In a real application, this would send an AJAX request to the server
    if (confirm('Are you sure you want to decline this appointment?')) {
        // Simulate a successful server response
        const appointmentElement = document.querySelector(`.appointment-item:has(button[onclick*="declineAppointment(${appointmentId})"])`);
        if (appointmentElement) {
            // Remove the appointment from the list
            appointmentElement.remove();
            
            // Remove the event from the calendar
            const calendarEvent = calendar.getEventById(appointmentId.toString());
            if (calendarEvent) {
                calendarEvent.remove();
            }
            
            alert('Appointment declined successfully!');
        }
    }
}
