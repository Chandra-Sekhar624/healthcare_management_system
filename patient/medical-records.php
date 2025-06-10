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

// Placeholder data for medical records (in a real system, you would fetch this from the database)
$medical_records = [
    [
        'id' => 1,
        'date' => '2023-05-15',
        'doctor' => 'Dr. John Williams',
        'specialty' => 'Cardiology',
        'diagnosis' => 'Hypertension',
        'treatment' => 'Prescribed Lisinopril 10mg once daily',
        'notes' => 'Blood pressure reading: 145/95. Follow-up in 1 month to evaluate medication effectiveness.',
        'documents' => ['Blood Test Results', 'ECG Report'],
        'type' => 'Consultation'
    ],
    [
        'id' => 2,
        'date' => '2023-04-22',
        'doctor' => 'Dr. Emily Rodriguez',
        'specialty' => 'Endocrinology',
        'diagnosis' => 'Type 2 Diabetes',
        'treatment' => 'Prescribed Metformin 500mg twice daily. Recommended diet and exercise plan.',
        'notes' => 'Blood glucose level: 180 mg/dL. Patient advised to monitor blood sugar regularly and maintain food diary.',
        'documents' => ['Blood Sugar Test', 'Diet Plan'],
        'type' => 'Follow-up'
    ],
    [
        'id' => 3,
        'date' => '2023-02-10',
        'doctor' => 'Dr. Michael Brown',
        'specialty' => 'Orthopedics',
        'diagnosis' => 'Knee Osteoarthritis',
        'treatment' => 'Prescribed Naproxen for pain. Recommended physical therapy twice weekly for 6 weeks.',
        'notes' => 'X-ray shows moderate joint space narrowing. Patient complains of pain during walking and climbing stairs.',
        'documents' => ['Knee X-Ray Report', 'Physical Therapy Plan'],
        'type' => 'Consultation'
    ],
    [
        'id' => 4,
        'date' => '2023-01-05',
        'doctor' => 'Dr. Sarah Johnson',
        'specialty' => 'Dermatology',
        'diagnosis' => 'Eczema',
        'treatment' => 'Prescribed hydrocortisone cream to be applied twice daily. Recommended fragrance-free soap and moisturizer.',
        'notes' => 'Skin patches on both arms and neck. Patient reports itching and discomfort especially at night.',
        'documents' => ['Allergy Test Results'],
        'type' => 'Consultation'
    ],
    [
        'id' => 5,
        'date' => '2022-11-20',
        'doctor' => 'Dr. John Williams',
        'specialty' => 'Cardiology',
        'diagnosis' => 'Annual Health Check-up',
        'treatment' => 'No medication prescribed. Recommended regular exercise and balanced diet.',
        'notes' => 'All vital signs within normal range. Cholesterol slightly elevated at 210 mg/dL. Advised to reduce intake of saturated fats.',
        'documents' => ['Complete Blood Work', 'Lipid Profile'],
        'type' => 'Check-up'
    ],
    [
        'id' => 6,
        'date' => '2022-09-08',
        'doctor' => 'Dr. David Chen',
        'specialty' => 'Neurology',
        'diagnosis' => 'Tension Headache',
        'treatment' => 'Prescribed Ibuprofen for pain. Suggested stress management techniques and improved sleep hygiene.',
        'notes' => 'Patient reports frequent headaches, especially during work hours. No neurological abnormalities detected.',
        'documents' => ['Neurological Assessment'],
        'type' => 'Consultation'
    ]
];

// Filter medical records based on search parameters
$filtered_records = $medical_records;
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = strtolower($_GET['search']);
    $filtered_records = array_filter($medical_records, function($record) use ($search) {
        return strpos(strtolower($record['doctor']), $search) !== false || 
               strpos(strtolower($record['diagnosis']), $search) !== false ||
               strpos(strtolower($record['specialty']), $search) !== false;
    });
}

// Filter by type if provided
if (isset($_GET['type']) && !empty($_GET['type'])) {
    $type = $_GET['type'];
    $filtered_records = array_filter($filtered_records, function($record) use ($type) {
        return $record['type'] === $type;
    });
}

// Filter by date range if provided
if (isset($_GET['from_date']) && !empty($_GET['from_date']) && isset($_GET['to_date']) && !empty($_GET['to_date'])) {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];
    
    $filtered_records = array_filter($filtered_records, function($record) use ($from_date, $to_date) {
        return $record['date'] >= $from_date && $record['date'] <= $to_date;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records | Patient Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .record-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .record-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .search-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .document-link {
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 5px;
            padding: 5px 10px;
            background-color: #f1f1f1;
            border-radius: 4px;
            color: #333;
            text-decoration: none;
            font-size: 0.85rem;
        }
        .document-link:hover {
            background-color: #e7e7e7;
        }
        .document-link i {
            margin-right: 5px;
        }
        .record-type-badge {
            position: absolute;
            top: 10px;
            right: 10px;
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
                    <h1 class="h3 mb-0 text-gray-800">Medical Records</h1>
                    <button class="d-none d-sm-inline-block btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#requestRecordsModal">
                        <i class="fas fa-download fa-sm text-white-50 me-1"></i> Request Records
                    </button>
                </div>

                <!-- Search and Filters -->
                <div class="search-box shadow-sm">
                    <form action="" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Search by doctor, diagnosis or specialty" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="type" class="form-label">Record Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All Types</option>
                                <option value="Consultation" <?php echo (isset($_GET['type']) && $_GET['type'] === 'Consultation') ? 'selected' : ''; ?>>Consultation</option>
                                <option value="Follow-up" <?php echo (isset($_GET['type']) && $_GET['type'] === 'Follow-up') ? 'selected' : ''; ?>>Follow-up</option>
                                <option value="Check-up" <?php echo (isset($_GET['type']) && $_GET['type'] === 'Check-up') ? 'selected' : ''; ?>>Check-up</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" value="<?php echo isset($_GET['from_date']) ? htmlspecialchars($_GET['from_date']) : ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="to_date" name="to_date" value="<?php echo isset($_GET['to_date']) ? htmlspecialchars($_GET['to_date']) : ''; ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Records</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo count($medical_records); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Consultations</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            <?php 
                                            echo count(array_filter($medical_records, function($record) {
                                                return $record['type'] === 'Consultation';
                                            }));
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Follow-ups</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            <?php 
                                            echo count(array_filter($medical_records, function($record) {
                                                return $record['type'] === 'Follow-up';
                                            }));
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Check-ups</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            <?php 
                                            echo count(array_filter($medical_records, function($record) {
                                                return $record['type'] === 'Check-up';
                                            }));
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Records List -->
                <div class="row">
                    <?php if (count($filtered_records) > 0): ?>
                        <?php foreach ($filtered_records as $record): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card shadow h-100 record-card position-relative">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 fw-bold text-primary"><?php echo htmlspecialchars($record['diagnosis']); ?></h6>
                                        <span class="badge bg-<?php 
                                            echo $record['type'] === 'Consultation' ? 'success' : 
                                                ($record['type'] === 'Follow-up' ? 'info' : 'warning'); 
                                        ?> record-type-badge"><?php echo htmlspecialchars($record['type']); ?></span>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <p class="mb-1"><i class="fas fa-calendar-alt text-muted me-2"></i> <strong>Date:</strong> <?php echo htmlspecialchars($record['date']); ?></p>
                                            <p class="mb-1"><i class="fas fa-user-md text-muted me-2"></i> <strong>Doctor:</strong> <?php echo htmlspecialchars($record['doctor']); ?> (<?php echo htmlspecialchars($record['specialty']); ?>)</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="mb-1"><strong>Treatment:</strong> <?php echo htmlspecialchars($record['treatment']); ?></p>
                                            <p class="mb-1"><strong>Notes:</strong> <?php echo htmlspecialchars($record['notes']); ?></p>
                                        </div>
                                        <div class="mt-3">
                                            <strong>Documents:</strong>
                                            <div class="mt-2">
                                                <?php foreach ($record['documents'] as $document): ?>
                                                    <a href="#" class="document-link">
                                                        <i class="fas fa-file-medical"></i> <?php echo htmlspecialchars($document); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewRecordModal<?php echo $record['id']; ?>">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#downloadRecordModal<?php echo $record['id']; ?>">
                                            <i class="fas fa-download me-1"></i> Download
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center" role="alert">
                                <i class="fas fa-info-circle me-2"></i> No medical records found matching your search criteria. Please try different filters.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- View Record Modal (Example for first record) -->
    <div class="modal fade" id="viewRecordModal1" tabindex="-1" aria-labelledby="viewRecordModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRecordModalLabel1">Medical Record Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Basic Information</h6>
                            <p class="mb-1"><strong>Record ID:</strong> #MR1001</p>
                            <p class="mb-1"><strong>Date:</strong> May 15, 2023</p>
                            <p class="mb-1"><strong>Record Type:</strong> <span class="badge bg-success">Consultation</span></p>
                            <p class="mb-1"><strong>Location:</strong> Main Hospital, Room 205</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Healthcare Provider</h6>
                            <p class="mb-1"><strong>Doctor:</strong> Dr. John Williams</p>
                            <p class="mb-1"><strong>Specialty:</strong> Cardiology</p>
                            <p class="mb-1"><strong>Department:</strong> Cardiovascular Medicine</p>
                            <p class="mb-1"><strong>Contact:</strong> (555) 123-4567</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="fw-bold">Clinical Information</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Chief Complaint</th>
                                        <td>Persistent headaches and elevated blood pressure</td>
                                    </tr>
                                    <tr>
                                        <th>Symptoms</th>
                                        <td>Headaches, dizziness, occasional chest discomfort</td>
                                    </tr>
                                    <tr>
                                        <th>Vital Signs</th>
                                        <td>
                                            <p class="mb-1">Blood Pressure: 145/95 mmHg</p>
                                            <p class="mb-1">Heart Rate: 82 bpm</p>
                                            <p class="mb-1">Respiratory Rate: 16 breaths/min</p>
                                            <p class="mb-0">Temperature: 98.6°F</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Diagnosis</th>
                                        <td>Hypertension (Essential/Primary)</td>
                                    </tr>
                                    <tr>
                                        <th>Treatment Plan</th>
                                        <td>
                                            <p class="mb-1"><strong>Medication:</strong> Lisinopril 10mg once daily</p>
                                            <p class="mb-1"><strong>Lifestyle Changes:</strong> Low sodium diet, regular exercise (30 minutes walking daily), weight management, stress reduction techniques</p>
                                            <p class="mb-0"><strong>Monitoring:</strong> Check blood pressure daily and maintain log</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Follow-up</th>
                                        <td>Schedule follow-up appointment in 1 month to evaluate medication effectiveness and adjust as needed.</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="fw-bold">Documents</h6>
                            <div class="d-flex flex-wrap">
                                <a href="#" class="document-link">
                                    <i class="fas fa-file-medical"></i> Blood Test Results
                                </a>
                                <a href="#" class="document-link">
                                    <i class="fas fa-file-medical"></i> ECG Report
                                </a>
                                <a href="#" class="document-link">
                                    <i class="fas fa-file-prescription"></i> Prescription
                                </a>
                                <a href="#" class="document-link">
                                    <i class="fas fa-file-alt"></i> Doctor's Notes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Download Record</button>
                    <button type="button" class="btn btn-success">Print</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Records Modal -->
    <div class="modal fade" id="requestRecordsModal" tabindex="-1" aria-labelledby="requestRecordsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestRecordsModalLabel">Request Medical Records</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="recordType" class="form-label">Record Type</label>
                            <select class="form-select" id="recordType" required>
                                <option value="" selected disabled>Select record type</option>
                                <option value="Complete Medical History">Complete Medical History</option>
                                <option value="Specific Visit Records">Specific Visit Records</option>
                                <option value="Lab Results">Lab Results</option>
                                <option value="Imaging Reports">Imaging Reports</option>
                                <option value="Specialist Consultations">Specialist Consultations</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dateRange" class="form-label">Date Range</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="date" class="form-control" id="recordFromDate" placeholder="From">
                                </div>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" id="recordToDate" placeholder="To">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="requestReason" class="form-label">Reason for Request</label>
                            <textarea class="form-control" id="requestReason" rows="3" placeholder="Briefly explain why you are requesting these records"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="deliveryMethod" class="form-label">Delivery Method</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="deliveryMethod" id="deliveryEmail" value="email" checked>
                                <label class="form-check-label" for="deliveryEmail">
                                    Email (Secure PDF)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="deliveryMethod" id="deliveryDownload" value="download">
                                <label class="form-check-label" for="deliveryDownload">
                                    Direct Download
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="deliveryMethod" id="deliveryMail" value="mail">
                                <label class="form-check-label" for="deliveryMail">
                                    Physical Mail (Additional charges may apply)
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
    
    <!-- Profile Image Upload Modal -->
    <div class="modal fade" id="profileImageModal" tabindex="-1" aria-labelledby="profileImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileImageModalLabel">Change Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="profile.php" method="post" enctype="multipart/form-data">
                        <div class="text-center mb-4">
                            <img src="<?php echo !empty($profile_image) ? $profile_image : '../img/patient-avatar.jpg'; ?>" alt="Current Profile" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <div class="mb-3">
                            <label for="profile_image" class="form-label">Select New Image</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" required>
                            <div class="form-text">Recommended size: 300x300 pixels. Max file size: 2MB.</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="update_profile_image" class="btn btn-primary">Upload New Image</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>