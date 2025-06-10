<?php
// Start session
session_start();

// Include database configuration and functions
include '../includes/config.php';
include '../includes/patient_functions.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get patient information
$patient_id = $_SESSION['user_id'];
$patient_name = $_SESSION['user_name'];

// Get profile image path
$profile_image = getPatientProfileImage($conn, $patient_id);

// Placeholder data for prescriptions (in a real system, you would fetch this from the database)
$prescriptions = [
    [
        'id' => 1,
        'medication' => 'Lisinopril',
        'dosage' => '10mg',
        'frequency' => 'Once daily',
        'prescribed_by' => 'Dr. John Williams',
        'prescribed_date' => '2023-05-15',
        'start_date' => '2023-05-16',
        'end_date' => '2023-11-16',
        'refills_left' => 5,
        'instructions' => 'Take in the morning with food. Avoid grapefruit juice.',
        'status' => 'Active',
        'pharmacy' => 'HealthPlus Pharmacy',
        'pharmacy_address' => '123 Main Street, Suite 101',
        'pharmacy_phone' => '(555) 123-4567'
    ],
    [
        'id' => 2,
        'medication' => 'Metformin',
        'dosage' => '500mg',
        'frequency' => 'Twice daily',
        'prescribed_by' => 'Dr. Emily Rodriguez',
        'prescribed_date' => '2023-04-22',
        'start_date' => '2023-04-23',
        'end_date' => '2023-10-23',
        'refills_left' => 3,
        'instructions' => 'Take with meals. May cause stomach upset initially.',
        'status' => 'Active',
        'pharmacy' => 'MediCare Pharmacy',
        'pharmacy_address' => '456 Oak Avenue',
        'pharmacy_phone' => '(555) 987-6543'
    ],
    [
        'id' => 3,
        'medication' => 'Naproxen',
        'dosage' => '250mg',
        'frequency' => 'Twice daily as needed',
        'prescribed_by' => 'Dr. Michael Brown',
        'prescribed_date' => '2023-02-10',
        'start_date' => '2023-02-11',
        'end_date' => '2023-03-11',
        'refills_left' => 0,
        'instructions' => 'Take with food. Do not take with other NSAIDs.',
        'status' => 'Expired',
        'pharmacy' => 'QuickMeds Pharmacy',
        'pharmacy_address' => '789 Pine Street',
        'pharmacy_phone' => '(555) 456-7890'
    ],
    [
        'id' => 4,
        'medication' => 'Hydrocortisone Cream',
        'dosage' => '1%',
        'frequency' => 'Twice daily',
        'prescribed_by' => 'Dr. Sarah Johnson',
        'prescribed_date' => '2023-01-05',
        'start_date' => '2023-01-06',
        'end_date' => '2023-02-06',
        'refills_left' => 2,
        'instructions' => 'Apply to affected areas. Do not use on broken skin.',
        'status' => 'Expired',
        'pharmacy' => 'HealthPlus Pharmacy',
        'pharmacy_address' => '123 Main Street, Suite 101',
        'pharmacy_phone' => '(555) 123-4567'
    ],
    [
        'id' => 5,
        'medication' => 'Ibuprofen',
        'dosage' => '200mg',
        'frequency' => 'Every 4-6 hours as needed',
        'prescribed_by' => 'Dr. David Chen',
        'prescribed_date' => '2022-09-08',
        'start_date' => '2022-09-09',
        'end_date' => '2022-10-09',
        'refills_left' => 0,
        'instructions' => 'Take with food or milk. Do not exceed 6 tablets in 24 hours.',
        'status' => 'Expired',
        'pharmacy' => 'MediCare Pharmacy',
        'pharmacy_address' => '456 Oak Avenue',
        'pharmacy_phone' => '(555) 987-6543'
    ]
];

// Filter prescriptions based on status
$active_prescriptions = array_filter($prescriptions, function($prescription) {
    return $prescription['status'] === 'Active';
});

$expired_prescriptions = array_filter($prescriptions, function($prescription) {
    return $prescription['status'] === 'Expired';
});

// Handle refill request (in a real system, this would update the database and notify the pharmacy)
if (isset($_GET['request_refill']) && !empty($_GET['request_refill'])) {
    $refill_id = $_GET['request_refill'];
    // In a real system, process the refill request here
    // For demo purposes, we'll just set a message
    $refill_message = "Refill request for prescription #" . $refill_id . " has been submitted successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescriptions | Patient Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .prescription-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid #4e73df;
        }
        .prescription-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .prescription-card.expired {
            border-left-color: #858796;
        }
        .med-icon {
            font-size: 2rem;
            color: #4e73df;
        }
        .expired .med-icon {
            color: #858796;
        }
        .refill-btn {
            transition: all 0.3s ease;
        }
        .refill-btn:hover {
            transform: scale(1.05);
        }
        .nav-tabs .nav-link.active {
            font-weight: bold;
            border-bottom: 3px solid #4e73df;
        }
        .medication-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body class="dashboard-body">
   <!-- Sidebar Navigation -->
     <?php include 'sidebar_nav.php'; ?>    
            <!-- Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">My Prescriptions</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#requestPrescriptionModal">
                        <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Request New Prescription
                    </a>
                </div>

                <!-- Refill Success Message -->
                <?php if (isset($refill_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?php echo htmlspecialchars($refill_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Prescriptions</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo count($prescriptions); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-prescription fa-2x text-gray-300"></i>
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
                                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo count($active_prescriptions); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Available Refills</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            <?php
                                            $total_refills = 0;
                                            foreach ($active_prescriptions as $prescription) {
                                                $total_refills += $prescription['refills_left'];
                                            }
                                            echo $total_refills;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-prescription-bottle fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Pharmacies</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            <?php
                                            $pharmacies = [];
                                            foreach ($prescriptions as $prescription) {
                                                if (!in_array($prescription['pharmacy'], $pharmacies)) {
                                                    $pharmacies[] = $prescription['pharmacy'];
                                                }
                                            }
                                            echo count($pharmacies);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clinic-medical fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prescription Tabs -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <ul class="nav nav-tabs card-header-tabs" id="prescriptionTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">
                                    <i class="fas fa-check-circle me-1"></i> Active Prescriptions
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="expired-tab" data-bs-toggle="tab" data-bs-target="#expired" type="button" role="tab" aria-controls="expired" aria-selected="false">
                                    <i class="fas fa-history me-1"></i> Expired Prescriptions
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="prescriptionTabContent">
                            <!-- Active Prescriptions Tab -->
                            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                                <?php if (count($active_prescriptions) > 0): ?>
                                    <div class="row">
                                        <?php foreach ($active_prescriptions as $prescription): ?>
                                            <div class="col-lg-6 mb-4">
                                                <div class="card prescription-card h-100">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 fw-bold text-primary">
                                                            <?php echo htmlspecialchars($prescription['medication']); ?> 
                                                            <span class="badge bg-primary"><?php echo htmlspecialchars($prescription['dosage']); ?></span>
                                                        </h6>
                                                        <div>
                                                            <span class="badge bg-success">Active</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-3 text-center">
                                                                <i class="fas fa-prescription-bottle-alt med-icon mb-2"></i>
                                                                <p class="mb-0 small"><?php echo htmlspecialchars($prescription['frequency']); ?></p>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <p class="mb-1"><strong>Prescribed by:</strong> <?php echo htmlspecialchars($prescription['prescribed_by']); ?></p>
                                                                <p class="mb-1">
                                                                    <strong>Date:</strong> <?php echo htmlspecialchars($prescription['prescribed_date']); ?> to <?php echo htmlspecialchars($prescription['end_date']); ?>
                                                                </p>
                                                                <p class="mb-1"><strong>Refills remaining:</strong> <?php echo htmlspecialchars($prescription['refills_left']); ?></p>
                                                                <p class="mb-0"><strong>Instructions:</strong> <?php echo htmlspecialchars($prescription['instructions']); ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="bg-light p-3 rounded">
                                                            <h6 class="fw-bold"><i class="fas fa-clinic-medical me-1"></i> Pharmacy Details</h6>
                                                            <p class="mb-1"><?php echo htmlspecialchars($prescription['pharmacy']); ?></p>
                                                            <p class="mb-1"><?php echo htmlspecialchars($prescription['pharmacy_address']); ?></p>
                                                            <p class="mb-0"><i class="fas fa-phone-alt me-1"></i> <?php echo htmlspecialchars($prescription['pharmacy_phone']); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-white">
                                                        <div class="d-flex justify-content-between">
                                                            <?php if ($prescription['refills_left'] > 0): ?>
                                                                <a href="prescriptions.php?request_refill=<?php echo $prescription['id']; ?>" class="btn btn-primary refill-btn">
                                                                    <i class="fas fa-sync-alt me-1"></i> Request Refill
                                                                </a>
                                                            <?php else: ?>
                                                                <button class="btn btn-secondary" disabled>
                                                                    <i class="fas fa-times-circle me-1"></i> No Refills Left
                                                                </button>
                                                            <?php endif; ?>
                                                            <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#prescriptionDetailModal<?php echo $prescription['id']; ?>">
                                                                <i class="fas fa-eye me-1"></i> View Details
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info text-center" role="alert">
                                        <i class="fas fa-info-circle me-2"></i> You have no active prescriptions at this time.
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Expired Prescriptions Tab -->
                            <div class="tab-pane fade" id="expired" role="tabpanel" aria-labelledby="expired-tab">
                                <?php if (count($expired_prescriptions) > 0): ?>
                                    <div class="row">
                                        <?php foreach ($expired_prescriptions as $prescription): ?>
                                            <div class="col-lg-6 mb-4">
                                                <div class="card prescription-card expired h-100">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 fw-bold text-gray-600">
                                                            <?php echo htmlspecialchars($prescription['medication']); ?> 
                                                            <span class="badge bg-secondary"><?php echo htmlspecialchars($prescription['dosage']); ?></span>
                                                        </h6>
                                                        <div>
                                                            <span class="badge bg-secondary">Expired</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-3 text-center">
                                                                <i class="fas fa-prescription-bottle-alt med-icon mb-2"></i>
                                                                <p class="mb-0 small"><?php echo htmlspecialchars($prescription['frequency']); ?></p>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <p class="mb-1"><strong>Prescribed by:</strong> <?php echo htmlspecialchars($prescription['prescribed_by']); ?></p>
                                                                <p class="mb-1">
                                                                    <strong>Date:</strong> <?php echo htmlspecialchars($prescription['prescribed_date']); ?> to <?php echo htmlspecialchars($prescription['end_date']); ?>
                                                                </p>
                                                                <p class="mb-1"><strong>Refills remaining:</strong> <?php echo htmlspecialchars($prescription['refills_left']); ?></p>
                                                                <p class="mb-0"><strong>Instructions:</strong> <?php echo htmlspecialchars($prescription['instructions']); ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="bg-light p-3 rounded">
                                                            <h6 class="fw-bold"><i class="fas fa-clinic-medical me-1"></i> Pharmacy Details</h6>
                                                            <p class="mb-1"><?php echo htmlspecialchars($prescription['pharmacy']); ?></p>
                                                            <p class="mb-1"><?php echo htmlspecialchars($prescription['pharmacy_address']); ?></p>
                                                            <p class="mb-0"><i class="fas fa-phone-alt me-1"></i> <?php echo htmlspecialchars($prescription['pharmacy_phone']); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-white">
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-secondary" disabled>
                                                                <i class="fas fa-hourglass-end me-1"></i> Expired
                                                            </button>
                                                            <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#prescriptionDetailModal<?php echo $prescription['id']; ?>">
                                                                <i class="fas fa-eye me-1"></i> View Details
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info text-center" role="alert">
                                        <i class="fas fa-info-circle me-2"></i> You have no expired prescriptions.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Prescription Modal -->
    <div class="modal fade" id="requestPrescriptionModal" tabindex="-1" aria-labelledby="requestPrescriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestPrescriptionModalLabel">Request Prescription Renewal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="prescriptionType" class="form-label">Prescription Type</label>
                            <select class="form-select" id="prescriptionType" required>
                                <option value="" selected disabled>Select prescription type</option>
                                <option value="renewal">Prescription Renewal</option>
                                <option value="new">New Prescription</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="medicationName" class="form-label">Medication</label>
                            <input type="text" class="form-control" id="medicationName" placeholder="Enter medication name">
                        </div>
                        <div class="mb-3">
                            <label for="doctorName" class="form-label">Prescribing Doctor</label>
                            <select class="form-select" id="doctorName">
                                <option value="" selected disabled>Select doctor</option>
                                <option value="Dr. John Williams">Dr. John Williams - Cardiology</option>
                                <option value="Dr. Emily Rodriguez">Dr. Emily Rodriguez - Endocrinology</option>
                                <option value="Dr. Michael Brown">Dr. Michael Brown - Orthopedics</option>
                                <option value="Dr. Sarah Johnson">Dr. Sarah Johnson - Dermatology</option>
                                <option value="Dr. David Chen">Dr. David Chen - Neurology</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="reasonRequest" class="form-label">Reason for Request</label>
                            <textarea class="form-control" id="reasonRequest" rows="3" placeholder="Please provide details on why you need this prescription"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="preferredPharmacy" class="form-label">Preferred Pharmacy</label>
                            <select class="form-select" id="preferredPharmacy">
                                <option value="" selected disabled>Select pharmacy</option>
                                <option value="HealthPlus Pharmacy">HealthPlus Pharmacy - 123 Main Street, Suite 101</option>
                                <option value="MediCare Pharmacy">MediCare Pharmacy - 456 Oak Avenue</option>
                                <option value="QuickMeds Pharmacy">QuickMeds Pharmacy - 789 Pine Street</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="urgentRequest">
                                <label class="form-check-label" for="urgentRequest">
                                    Mark as urgent (Additional fees may apply)
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Submit Request</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescription Detail Modal (Example for first prescription) -->
    <div class="modal fade" id="prescriptionDetailModal1" tabindex="-1" aria-labelledby="prescriptionDetailModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="prescriptionDetailModalLabel1">Prescription Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Basic Information</h6>
                            <p class="mb-1"><strong>Prescription ID:</strong> #RX1001</p>
                            <p class="mb-1"><strong>Date Prescribed:</strong> May 15, 2023</p>
                            <p class="mb-1"><strong>Valid Until:</strong> November 16, 2023</p>
                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Healthcare Provider</h6>
                            <p class="mb-1"><strong>Prescribed by:</strong> Dr. John Williams</p>
                            <p class="mb-1"><strong>Specialty:</strong> Cardiology</p>
                            <p class="mb-1"><strong>Medical Facility:</strong> HealthConnect Main Hospital</p>
                            <p class="mb-1"><strong>Contact:</strong> (555) 123-4567</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="fw-bold">Medication Information</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Medication</th>
                                        <td>Lisinopril 10mg</td>
                                    </tr>
                                    <tr>
                                        <th>Dosage</th>
                                        <td>1 tablet once daily</td>
                                    </tr>
                                    <tr>
                                        <th>Instructions</th>
                                        <td>Take in the morning with food. Avoid grapefruit juice.</td>
                                    </tr>
                                    <tr>
                                        <th>Refills</th>
                                        <td>5 refills remaining (originally 6 refills)</td>
                                    </tr>
                                    <tr>
                                        <th>Last Refill Date</th>
                                        <td>June 15, 2023</td>
                                    </tr>
                                    <tr>
                                        <th>Side Effects</th>
                                        <td>
                                            <p class="mb-1">Common: Dizziness, headache, dry cough</p>
                                            <p class="mb-0">Serious: Swelling of face/lips/tongue, difficulty breathing, severe dizziness</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Pharmacy Information</h6>
                            <p class="mb-1"><strong>Pharmacy:</strong> HealthPlus Pharmacy</p>
                            <p class="mb-1"><strong>Address:</strong> 123 Main Street, Suite 101</p>
                            <p class="mb-1"><strong>Phone:</strong> (555) 123-4567</p>
                            <p class="mb-0"><strong>Hours:</strong> Mon-Fri 8am-9pm, Sat-Sun 9am-6pm</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Additional Information</h6>
                            <p class="mb-1"><strong>Drug Class:</strong> ACE Inhibitor</p>
                            <p class="mb-1"><strong>Generic Available:</strong> Yes</p>
                            <p class="mb-1"><strong>Insurance Coverage:</strong> Tier 1 - $10 Copay</p>
                            <p class="mb-0"><strong>Related Diagnosis:</strong> Hypertension</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Print</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Request Refill</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.1.3 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/main.js"></script>
    <script>
        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
        });
    </script>
</body>
</html> 