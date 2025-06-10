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
$prescriptions = [
    [
        'id' => 1,
        'patient_name' => 'Jane Smith',
        'patient_id' => 1,
        'date' => '2023-06-01',
        'medication' => 'Lisinopril 10mg',
        'dosage' => '1 tablet',
        'frequency' => 'Once daily',
        'duration' => '30 days',
        'refills' => '2',
        'directions' => 'Take with water in the morning',
        'notes' => 'Monitor blood pressure weekly',
        'status' => 'active'
    ],
    [
        'id' => 2,
        'patient_name' => 'Robert Johnson',
        'patient_id' => 2,
        'date' => '2023-05-28',
        'medication' => 'Metformin 1000mg',
        'dosage' => '1 tablet',
        'frequency' => 'Twice daily',
        'duration' => '90 days',
        'refills' => '3',
        'directions' => 'Take with food in the morning and evening',
        'notes' => 'Monitor blood sugar levels regularly',
        'status' => 'active'
    ],
    [
        'id' => 3,
        'patient_name' => 'Emily Williams',
        'patient_id' => 3,
        'date' => '2023-05-15',
        'medication' => 'Prenatal Vitamins',
        'dosage' => '1 tablet',
        'frequency' => 'Once daily',
        'duration' => '30 days',
        'refills' => '5',
        'directions' => 'Take with food',
        'notes' => 'Continue through pregnancy',
        'status' => 'active'
    ],
    [
        'id' => 4,
        'patient_name' => 'Michael Brown',
        'patient_id' => 4,
        'date' => '2023-06-10',
        'medication' => 'Ibuprofen 600mg',
        'dosage' => '1 tablet',
        'frequency' => 'Three times daily',
        'duration' => '10 days',
        'refills' => '0',
        'directions' => 'Take with food to minimize stomach irritation',
        'notes' => 'Short term use only for joint pain',
        'status' => 'active'
    ],
    [
        'id' => 5,
        'patient_name' => 'Sarah Thompson',
        'patient_id' => 5,
        'date' => '2023-06-05',
        'medication' => 'Albuterol Inhaler',
        'dosage' => '2 puffs',
        'frequency' => 'As needed',
        'duration' => '30 days',
        'refills' => '1',
        'directions' => 'Use as rescue inhaler for asthma attacks',
        'notes' => 'Do not exceed 8 puffs in 24 hours',
        'status' => 'active'
    ],
    [
        'id' => 6,
        'patient_name' => 'David Wilson',
        'patient_id' => 6,
        'date' => '2023-06-08',
        'medication' => 'Oxycodone 5mg',
        'dosage' => '1 tablet',
        'frequency' => 'Every 6 hours as needed for pain',
        'duration' => '5 days',
        'refills' => '0',
        'directions' => 'Take only if pain is severe',
        'notes' => 'Controlled substance. No refills. Taper off as pain improves.',
        'status' => 'active'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescriptions | Doctor Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-body">
     <!-- Sidebar and Navigation -->
    <?php include 'sidebar_nav.php'; ?>

            <!-- Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Prescriptions</h1>
                    <div>
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addPrescriptionModal">
                            <i class="fas fa-plus fa-sm me-2"></i> New Prescription
                        </a>
                        <a href="#" class="btn btn-info btn-sm shadow-sm ms-2" onclick="window.print();">
                            <i class="fas fa-print fa-sm me-2"></i> Print
                        </a>
                    </div>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <!-- Prescription Statistics Cards -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Prescriptions</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">78</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-prescription-bottle fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Active Prescriptions</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">42</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Expiring Soon</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">9</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Refill Requests</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">3</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-sync-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prescription Filter -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">Filter Prescriptions</h6>
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
                                    <label for="patientFilter" class="form-label">Patient</label>
                                    <select class="form-select" id="patientFilter">
                                        <option value="" selected>All Patients</option>
                                        <option value="1">Jane Smith</option>
                                        <option value="2">Robert Johnson</option>
                                        <option value="3">Emily Williams</option>
                                        <option value="4">Michael Brown</option>
                                        <option value="5">Sarah Thompson</option>
                                        <option value="6">David Wilson</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <label for="prescriptionStatus" class="form-label">Status</label>
                                    <select class="form-select" id="prescriptionStatus">
                                        <option value="" selected>All Statuses</option>
                                        <option value="active">Active</option>
                                        <option value="expired">Expired</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <label for="dateRange" class="form-label">Date Range</label>
                                    <select class="form-select" id="dateRange">
                                        <option value="all" selected>All Time</option>
                                        <option value="today">Today</option>
                                        <option value="thisweek">This Week</option>
                                        <option value="thismonth">This Month</option>
                                        <option value="lastsixmonths">Last 6 Months</option>
                                        <option value="lastyear">Last Year</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
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

                <!-- Prescriptions Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">All Prescriptions</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="prescriptionsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Medication</th>
                                        <th>Dosage</th>
                                        <th>Duration</th>
                                        <th>Refills</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($prescriptions as $prescription): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($prescription['id']); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2" style="width: 32px; height: 32px; background-color: #4e73df; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold;">
                                                    <?php echo substr(htmlspecialchars($prescription['patient_name']), 0, 1); ?>
                                                </div>
                                                <?php echo htmlspecialchars($prescription['patient_name']); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($prescription['date']); ?></td>
                                        <td><?php echo htmlspecialchars($prescription['medication']); ?></td>
                                        <td><?php echo htmlspecialchars($prescription['dosage'] . ' ' . $prescription['frequency']); ?></td>
                                        <td><?php echo htmlspecialchars($prescription['duration']); ?></td>
                                        <td>
                                            <?php if ($prescription['refills'] > 0): ?>
                                                <span class="badge bg-success"><?php echo htmlspecialchars($prescription['refills']); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $prescription['status'] === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo ucfirst(htmlspecialchars($prescription['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewPrescriptionModal<?php echo $prescription['id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editPrescriptionModal<?php echo $prescription['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#printPrescriptionModal<?php echo $prescription['id']; ?>">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#refillPrescriptionModal<?php echo $prescription['id']; ?>">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
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

    <!-- Add New Prescription Modal -->
    <div class="modal fade" id="addPrescriptionModal" tabindex="-1" aria-labelledby="addPrescriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPrescriptionModalLabel">Create New Prescription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="patientSelect" class="form-label">Patient</label>
                                <select class="form-select" id="patientSelect" required>
                                    <option value="" selected disabled>Select Patient</option>
                                    <option value="1">Jane Smith</option>
                                    <option value="2">Robert Johnson</option>
                                    <option value="3">Emily Williams</option>
                                    <option value="4">Michael Brown</option>
                                    <option value="5">Sarah Thompson</option>
                                    <option value="6">David Wilson</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="prescriptionDate" class="form-label">Prescription Date</label>
                                <input type="date" class="form-control" id="prescriptionDate" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="medication" class="form-label">Medication</label>
                                <input type="text" class="form-control" id="medication" placeholder="Medication name and strength" required>
                            </div>
                            <div class="col-md-6">
                                <label for="dosage" class="form-label">Dosage</label>
                                <input type="text" class="form-control" id="dosage" placeholder="e.g. 1 tablet, 2 capsules" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="frequency" class="form-label">Frequency</label>
                                <select class="form-select" id="frequency" required>
                                    <option value="" selected disabled>Select Frequency</option>
                                    <option value="once_daily">Once Daily</option>
                                    <option value="twice_daily">Twice Daily</option>
                                    <option value="three_daily">Three Times Daily</option>
                                    <option value="four_daily">Four Times Daily</option>
                                    <option value="every_morning">Every Morning</option>
                                    <option value="every_evening">Every Evening</option>
                                    <option value="every_hour">Every Hour</option>
                                    <option value="every_4_hours">Every 4 Hours</option>
                                    <option value="every_6_hours">Every 6 Hours</option>
                                    <option value="every_8_hours">Every 8 Hours</option>
                                    <option value="every_12_hours">Every 12 Hours</option>
                                    <option value="as_needed">As Needed (PRN)</option>
                                    <option value="other">Other (Specify)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="duration" class="form-label">Duration</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="duration" min="1" required>
                                    <select class="form-select" id="durationUnit">
                                        <option value="days">Days</option>
                                        <option value="weeks">Weeks</option>
                                        <option value="months">Months</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="refills" class="form-label">Refills</label>
                                <input type="number" class="form-control" id="refills" min="0" max="12" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="directions" class="form-label">Directions for Use</label>
                            <textarea class="form-control" id="directions" rows="2" placeholder="Detailed instructions for taking medication" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes" rows="2" placeholder="Additional information or warnings"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="dispenseAsWritten">
                                <label class="form-check-label" for="dispenseAsWritten">
                                    Dispense As Written (No Substitution)
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Prescription</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Prescription Modals -->
    <?php foreach ($prescriptions as $prescription): ?>
    <div class="modal fade" id="viewPrescriptionModal<?php echo $prescription['id']; ?>" tabindex="-1" aria-labelledby="viewPrescriptionModalLabel<?php echo $prescription['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPrescriptionModalLabel<?php echo $prescription['id']; ?>">View Prescription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="prescription-view">
                        <div class="prescription-header mb-4 p-3 bg-light border rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($prescription['patient_name']); ?></h6>
                                    <p class="mb-1 small">Patient ID: <?php echo htmlspecialchars($prescription['patient_id']); ?></p>
                                    <p class="mb-0 small">Prescription #: <?php echo htmlspecialchars($prescription['id']); ?></p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($doctor_name); ?></h6>
                                    <p class="mb-1 small">NPI: 1234567890</p>
                                    <p class="mb-0 small">Date: <?php echo htmlspecialchars($prescription['date']); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="prescription-details mb-4">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Medication:</strong> <?php echo htmlspecialchars($prescription['medication']); ?></p>
                                    <p class="mb-1"><strong>Dosage:</strong> <?php echo htmlspecialchars($prescription['dosage']); ?></p>
                                    <p class="mb-1"><strong>Frequency:</strong> <?php echo htmlspecialchars($prescription['frequency']); ?></p>
                                    <p class="mb-0"><strong>Duration:</strong> <?php echo htmlspecialchars($prescription['duration']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Refills:</strong> <?php echo htmlspecialchars($prescription['refills']); ?></p>
                                    <p class="mb-1"><strong>Status:</strong> 
                                        <span class="badge <?php echo $prescription['status'] === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo ucfirst(htmlspecialchars($prescription['status'])); ?>
                                        </span>
                                    </p>
                                    <p class="mb-0"><strong>Prescriber:</strong><?php echo htmlspecialchars($doctor_name); ?></p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-1"><strong>Directions:</strong> <?php echo htmlspecialchars($prescription['directions']); ?></p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-0"><strong>Notes:</strong> <?php echo htmlspecialchars($prescription['notes']); ?></p>
                            </div>
                        </div>
                        
                        <div class="prescription-signature mt-4 pt-4 border-top">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-0 small text-muted">Electronic Signature: Dr. <?php echo htmlspecialchars($doctor_name); ?></p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p class="mb-0 small text-muted">Prescription ID: RX-<?php echo date('ymd', strtotime($prescription['date'])); ?>-<?php echo sprintf('%03d', $prescription['id']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editPrescriptionModal<?php echo $prescription['id']; ?>">
                        <i class="fas fa-edit me-1"></i> Edit
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#printPrescriptionModal<?php echo $prescription['id']; ?>">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Edit Prescription Modals -->
    <?php foreach ($prescriptions as $prescription): ?>
    <div class="modal fade" id="editPrescriptionModal<?php echo $prescription['id']; ?>" tabindex="-1" aria-labelledby="editPrescriptionModalLabel<?php echo $prescription['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPrescriptionModalLabel<?php echo $prescription['id']; ?>">Edit Prescription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="patientSelect<?php echo $prescription['id']; ?>" class="form-label">Patient</label>
                                <select class="form-select" id="patientSelect<?php echo $prescription['id']; ?>" required>
                                    <option value="" disabled>Select Patient</option>
                                    <?php 
                                    $patients = [
                                        1 => 'Jane Smith',
                                        2 => 'Robert Johnson',
                                        3 => 'Emily Williams',
                                        4 => 'Michael Brown',
                                        5 => 'Sarah Thompson',
                                        6 => 'David Wilson'
                                    ];
                                    foreach ($patients as $id => $name): 
                                        $selected = ($id == $prescription['patient_id']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $id; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="prescriptionDate<?php echo $prescription['id']; ?>" class="form-label">Prescription Date</label>
                                <input type="date" class="form-control" id="prescriptionDate<?php echo $prescription['id']; ?>" value="<?php echo $prescription['date']; ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="medication<?php echo $prescription['id']; ?>" class="form-label">Medication</label>
                                <input type="text" class="form-control" id="medication<?php echo $prescription['id']; ?>" value="<?php echo htmlspecialchars($prescription['medication']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="dosage<?php echo $prescription['id']; ?>" class="form-label">Dosage</label>
                                <input type="text" class="form-control" id="dosage<?php echo $prescription['id']; ?>" value="<?php echo htmlspecialchars($prescription['dosage']); ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="frequency<?php echo $prescription['id']; ?>" class="form-label">Frequency</label>
                                <select class="form-select" id="frequency<?php echo $prescription['id']; ?>" required>
                                    <?php 
                                    $frequencies = [
                                        'once_daily' => 'Once Daily',
                                        'twice_daily' => 'Twice Daily',
                                        'three_daily' => 'Three Times Daily',
                                        'four_daily' => 'Four Times Daily',
                                        'every_morning' => 'Every Morning',
                                        'every_evening' => 'Every Evening',
                                        'every_hour' => 'Every Hour',
                                        'every_4_hours' => 'Every 4 Hours',
                                        'every_6_hours' => 'Every 6 Hours',
                                        'every_8_hours' => 'Every 8 Hours',
                                        'every_12_hours' => 'Every 12 Hours',
                                        'as_needed' => 'As Needed (PRN)',
                                        'other' => 'Other (Specify)'
                                    ];
                                    
                                    // Find the closest match for the frequency
                                    $currentFrequency = 'other';
                                    foreach ($frequencies as $key => $label) {
                                        if (stripos($prescription['frequency'], $label) !== false) {
                                            $currentFrequency = $key;
                                            break;
                                        }
                                    }
                                    
                                    foreach ($frequencies as $value => $label): 
                                        $selected = ($value === $currentFrequency) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="duration<?php echo $prescription['id']; ?>" class="form-label">Duration</label>
                                <div class="input-group">
                                    <?php 
                                    // Extract the duration number and unit
                                    $durationParts = explode(' ', $prescription['duration']);
                                    $durationNumber = intval($durationParts[0]);
                                    $durationUnit = strtolower(trim($durationParts[1]));
                                    if (substr($durationUnit, -1) === 's') {
                                        $durationUnit = substr($durationUnit, 0, -1); // Remove trailing 's'
                                    }
                                    ?>
                                    <input type="number" class="form-control" id="duration<?php echo $prescription['id']; ?>" value="<?php echo $durationNumber; ?>" min="1" required>
                                    <select class="form-select" id="durationUnit<?php echo $prescription['id']; ?>">
                                        <option value="day" <?php echo ($durationUnit === 'day') ? 'selected' : ''; ?>>Days</option>
                                        <option value="week" <?php echo ($durationUnit === 'week') ? 'selected' : ''; ?>>Weeks</option>
                                        <option value="month" <?php echo ($durationUnit === 'month') ? 'selected' : ''; ?>>Months</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="refills<?php echo $prescription['id']; ?>" class="form-label">Refills</label>
                                <input type="number" class="form-control" id="refills<?php echo $prescription['id']; ?>" value="<?php echo $prescription['refills']; ?>" min="0" max="12">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="directions<?php echo $prescription['id']; ?>" class="form-label">Directions for Use</label>
                            <textarea class="form-control" id="directions<?php echo $prescription['id']; ?>" rows="2" required><?php echo htmlspecialchars($prescription['directions']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes<?php echo $prescription['id']; ?>" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes<?php echo $prescription['id']; ?>" rows="2"><?php echo htmlspecialchars($prescription['notes']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="dispenseAsWritten<?php echo $prescription['id']; ?>">
                                <label class="form-check-label" for="dispenseAsWritten<?php echo $prescription['id']; ?>">
                                    Dispense As Written (No Substitution)
                                </label>
                            </div>
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

    <!-- Print Prescription Modals -->
    <?php foreach ($prescriptions as $prescription): ?>
    <div class="modal fade" id="printPrescriptionModal<?php echo $prescription['id']; ?>" tabindex="-1" aria-labelledby="printPrescriptionModalLabel<?php echo $prescription['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printPrescriptionModalLabel<?php echo $prescription['id']; ?>">Print Prescription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="prescription-print p-4 border rounded" id="printable-prescription-<?php echo $prescription['id']; ?>">
                        <div class="text-center mb-4">
                            <h4 class="mb-1">HealthConnect Medical Center</h4>
                            <p class="mb-1 small">123 Healthcare Ave, Medical City, MC 12345</p>
                            <p class="mb-0 small">Phone: (555) 123-4567 | Fax: (555) 987-6543</p>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-6">
                                <h5 class="border-bottom pb-2">Patient Information</h5>
                                <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($prescription['patient_name']); ?></p>
                                <p class="mb-1"><strong>Patient ID:</strong> <?php echo htmlspecialchars($prescription['patient_id']); ?></p>
                                <p class="mb-0"><strong>Date:</strong> <?php echo htmlspecialchars($prescription['date']); ?></p>
                            </div>
                            <div class="col-6">
                                <h5 class="border-bottom pb-2">Prescriber Information</h5>
                                <p class="mb-1"><strong>Name:</strong><?php echo htmlspecialchars($doctor_name); ?></p>
                                <p class="mb-1"><strong>NPI:</strong> 1234567890</p>
                                <p class="mb-0"><strong>DEA:</strong> XY1234567</p>
                            </div>
                        </div>
                        
                        <div class="prescription-content p-3 border rounded mb-4">
                            <h5 class="text-center mb-3">Prescription</h5>
                            <p class="mb-1"><strong>Medication:</strong> <?php echo htmlspecialchars($prescription['medication']); ?></p>
                            <p class="mb-1"><strong>Dosage:</strong> <?php echo htmlspecialchars($prescription['dosage']); ?></p>
                            <p class="mb-1"><strong>Frequency:</strong> <?php echo htmlspecialchars($prescription['frequency']); ?></p>
                            <p class="mb-1"><strong>Duration:</strong> <?php echo htmlspecialchars($prescription['duration']); ?></p>
                            <p class="mb-1"><strong>Refills:</strong> <?php echo htmlspecialchars($prescription['refills']); ?></p>
                            <p class="mb-1"><strong>Directions:</strong> <?php echo htmlspecialchars($prescription['directions']); ?></p>
                            <?php if (!empty($prescription['notes'])): ?>
                            <p class="mb-0"><strong>Notes:</strong> <?php echo htmlspecialchars($prescription['notes']); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="row mt-5">
                            <div class="col-6">
                                <div class="border-top pt-2">
                                    <p class="mb-0">Prescriber Signature</p>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <p class="mb-0 small">Prescription ID: RX-<?php echo date('ymd', strtotime($prescription['date'])); ?>-<?php echo sprintf('%03d', $prescription['id']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="printPrescription(<?php echo $prescription['id']; ?>)">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Refill Prescription Modals -->
    <?php foreach ($prescriptions as $prescription): ?>
    <div class="modal fade" id="refillPrescriptionModal<?php echo $prescription['id']; ?>" tabindex="-1" aria-labelledby="refillPrescriptionModalLabel<?php echo $prescription['id']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="refillPrescriptionModalLabel<?php echo $prescription['id']; ?>">Refill Prescription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> You are about to authorize a refill for the following prescription:
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title"><?php echo htmlspecialchars($prescription['medication']); ?></h6>
                            <p class="card-text mb-1"><strong>Patient:</strong> <?php echo htmlspecialchars($prescription['patient_name']); ?></p>
                            <p class="card-text mb-1"><strong>Dosage:</strong> <?php echo htmlspecialchars($prescription['dosage']); ?> - <?php echo htmlspecialchars($prescription['frequency']); ?></p>
                            <p class="card-text mb-0"><strong>Current Refills Remaining:</strong> <?php echo htmlspecialchars($prescription['refills']); ?></p>
                        </div>
                    </div>
                    
                    <form>
                        <div class="mb-3">
                            <label for="refillCount<?php echo $prescription['id']; ?>" class="form-label">Number of Refills to Authorize</label>
                            <input type="number" class="form-control" id="refillCount<?php echo $prescription['id']; ?>" min="1" max="12" value="1">
                        </div>
                        <div class="mb-3">
                            <label for="refillNotes<?php echo $prescription['id']; ?>" class="form-label">Notes</label>
                            <textarea class="form-control" id="refillNotes<?php echo $prescription['id']; ?>" rows="3" placeholder="Any special instructions or changes to the prescription"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="notifyPatient<?php echo $prescription['id']; ?>" checked>
                            <label class="form-check-label" for="notifyPatient<?php echo $prescription['id']; ?>">
                                Notify patient about refill authorization
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="requireFollowUp<?php echo $prescription['id']; ?>">
                            <label class="form-check-label" for="requireFollowUp<?php echo $prescription['id']; ?>">
                                Require follow-up appointment before next refill
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Authorize Refill</button>
                </div>
            </div>
        </div>
    </div>
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
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('#prescriptionsTable').DataTable({
                responsive: true,
                order: [[2, 'desc']] // Sort by date (newest first)
            });
        });

        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
        });
        
        // Function to print prescription
        function printPrescription(prescriptionId) {
            const printContents = document.getElementById(`printable-prescription-${prescriptionId}`).innerHTML;
            const originalContents = document.body.innerHTML;
            
            // Create a new window with only the printable content
            document.body.innerHTML = `
                <html>
                <head>
                    <title>Prescription #${prescriptionId}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .text-center { text-align: center; }
                        .mb-1 { margin-bottom: 0.25rem; }
                        .mb-0 { margin-bottom: 0; }
                        .mb-2 { margin-bottom: 0.5rem; }
                        .mb-3 { margin-bottom: 1rem; }
                        .mb-4 { margin-bottom: 1.5rem; }
                        .mt-4 { margin-top: 1.5rem; }
                        .mt-5 { margin-top: 3rem; }
                        .pb-2 { padding-bottom: 0.5rem; }
                        .pt-2 { padding-top: 0.5rem; }
                        .border-bottom { border-bottom: 1px solid #dee2e6; }
                        .border-top { border-top: 1px solid #dee2e6; }
                        .row { display: flex; flex-wrap: wrap; margin-right: -0.75rem; margin-left: -0.75rem; }
                        .col-6 { flex: 0 0 50%; max-width: 50%; padding-right: 0.75rem; padding-left: 0.75rem; }
                        .col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; padding-right: 0.75rem; padding-left: 0.75rem; }
                        .small { font-size: 0.875em; }
                        .text-end { text-align: right; }
                        h4, h5, h6 { margin-top: 0; }
                        p { margin-top: 0; }
                        .prescription-content { border: 1px solid #dee2e6; padding: 1rem; margin-bottom: 1.5rem; }
                        @media print {
                            body { margin: 0; padding: 15px; }
                        }
                    </style>
                </head>
                <body>
                    ${printContents}
                </body>
                </html>
            `;
            
            // Print the document
            window.print();
            
            // Restore the original content
            setTimeout(function() {
                document.body.innerHTML = originalContents;
                // Reinitialize any event listeners and scripts that were lost
                location.reload();
            }, 1000);
        }
    </script>
</body>
</html>