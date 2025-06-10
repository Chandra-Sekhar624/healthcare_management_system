<?php
// Start session
session_start();

// Include database configuration
// include '../includes/config.php';

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get doctor information
$doctor_id = $_SESSION['user_id'];
$doctor_name = $_SESSION['user_name'];

// Placeholder data (in a real system, you would fetch this from the database)
$appointments = [
    [
        'id' => 1,
        'patient_name' => 'Jane Smith',
        'date' => '2023-06-15',
        'time' => '10:00 AM',
        'status' => 'confirmed',
        'reason' => 'Follow-up Consultation',
        'notes' => 'Check blood pressure and review medication'
    ],
    [
        'id' => 2,
        'patient_name' => 'Robert Johnson',
        'date' => '2023-06-15',
        'time' => '11:30 AM',
        'status' => 'confirmed',
        'reason' => 'Annual Physical',
        'notes' => 'Complete health assessment and blood work'
    ],
    [
        'id' => 3,
        'patient_name' => 'Emily Williams',
        'date' => '2023-06-16',
        'time' => '09:15 AM',
        'status' => 'pending',
        'reason' => 'Initial Consultation',
        'notes' => 'New patient with chronic headaches'
    ],
    [
        'id' => 4,
        'patient_name' => 'Michael Brown',
        'date' => '2023-06-16',
        'time' => '02:00 PM',
        'status' => 'confirmed',
        'reason' => 'Prescription Renewal',
        'notes' => 'Patient needs renewal of hypertension medication'
    ],
    [
        'id' => 5,
        'patient_name' => 'Sarah Thompson',
        'date' => '2023-06-17',
        'time' => '03:30 PM',
        'status' => 'cancelled',
        'reason' => 'Skin Condition',
        'notes' => 'Patient called to reschedule'
    ],
    [
        'id' => 6,
        'patient_name' => 'David Wilson',
        'date' => '2023-06-18',
        'time' => '10:45 AM',
        'status' => 'confirmed',
        'reason' => 'Post-Surgery Follow-up',
        'notes' => 'Check wound healing and remove stitches if ready'
    ],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments | Doctor Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">

</head>
<body class="dashboard-body">
    <!-- Sidebar and Navigation -->
    <?php include 'sidebar_nav.php'; ?>

            <!-- Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Appointments</h1>
                    <div>
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
                            <i class="fas fa-plus fa-sm me-2"></i> New Appointment
                        </a>
                        <a href="#" class="btn btn-info btn-sm shadow-sm ms-2" onclick="window.print();">
                            <i class="fas fa-print fa-sm me-2"></i> Print
                        </a>
                    </div>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <!-- Appointment Summary Cards -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Today's Appointments</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">5</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Upcoming (This Week)</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">15</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Pending Approval</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">3</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-danger text-uppercase mb-1">Cancelled</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">2</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointment Filter -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">Filter Appointments</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Actions:</div>
                                <a class="dropdown-item" href="#">Reset Filters</a>
                                <a class="dropdown-item" href="#">Save Filters</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row mb-3">
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <label for="dateRange" class="form-label">Date Range</label>
                                    <select class="form-select" id="dateRange">
                                        <option value="today" selected>Today</option>
                                        <option value="tomorrow">Tomorrow</option>
                                        <option value="week">This Week</option>
                                        <option value="month">This Month</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status">
                                        <option value="all" selected>All</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="pending">Pending</option>
                                        <option value="cancelled">Cancelled</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <label for="patientSearch" class="form-label">Patient Name</label>
                                    <input type="text" class="form-control" id="patientSearch" placeholder="Search by name...">
                                </div>
                                <div class="col-md-3 align-self-end">
                                    <button type="button" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-2"></i> Apply Filters
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Appointments Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">All Appointments</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="appointmentsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['reason']); ?></td>
                                        <td>
                                            <?php 
                                            $statusClass = 'secondary';
                                            if ($appointment['status'] === 'confirmed') $statusClass = 'success';
                                            else if ($appointment['status'] === 'pending') $statusClass = 'warning';
                                            else if ($appointment['status'] === 'cancelled') $statusClass = 'danger';
                                            ?>
                                            <span class="badge bg-<?php echo $statusClass; ?> rounded-pill">
                                                <?php echo ucfirst(htmlspecialchars($appointment['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal<?php echo $appointment['id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editAppointmentModal<?php echo $appointment['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <?php if ($appointment['status'] !== 'cancelled'): ?>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelAppointmentModal<?php echo $appointment['id']; ?>">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <?php endif; ?>
                                                <?php if ($appointment['status'] === 'confirmed'): ?>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveAppointmentModal<?php echo $appointment['id']; ?>">
                                                    <i class="fas fa-check-double"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Appointment Modal -->
    <div class="modal fade" id="addAppointmentModal" tabindex="-1" aria-labelledby="addAppointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAppointmentModalLabel">Schedule New Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Success Alert (Hidden by default) -->
                    <div class="alert alert-success alert-dismissible fade show d-none" id="appointmentSuccess" role="alert">
                        <i class="fas fa-check-circle me-2"></i> Appointment booked successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    
                    <form id="newAppointmentForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="patient" class="form-label">Patient</label>
                                <select class="form-select" id="patient" required>
                                    <option value="" selected disabled>Select Patient</option>
                                    <option value="1">Jane Smith</option>
                                    <option value="2">Robert Johnson</option>
                                    <option value="3">Emily Williams</option>
                                    <option value="4">Michael Brown</option>
                                    <option value="5">Sarah Thompson</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="appointmentType" class="form-label">Appointment Type</label>
                                <select class="form-select" id="appointmentType" required>
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="consultation">Consultation</option>
                                    <option value="followup">Follow-up</option>
                                    <option value="physical">Physical Examination</option>
                                    <option value="test">Medical Test</option>
                                    <option value="emergency">Emergency</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="appointmentDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="appointmentDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="appointmentTime" class="form-label">Time</label>
                                <input type="time" class="form-control" id="appointmentTime" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentReason" class="form-label">Reason</label>
                            <input type="text" class="form-control" id="appointmentReason" placeholder="Reason for appointment">
                        </div>
                        <div class="mb-3">
                            <label for="appointmentNotes" class="form-label">Notes</label>
                            <textarea class="form-control" id="appointmentNotes" rows="3" placeholder="Additional notes or information"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentStatus" class="form-label">Status</label>
                            <select class="form-select" id="appointmentStatus">
                                <option value="confirmed" selected>Confirmed</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="scheduleAppointmentBtn">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="appointmentSpinner"></span>
                        Schedule Appointment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Appointment Modal -->
    <?php foreach ($appointments as $appointment): ?>
    <div class="modal fade" id="viewAppointmentModal<?php echo $appointment['id']; ?>" tabindex="-1" aria-labelledby="viewAppointmentModalLabel<?php echo $appointment['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewAppointmentModalLabel<?php echo $appointment['id']; ?>">Appointment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Patient Information</h6>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($appointment['patient_name']); ?></p>
                            <p><strong>ID:</strong> <?php echo htmlspecialchars($appointment['id']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Appointment Information</h6>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($appointment['date']); ?></p>
                            <p><strong>Time:</strong> <?php echo htmlspecialchars($appointment['time']); ?></p>
                            <p><strong>Status:</strong> 
                                <?php 
                                $statusClass = 'secondary';
                                if ($appointment['status'] === 'confirmed') $statusClass = 'success';
                                else if ($appointment['status'] === 'pending') $statusClass = 'warning';
                                else if ($appointment['status'] === 'cancelled') $statusClass = 'danger';
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?> rounded-pill">
                                    <?php echo ucfirst(htmlspecialchars($appointment['status'])); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Reason for Visit</h6>
                        <p><?php echo htmlspecialchars($appointment['reason']); ?></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Notes</h6>
                        <p><?php echo htmlspecialchars($appointment['notes']); ?></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAppointmentModal<?php echo $appointment['id']; ?>">
                        <i class="fas fa-edit me-2"></i> Edit
                    </button>
                    <?php if ($appointment['status'] !== 'cancelled'): ?>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelAppointmentModal<?php echo $appointment['id']; ?>">
                        <i class="fas fa-times me-2"></i> Cancel Appointment
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Edit Appointment Modal -->
    <?php foreach ($appointments as $appointment): ?>
    <div class="modal fade" id="editAppointmentModal<?php echo $appointment['id']; ?>" tabindex="-1" aria-labelledby="editAppointmentModalLabel<?php echo $appointment['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAppointmentModalLabel<?php echo $appointment['id']; ?>">Edit Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="patient<?php echo $appointment['id']; ?>" class="form-label">Patient</label>
                                <select class="form-select" id="patient<?php echo $appointment['id']; ?>" required>
                                    <option value="" disabled>Select Patient</option>
                                    <?php 
                                    $patients = ['Jane Smith', 'Robert Johnson', 'Emily Williams', 'Michael Brown', 'Sarah Thompson'];
                                    foreach ($patients as $index => $patient): 
                                        $patientId = $index + 1;
                                        $selected = ($patient === $appointment['patient_name']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $patientId; ?>" <?php echo $selected; ?>><?php echo $patient; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="appointmentType<?php echo $appointment['id']; ?>" class="form-label">Appointment Type</label>
                                <select class="form-select" id="appointmentType<?php echo $appointment['id']; ?>" required>
                                    <option value="" disabled>Select Type</option>
                                    <?php 
                                    $types = [
                                        'consultation' => 'Consultation',
                                        'followup' => 'Follow-up',
                                        'physical' => 'Physical Examination',
                                        'test' => 'Medical Test',
                                        'emergency' => 'Emergency'
                                    ];
                                    // Determine selected type based on reason
                                    $selectedType = '';
                                    if (stripos($appointment['reason'], 'consultation') !== false) $selectedType = 'consultation';
                                    else if (stripos($appointment['reason'], 'follow-up') !== false) $selectedType = 'followup';
                                    else if (stripos($appointment['reason'], 'physical') !== false) $selectedType = 'physical';
                                    else if (stripos($appointment['reason'], 'test') !== false) $selectedType = 'test';
                                    else if (stripos($appointment['reason'], 'emergency') !== false) $selectedType = 'emergency';
                                    
                                    foreach ($types as $value => $label): 
                                        $selected = ($value === $selectedType) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="appointmentDate<?php echo $appointment['id']; ?>" class="form-label">Date</label>
                                <input type="date" class="form-control" id="appointmentDate<?php echo $appointment['id']; ?>" value="<?php echo $appointment['date']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="appointmentTime<?php echo $appointment['id']; ?>" class="form-label">Time</label>
                                <input type="time" class="form-control" id="appointmentTime<?php echo $appointment['id']; ?>" value="<?php echo date('H:i', strtotime($appointment['time'])); ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentReason<?php echo $appointment['id']; ?>" class="form-label">Reason</label>
                            <input type="text" class="form-control" id="appointmentReason<?php echo $appointment['id']; ?>" value="<?php echo htmlspecialchars($appointment['reason']); ?>" placeholder="Reason for appointment">
                        </div>
                        <div class="mb-3">
                            <label for="appointmentNotes<?php echo $appointment['id']; ?>" class="form-label">Notes</label>
                            <textarea class="form-control" id="appointmentNotes<?php echo $appointment['id']; ?>" rows="3" placeholder="Additional notes or information"><?php echo htmlspecialchars($appointment['notes']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentStatus<?php echo $appointment['id']; ?>" class="form-label">Status</label>
                            <select class="form-select" id="appointmentStatus<?php echo $appointment['id']; ?>">
                                <option value="confirmed" <?php echo ($appointment['status'] === 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="pending" <?php echo ($appointment['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="cancelled" <?php echo ($appointment['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                <option value="completed" <?php echo ($appointment['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Cancel Appointment Modal -->
    <?php foreach ($appointments as $appointment): ?>
    <?php if ($appointment['status'] !== 'cancelled'): ?>
    <div class="modal fade" id="cancelAppointmentModal<?php echo $appointment['id']; ?>" tabindex="-1" aria-labelledby="cancelAppointmentModalLabel<?php echo $appointment['id']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelAppointmentModalLabel<?php echo $appointment['id']; ?>">Cancel Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel the appointment with <strong><?php echo htmlspecialchars($appointment['patient_name']); ?></strong> on <strong><?php echo htmlspecialchars($appointment['date']); ?></strong> at <strong><?php echo htmlspecialchars($appointment['time']); ?></strong>?</p>
                    <div class="mb-3">
                        <label for="cancelReason<?php echo $appointment['id']; ?>" class="form-label">Reason for Cancellation</label>
                        <textarea class="form-control" id="cancelReason<?php echo $appointment['id']; ?>" rows="3" placeholder="Please provide a reason for cancellation"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="notifyPatient<?php echo $appointment['id']; ?>" checked>
                        <label class="form-check-label" for="notifyPatient<?php echo $appointment['id']; ?>">
                            Notify patient about cancellation
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep It</button>
                    <button type="button" class="btn btn-danger">Yes, Cancel Appointment</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>

    <!-- Approve Appointment Modal -->
    <?php foreach ($appointments as $appointment): ?>
    <?php if ($appointment['status'] === 'confirmed'): ?>
    <div class="modal fade" id="approveAppointmentModal<?php echo $appointment['id']; ?>" tabindex="-1" aria-labelledby="approveAppointmentModalLabel<?php echo $appointment['id']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveAppointmentModalLabel<?php echo $appointment['id']; ?>">Mark Appointment as Completed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to mark the appointment with <strong><?php echo htmlspecialchars($appointment['patient_name']); ?></strong> on <strong><?php echo htmlspecialchars($appointment['date']); ?></strong> at <strong><?php echo htmlspecialchars($appointment['time']); ?></strong> as completed?</p>
                    
                    <div class="mb-3">
                        <label for="appointmentSummary<?php echo $appointment['id']; ?>" class="form-label">Appointment Summary</label>
                        <textarea class="form-control" id="appointmentSummary<?php echo $appointment['id']; ?>" rows="3" placeholder="Enter a summary of the appointment"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="followUpRequired<?php echo $appointment['id']; ?>" class="form-label">Follow-up Required</label>
                        <select class="form-select" id="followUpRequired<?php echo $appointment['id']; ?>">
                            <option value="no" selected>No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>
                    
                    <div id="followUpDetails<?php echo $appointment['id']; ?>" class="d-none">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="followUpDate<?php echo $appointment['id']; ?>" class="form-label">Follow-up Date</label>
                                <input type="date" class="form-control" id="followUpDate<?php echo $appointment['id']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="followUpTime<?php echo $appointment['id']; ?>" class="form-label">Follow-up Time</label>
                                <input type="time" class="form-control" id="followUpTime<?php echo $appointment['id']; ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="followUpReason<?php echo $appointment['id']; ?>" class="form-label">Follow-up Reason</label>
                            <input type="text" class="form-control" id="followUpReason<?php echo $appointment['id']; ?>" placeholder="Reason for follow-up">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success">Mark as Completed</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>

    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.1.3 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/main.js"></script>
    <script src="../js/appointment-form.js"></script>
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('#appointmentsTable').DataTable({
                responsive: true,
                order: [[2, 'asc'], [3, 'asc']] // Sort by date and then time
            });
        });

        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
        });
        
        // Toggle follow-up details in Approve modals
        <?php foreach ($appointments as $appointment): ?>
        <?php if ($appointment['status'] === 'confirmed'): ?>
        document.getElementById('followUpRequired<?php echo $appointment['id']; ?>').addEventListener('change', function() {
            const followUpDetails = document.getElementById('followUpDetails<?php echo $appointment['id']; ?>');
            if (this.value === 'yes') {
                followUpDetails.classList.remove('d-none');
            } else {
                followUpDetails.classList.add('d-none');
            }
        });
        <?php endif; ?>
        <?php endforeach; ?>
    </script>
</body>
</html>