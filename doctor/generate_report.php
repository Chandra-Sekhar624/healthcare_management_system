<?php
// Start session
session_start();

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get doctor information
$doctor_id = $_SESSION['user_id'];
$doctor_name = $_SESSION['user_name'];

// Initialize variables
$report_type = isset($_GET['type']) ? $_GET['type'] : 'appointments';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d', strtotime('-30 days'));
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');
$report_generated = false;
$report_data = [];

// Handle report generation
if (isset($_POST['generate'])) {
    $report_type = $_POST['report_type'];
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $report_generated = true;
    
    // In a real application, you would fetch this data from the database
    // For now, we'll use sample data
    if ($report_type == 'appointments') {
        $report_data = [
            [
                'id' => 1,
                'patient_name' => 'Jane Smith',
                'date' => '2023-06-15',
                'time' => '10:00 AM',
                'status' => 'completed',
                'notes' => 'Regular checkup, patient is doing well'
            ],
            [
                'id' => 2,
                'patient_name' => 'Robert Johnson',
                'date' => '2023-06-15',
                'time' => '11:30 AM',
                'status' => 'completed',
                'notes' => 'Follow-up appointment for medication adjustment'
            ],
            [
                'id' => 3,
                'patient_name' => 'Emily Williams',
                'date' => '2023-06-16',
                'time' => '09:15 AM',
                'status' => 'completed',
                'notes' => 'New patient consultation'
            ],
            [
                'id' => 4,
                'patient_name' => 'Michael Brown',
                'date' => '2023-06-18',
                'time' => '2:00 PM',
                'status' => 'completed',
                'notes' => 'Post-surgery follow-up'
            ],
            [
                'id' => 5,
                'patient_name' => 'Sarah Davis',
                'date' => '2023-06-20',
                'time' => '3:30 PM',
                'status' => 'completed',
                'notes' => 'Annual physical examination'
            ]
        ];
    } elseif ($report_type == 'patients') {
        $report_data = [
            [
                'id' => 1,
                'name' => 'Jane Smith',
                'age' => 35,
                'gender' => 'Female',
                'last_visit' => '2023-06-01',
                'condition' => 'Hypertension',
                'visits_count' => 5
            ],
            [
                'id' => 2,
                'name' => 'Robert Johnson',
                'age' => 42,
                'gender' => 'Male',
                'last_visit' => '2023-05-28',
                'condition' => 'Diabetes',
                'visits_count' => 8
            ],
            [
                'id' => 3,
                'name' => 'Emily Williams',
                'age' => 29,
                'gender' => 'Female',
                'last_visit' => '2023-05-15',
                'condition' => 'Pregnancy',
                'visits_count' => 3
            ],
            [
                'id' => 4,
                'name' => 'Michael Brown',
                'age' => 55,
                'gender' => 'Male',
                'last_visit' => '2023-06-18',
                'condition' => 'Post-surgery recovery',
                'visits_count' => 2
            ],
            [
                'id' => 5,
                'name' => 'Sarah Davis',
                'age' => 48,
                'gender' => 'Female',
                'last_visit' => '2023-06-20',
                'condition' => 'Annual checkup',
                'visits_count' => 1
            ]
        ];
    } elseif ($report_type == 'income') {
        $report_data = [
            [
                'date' => '2023-06-01',
                'patient_name' => 'Jane Smith',
                'service' => 'Regular Checkup',
                'amount' => 150.00
            ],
            [
                'date' => '2023-06-02',
                'patient_name' => 'Robert Johnson',
                'service' => 'Consultation',
                'amount' => 200.00
            ],
            [
                'date' => '2023-06-05',
                'patient_name' => 'Emily Williams',
                'service' => 'Prenatal Checkup',
                'amount' => 250.00
            ],
            [
                'date' => '2023-06-10',
                'patient_name' => 'Michael Brown',
                'service' => 'Post-surgery Checkup',
                'amount' => 180.00
            ],
            [
                'date' => '2023-06-15',
                'patient_name' => 'Sarah Davis',
                'service' => 'Annual Physical',
                'amount' => 300.00
            ],
            [
                'date' => '2023-06-18',
                'patient_name' => 'David Wilson',
                'service' => 'Consultation',
                'amount' => 200.00
            ],
            [
                'date' => '2023-06-20',
                'patient_name' => 'Lisa Martinez',
                'service' => 'Regular Checkup',
                'amount' => 150.00
            ]
        ];
    }
}

// Function to export data to CSV
function exportToCSV($data, $filename) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Output header row
    fputcsv($output, array_keys($data[0]));
    
    // Output data rows
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}

// Handle CSV export
if (isset($_POST['export_csv']) && !empty($report_data)) {
    $filename = $report_type . '_report_' . date('Y-m-d') . '.csv';
    exportToCSV($report_data, $filename);
}

// Handle PDF export
if (isset($_POST['export_pdf']) && !empty($report_data)) {
    // In a real application, you would use a library like FPDF or TCPDF to generate PDFs
    // For this example, we'll just redirect back with a message
    header('Location: generate_report.php?pdf_generated=1&type=' . $report_type . '&date_from=' . $date_from . '&date_to=' . $date_to);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report | Doctor Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
</head>
<body class="dashboard-body">
    <!-- Sidebar and Navigation -->
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="dashboard-sidebar bg-dark text-white">
            <div class="sidebar-header p-3 text-center">
                <a href="../index.php" class="sidebar-brand text-decoration-none">
                    <i class="fas fa-heartbeat text-primary me-2"></i>
                    <span class="fw-bold fs-4 text-white">HealthConnect</span>
                </a>
            </div>
            <hr class="sidebar-divider">
            <div class="sidebar-user text-center mb-4">
                <div class="user-avatar mb-3">
                    <?php
                    // Display the uploaded image if available, otherwise show default
                    $profile_image = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : '../img/doctor-avatar.jpg';
                    ?>
                    <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Doctor" class="rounded-circle">
                </div>
                <div class="user-info">
                    <h6 class="mb-0"><?php echo htmlspecialchars($doctor_name); ?></h6>
                    <span class="text-muted small">Doctor</span>
                </div>
            </div>
            <ul class="sidebar-nav list-unstyled">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="appointment.php">
                        <i class="fas fa-calendar-check me-2"></i> Appointments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my-patients.php">
                        <i class="fas fa-user-injured me-2"></i> My Patients
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="medical-records.php">
                        <i class="fas fa-notes-medical me-2"></i> Medical Records
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="prescription.php">
                        <i class="fas fa-prescription me-2"></i> Prescriptions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="schedule.php">
                        <i class="fas fa-clock me-2"></i> Schedule
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reviews.php">
                        <i class="fas fa-star me-2"></i> Reviews
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="payments.php">
                        <i class="fas fa-money-bill-wave me-2"></i> Payments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">
                        <i class="fas fa-cog me-2"></i> Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="dashboard-content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggle" class="btn btn-link d-md-none rounded-circle me-3">
                    <i class="fas fa-bars"></i>
                </button>
                
                <ul class="navbar-nav ms-auto">
                    <!-- User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($doctor_name); ?></span>
                            <img class="img-profile rounded-circle" src="<?php echo htmlspecialchars(isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : '../img/doctor-avatar.jpg'); ?>">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i> Profile
                            </a>
                            <a class="dropdown-item" href="settings.php">
                                <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../logout.php">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Generate Report</h1>
                    <a href="index.php" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Dashboard
                    </a>
                </div>

                <?php if (isset($_GET['pdf_generated'])): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i> PDF report has been generated and is ready for download.
                </div>
                <?php endif; ?>

                <!-- Report Generation Form -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Report Parameters</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="generate_report.php">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="report_type" class="form-label">Report Type</label>
                                    <select class="form-select" id="report_type" name="report_type">
                                        <option value="appointments" <?php echo $report_type == 'appointments' ? 'selected' : ''; ?>>Appointments Report</option>
                                        <option value="patients" <?php echo $report_type == 'patients' ? 'selected' : ''; ?>>Patients Report</option>
                                        <option value="income" <?php echo $report_type == 'income' ? 'selected' : ''; ?>>Income Report</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="date_from" class="form-label">Date From</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo $date_from; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="date_to" class="form-label">Date To</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo $date_to; ?>">
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" name="generate" class="btn btn-primary">
                                    <i class="fas fa-file-alt me-1"></i> Generate Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($report_generated && !empty($report_data)): ?>
                <!-- Report Results -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">
                            <?php 
                            $report_title = '';
                            switch ($report_type) {
                                case 'appointments':
                                    $report_title = 'Appointments Report';
                                    break;
                                case 'patients':
                                    $report_title = 'Patients Report';
                                    break;
                                case 'income':
                                    $report_title = 'Income Report';
                                    break;
                            }
                            echo $report_title;
                            ?> 
                            (<?php echo date('M d, Y', strtotime($date_from)); ?> - <?php echo date('M d, Y', strtotime($date_to)); ?>)
                        </h6>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="printReport()">
                                <i class="fas fa-print me-1"></i> Print Report
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead class="bg-light">
                                    <tr>
                                        <?php if ($report_type == 'appointments'): ?>
                                            <th>ID</th>
                                            <th>Patient Name</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Notes</th>
                                        <?php elseif ($report_type == 'patients'): ?>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Last Visit</th>
                                            <th>Condition</th>
                                            <th>Total Visits</th>
                                        <?php elseif ($report_type == 'income'): ?>
                                            <th>Date</th>
                                            <th>Patient Name</th>
                                            <th>Service</th>
                                            <th>Amount</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($report_data as $row): ?>
                                        <tr>
                                            <?php if ($report_type == 'appointments'): ?>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                                <td><?php echo $row['time']; ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $row['status'] == 'completed' ? 'success' : ($row['status'] == 'cancelled' ? 'danger' : 'warning'); ?>">
                                                        <?php echo ucfirst($row['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['notes']); ?></td>
                                            <?php elseif ($report_type == 'patients'): ?>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                <td><?php echo $row['age']; ?></td>
                                                <td><?php echo $row['gender']; ?></td>
                                                <td><?php echo date('M d, Y', strtotime($row['last_visit'])); ?></td>
                                                <td><?php echo htmlspecialchars($row['condition']); ?></td>
                                                <td><?php echo $row['visits_count']; ?></td>
                                            <?php elseif ($report_type == 'income'): ?>
                                                <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                                                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['service']); ?></td>
                                                <td>$<?php echo number_format($row['amount'], 2); ?></td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                    
                                    <?php if ($report_type == 'income'): ?>
                                    <tr class="fw-bold bg-light">
                                        <td colspan="3" class="text-end">Total:</td>
                                        <td>
                                            $<?php 
                                            $total = array_sum(array_column($report_data, 'amount'));
                                            echo number_format($total, 2); 
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if ($report_type == 'appointments' || $report_type == 'patients'): ?>
                        <div class="mt-4">
                            <h6 class="fw-bold">Summary</h6>
                            <div class="row">
                                <?php if ($report_type == 'appointments'): ?>
                                    <div class="col-md-3 mb-3">
                                        <div class="card border-left-primary shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Appointments</div>
                                                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo count($report_data); ?></div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($report_type == 'patients'): ?>
                                    <div class="col-md-3 mb-3">
                                        <div class="card border-left-primary shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Patients</div>
                                                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo count($report_data); ?></div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-user-injured fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="card border-left-success shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Visits</div>
                                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                                            <?php echo array_sum(array_column($report_data, 'visits_count')); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php elseif ($report_generated): ?>
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i> No data found for the selected parameters.
                </div>
                <?php endif; ?>
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
        
        // Function to print the report
        function printReport() {
            // Store the original contents
            const originalContents = document.body.innerHTML;
            
            // Get the report title and date range
            const reportTitle = document.querySelector('.card-header .fw-bold').textContent.trim();
            
            // Get the table content
            const tableContent = document.querySelector('.table-responsive').innerHTML;
            
            // Create a print-friendly version
            const printContent = `
                <div style="padding: 20px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h2>HealthConnect Medical Center</h2>
                        <h3>${reportTitle}</h3>
                    </div>
                    ${tableContent}
                    <div style="margin-top: 30px; text-align: right;">
                        <p>Generated on: ${new Date().toLocaleDateString()}</p>
                        <p style="margin-top: 50px; border-top: 1px solid #000; display: inline-block; padding-top: 10px;">Doctor's Signature</p>
                    </div>
                </div>
            `;
            
            // Replace the body content with our print content
            document.body.innerHTML = printContent;
            
            // Print the document
            window.print();
            
            // Restore the original content after printing
            setTimeout(() => {
                document.body.innerHTML = originalContents;
                // Reinitialize event listeners
                document.getElementById('sidebarToggle').addEventListener('click', function() {
                    document.querySelector('.dashboard-sidebar').classList.toggle('show');
                });
            }, 1000);
        }
    </script>
</body>
</html>
