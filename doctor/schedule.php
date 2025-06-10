<?php
// Start session
session_start();

// Include database configuration
// include '../includes/config.php';

// Include the appointment buttons file
include 'appointment-buttons.php';

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get doctor information
$doctor_id = $_SESSION['user_id'];
$doctor_name = $_SESSION['user_name'];

// Placeholder schedule data (in a real system, you would fetch this from the database)
$schedule_slots = [
    [
        'id' => 1,
        'day' => 'Monday',
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
        'status' => 'active'
    ],
    [
        'id' => 2,
        'day' => 'Monday',
        'start_time' => '13:00:00',
        'end_time' => '17:00:00',
        'status' => 'active'
    ],
    [
        'id' => 3,
        'day' => 'Tuesday',
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
        'status' => 'active'
    ],
    [
        'id' => 4,
        'day' => 'Tuesday',
        'start_time' => '13:00:00',
        'end_time' => '17:00:00',
        'status' => 'active'
    ],
    [
        'id' => 5,
        'day' => 'Wednesday',
        'start_time' => '10:00:00',
        'end_time' => '14:00:00',
        'status' => 'active'
    ],
    [
        'id' => 6,
        'day' => 'Thursday',
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
        'status' => 'active'
    ],
    [
        'id' => 7,
        'day' => 'Thursday',
        'start_time' => '13:00:00',
        'end_time' => '17:00:00',
        'status' => 'active'
    ],
    [
        'id' => 8,
        'day' => 'Friday',
        'start_time' => '09:00:00',
        'end_time' => '13:00:00',
        'status' => 'active'
    ]
];

// Sample upcoming appointments
$upcoming_appointments = [
    [
        'id' => 101,
        'patient_name' => 'Jane Smith',
        'date' => '2023-06-15',
        'start_time' => '09:15:00',
        'end_time' => '09:45:00',
        'type' => 'Follow-up',
        'status' => 'confirmed'
    ],
    [
        'id' => 102,
        'patient_name' => 'Robert Johnson',
        'date' => '2023-06-15',
        'start_time' => '10:00:00',
        'end_time' => '10:30:00',
        'type' => 'Consultation',
        'status' => 'confirmed'
    ],
    [
        'id' => 103,
        'patient_name' => 'Emily Williams',
        'date' => '2023-06-15',
        'start_time' => '11:15:00',
        'end_time' => '11:45:00',
        'type' => 'Prenatal Check-up',
        'status' => 'confirmed'
    ],
    [
        'id' => 104,
        'patient_name' => 'Michael Brown',
        'date' => '2023-06-16',
        'start_time' => '09:30:00',
        'end_time' => '10:00:00',
        'type' => 'Routine Check-up',
        'status' => 'confirmed'
    ],
    [
        'id' => 105,
        'patient_name' => 'Sarah Thompson',
        'date' => '2023-06-16',
        'start_time' => '10:45:00',
        'end_time' => '11:15:00',
        'type' => 'Follow-up',
        'status' => 'pending'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule | Doctor Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-body">
    <!-- Sidebar and Navigation -->
    <?php include 'sidebar_nav.php'; ?>
        <!-- Main Content -->  
            <!-- Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">My Schedule</h1>
                    <div>
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addTimeSlotModal">
                            <i class="fas fa-plus fa-sm me-2"></i> Add Time Slot
                        </a>
                        <a href="#" class="btn btn-info btn-sm shadow-sm ms-2" onclick="window.print();">
                            <i class="fas fa-print fa-sm me-2"></i> Print
                        </a>
                    </div>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <!-- Schedule Statistics Cards -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Weekly Hours</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">32</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Appointments Today</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">6</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Next Appointment</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">15 min</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Pending Requests</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">4</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule View -->
                <div class="row">
                    <!-- Weekly Schedule -->
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 fw-bold text-primary">Weekly Schedule</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">View Options:</div>
                                        <a class="dropdown-item" href="#" id="viewDayBtn">Day View</a>
                                        <a class="dropdown-item" href="#" id="viewWeekBtn">Week View</a>
                                        <a class="dropdown-item" href="#" id="viewMonthBtn">Month View</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Appointments -->
                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">Upcoming Appointments</h6>
                            </div>
                            <div class="card-body">
                                <div class="upcoming-appointments">
                                    <?php foreach ($upcoming_appointments as $appointment): ?>
                                    <div class="appointment-item p-3 mb-2 rounded border 
                                        <?php 
                                            if ($appointment['status'] === 'confirmed') {
                                                echo 'border-success';
                                            } else if ($appointment['status'] === 'rejected') {
                                                echo 'border-danger';
                                            } else {
                                                echo 'border-warning';
                                            }
                                        ?>">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($appointment['patient_name']); ?></h6>
                                            <span class="badge <?php 
                                                if ($appointment['status'] === 'confirmed') {
                                                    echo 'bg-success';
                                                } else if ($appointment['status'] === 'rejected') {
                                                    echo 'bg-danger';
                                                } else {
                                                    echo 'bg-warning';
                                                }
                                            ?>">
                                                <?php echo ucfirst(htmlspecialchars($appointment['status'])); ?>
                                            </span>
                                        </div>
                                        <div class="appointment-details small">
                                            <p class="mb-1">
                                                <i class="fas fa-calendar-day me-2 text-primary"></i>
                                                <?php echo date('M d, Y', strtotime($appointment['date'])); ?>
                                            </p>
                                            <p class="mb-1">
                                                <i class="fas fa-clock me-2 text-primary"></i>
                                                <?php 
                                                    echo date('h:i A', strtotime($appointment['start_time'])) . ' - ' . 
                                                        date('h:i A', strtotime($appointment['end_time'])); 
                                                ?>
                                            </p>
                                            <p class="mb-1">
                                                <i class="fas fa-tag me-2 text-primary"></i>
                                                <?php echo htmlspecialchars($appointment['type']); ?>
                                            </p>
                                        </div>
                                        <div class="appointment-actions mt-2 text-end">
                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="showAppointmentDetails(<?php echo $appointment['id']; ?>)">Details</button>
                                            <?php if ($appointment['status'] === 'pending'): ?>
                                                <button class="btn btn-sm btn-success me-1" onclick="confirmAppointment(<?php echo $appointment['id']; ?>)">Confirm</button>
                                                <button class="btn btn-sm btn-danger" onclick="declineAppointment(<?php echo $appointment['id']; ?>)">Decline</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Working Hours -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">My Working Hours</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Morning Session</th>
                                        <th>Afternoon Session</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    $schedule_by_day = [];
                                    
                                    // Group schedule slots by day
                                    foreach ($schedule_slots as $slot) {
                                        $day = $slot['day'];
                                        if (!isset($schedule_by_day[$day])) {
                                            $schedule_by_day[$day] = [];
                                        }
                                        $schedule_by_day[$day][] = $slot;
                                    }
                                    
                                    foreach ($days as $day) {
                                        echo '<tr>';
                                        echo '<td>' . $day . '</td>';
                                        
                                        // Morning session (before 12:00)
                                        echo '<td>';
                                        if (isset($schedule_by_day[$day])) {
                                            foreach ($schedule_by_day[$day] as $slot) {
                                                if (strtotime($slot['start_time']) < strtotime('12:00:00')) {
                                                    echo date('h:i A', strtotime($slot['start_time'])) . ' - ' . 
                                                         date('h:i A', strtotime($slot['end_time']));
                                                }
                                            }
                                        } else {
                                            echo '<span class="text-muted">Off</span>';
                                        }
                                        echo '</td>';
                                        
                                        // Afternoon session (after 12:00)
                                        echo '<td>';
                                        if (isset($schedule_by_day[$day])) {
                                            foreach ($schedule_by_day[$day] as $slot) {
                                                if (strtotime($slot['start_time']) >= strtotime('12:00:00')) {
                                                    echo date('h:i A', strtotime($slot['start_time'])) . ' - ' . 
                                                         date('h:i A', strtotime($slot['end_time']));
                                                }
                                            }
                                        } else {
                                            echo '<span class="text-muted">Off</span>';
                                        }
                                        echo '</td>';
                                        
                                        echo '<td>';
                                        echo '<button class="btn btn-sm btn-info me-1"><i class="fas fa-edit"></i></button>';
                                        echo '<button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
                                        echo '</td>';
                                        
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Time Slot Modal -->
    <div class="modal fade" id="addTimeSlotModal" tabindex="-1" aria-labelledby="addTimeSlotModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTimeSlotModalLabel">Add New Time Slot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="day" class="form-label">Day of Week</label>
                            <select class="form-select" id="day" required>
                                <option value="" selected disabled>Select Day</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="startTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="startTime" required>
                            </div>
                            <div class="col-md-6">
                                <label for="endTime" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="endTime" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="recurring">
                                <label class="form-check-label" for="recurring">
                                    Recurring (every week)
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Time Slot</button>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.1.3 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/main.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize FullCalendar
            var calendarEl = document.getElementById('calendar');
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                slotMinTime: '08:00:00',
                slotMaxTime: '20:00:00',
                allDaySlot: false,
                height: 'auto',
                businessHours: [
                    {
                        daysOfWeek: [1, 2, 3, 4, 5], // Monday - Friday
                        startTime: '09:00',
                        endTime: '17:00'
                    }
                ],
                events: [
                    <?php foreach ($upcoming_appointments as $appointment): ?>
                    {
                        id: '<?php echo $appointment['id']; ?>',
                        title: '<?php echo htmlspecialchars($appointment['patient_name']); ?> - <?php echo htmlspecialchars($appointment['type']); ?>',
                        start: '<?php echo $appointment['date'] . 'T' . $appointment['start_time']; ?>',
                        end: '<?php echo $appointment['date'] . 'T' . $appointment['end_time']; ?>',
                        backgroundColor: '<?php echo $appointment['status'] === 'confirmed' ? '#28a745' : '#ffc107'; ?>',
                        borderColor: '<?php echo $appointment['status'] === 'confirmed' ? '#28a745' : '#ffc107'; ?>'
                    },
                    <?php endforeach; ?>
                ],
                eventClick: function(info) {
                    alert('Event: ' + info.event.title + '\nStart: ' + info.event.start.toLocaleString());
                }
            });
            
            calendar.render();
            
            // View buttons functionality
            $('#viewDayBtn').click(function(e) {
                e.preventDefault();
                calendar.changeView('timeGridDay');
            });
            
            $('#viewWeekBtn').click(function(e) {
                e.preventDefault();
                calendar.changeView('timeGridWeek');
            });
            
            $('#viewMonthBtn').click(function(e) {
                e.preventDefault();
                calendar.changeView('dayGridMonth');
            });
        });

        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
        });
        
        // Function to show appointment details
        function showAppointmentDetails(appointmentId) {
            // Find the appointment data
            let appointmentData = null;
            const appointmentElement = document.querySelector(`.appointment-item:has(button[onclick*="showAppointmentDetails(${appointmentId})"])`);
            
            if (appointmentElement) {
                const patientName = appointmentElement.querySelector('h6').textContent;
                const status = appointmentElement.querySelector('.badge').textContent;
                const dateElement = appointmentElement.querySelector('.fas.fa-calendar-day').parentNode;
                const timeElement = appointmentElement.querySelector('.fas.fa-clock').parentNode;
                const typeElement = appointmentElement.querySelector('.fas.fa-tag').parentNode;
                
                const date = dateElement ? dateElement.textContent.trim() : '';
                const time = timeElement ? timeElement.textContent.trim() : '';
                const type = typeElement ? typeElement.textContent.trim() : '';
                
                // Create modal HTML
                const modalHtml = `
                <div class="modal fade" id="appointmentDetailsModal" tabindex="-1" aria-labelledby="appointmentDetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="appointmentDetailsModalLabel">Appointment Details</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="appointment-details-container">
                                    <div class="row mb-3">
                                        <div class="col-4 fw-bold">Patient:</div>
                                        <div class="col-8">${patientName}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4 fw-bold">Date:</div>
                                        <div class="col-8">${date}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4 fw-bold">Time:</div>
                                        <div class="col-8">${time}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4 fw-bold">Type:</div>
                                        <div class="col-8">${type}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4 fw-bold">Status:</div>
                                        <div class="col-8">
                                            <span class="badge ${status.toLowerCase() === 'confirmed' ? 'bg-success' : (status.toLowerCase() === 'rejected' ? 'bg-danger' : 'bg-warning')}">
                                                ${status}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                
                // Remove any existing modal
                const existingModal = document.getElementById('appointmentDetailsModal');
                if (existingModal) {
                    existingModal.remove();
                }
                
                // Add the modal to the document
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('appointmentDetailsModal'));
                modal.show();
            } else {
                alert('Appointment details not found!');
            }
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
                    // Update the badge
                    const badge = appointmentElement.querySelector('.badge');
                    if (badge) {
                        badge.classList.remove('bg-warning');
                        badge.classList.add('bg-danger');
                        badge.textContent = 'Rejected';
                    }
                    
                    // Update the border
                    appointmentElement.classList.remove('border-warning');
                    appointmentElement.classList.add('border-danger');
                    
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
                        calendarEvent.setProp('backgroundColor', '#dc3545');
                        calendarEvent.setProp('borderColor', '#dc3545');
                    }
                    
                    alert('Appointment declined successfully!');
                }
            }
        }
    </script>
</body>
</html>