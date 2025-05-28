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

// Placeholder payment data (in a real system, you would fetch this from the database)
$payments = [
    [
        'id' => 'INV-20230601',
        'patient_name' => 'Jane Smith',
        'patient_id' => 1,
        'date' => '2023-06-01',
        'service' => 'General Consultation',
        'amount' => 150.00,
        'insurance' => 'Blue Cross',
        'status' => 'paid',
        'payment_date' => '2023-06-05',
        'payment_method' => 'Credit Card'
    ],
    [
        'id' => 'INV-20230528',
        'patient_name' => 'Robert Johnson',
        'patient_id' => 2,
        'date' => '2023-05-28',
        'service' => 'Follow-up Appointment',
        'amount' => 80.00,
        'insurance' => 'Medicare',
        'status' => 'paid',
        'payment_date' => '2023-05-30',
        'payment_method' => 'Insurance'
    ],
    [
        'id' => 'INV-20230602',
        'patient_name' => 'Emily Williams',
        'patient_id' => 3,
        'date' => '2023-06-02',
        'service' => 'Prenatal Check-up',
        'amount' => 200.00,
        'insurance' => 'Aetna',
        'status' => 'paid',
        'payment_date' => '2023-06-03',
        'payment_method' => 'Debit Card'
    ],
    [
        'id' => 'INV-20230610',
        'patient_name' => 'Michael Brown',
        'patient_id' => 4,
        'date' => '2023-06-10',
        'service' => 'Routine Check-up',
        'amount' => 120.00,
        'insurance' => 'United Healthcare',
        'status' => 'pending',
        'payment_date' => null,
        'payment_method' => null
    ],
    [
        'id' => 'INV-20230612',
        'patient_name' => 'Sarah Thompson',
        'patient_id' => 5,
        'date' => '2023-06-12',
        'service' => 'Asthma Treatment',
        'amount' => 175.00,
        'insurance' => 'Cigna',
        'status' => 'pending',
        'payment_date' => null,
        'payment_method' => null
    ],
    [
        'id' => 'INV-20230614',
        'patient_name' => 'David Wilson',
        'patient_id' => 6,
        'date' => '2023-06-14',
        'service' => 'Post-Surgery Follow-up',
        'amount' => 90.00,
        'insurance' => 'Blue Shield',
        'status' => 'processing',
        'payment_date' => null,
        'payment_method' => 'Insurance'
    ]
];

// Calculate statistics
$total_paid = 0;
$total_pending = 0;
$total_processing = 0;
$total_amount = 0;
$payment_methods = [];

foreach ($payments as $payment) {
    $total_amount += $payment['amount'];
    
    if ($payment['status'] === 'paid') {
        $total_paid += $payment['amount'];
    } else if ($payment['status'] === 'pending') {
        $total_pending += $payment['amount'];
    } else if ($payment['status'] === 'processing') {
        $total_processing += $payment['amount'];
    }
    
    if (!empty($payment['payment_method'])) {
        if (!isset($payment_methods[$payment['payment_method']])) {
            $payment_methods[$payment['payment_method']] = 0;
        }
        $payment_methods[$payment['payment_method']]++;
    }
}

// Monthly earnings data for chart
$monthly_earnings = [
    'Jan' => 2450,
    'Feb' => 2850,
    'Mar' => 3100,
    'Apr' => 2750,
    'May' => 3200,
    'Jun' => 1800, // Current month (partial)
    'Jul' => 0,
    'Aug' => 0,
    'Sep' => 0,
    'Oct' => 0,
    'Nov' => 0,
    'Dec' => 0
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments | Doctor Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <style>
        .payment-status-badge.paid {
            background-color: #1cc88a;
        }
        .payment-status-badge.pending {
            background-color: #f6c23e;
        }
        .payment-status-badge.processing {
            background-color: #36b9cc;
        }
        .payment-status-badge.declined {
            background-color: #e74a3b;
        }
    </style>
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
                <li class="nav-item active">
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
        <div class="dashboard-content col-lg-12 col-12">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggle" class="btn btn-link d-md-none rounded-circle me-3">
                    <i class="fas fa-bars"></i>
                </button>
                
                <ul class="navbar-nav ms-auto">
                    <!-- Notifications -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell fa-fw"></i>
                            <span class="badge bg-danger badge-counter">3+</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">Notifications Center</h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-money-bill text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">Today</div>
                                    <span>You have a new payment from Emily Williams</span>
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-primary" href="#">Show All Notifications</a>
                        </div>
                    </li>

                    <div class="topbar-divider d-none d-sm-block"></div>

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
                    <h1 class="h3 mb-0 text-gray-800">Payments & Billing</h1>
                    <div>
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                            <i class="fas fa-plus fa-sm me-2"></i> Create Invoice
                        </a>
                        <a href="#" class="btn btn-info btn-sm shadow-sm ms-2" onclick="window.print();">
                            <i class="fas fa-print fa-sm me-2"></i> Print
                        </a>
                    </div>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <!-- Total Earnings Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Earnings (YTD)</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">$<?php echo number_format(array_sum($monthly_earnings), 2); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payments Received Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Payments Received</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">$<?php echo number_format($total_paid, 2); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Payments Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Pending Payments</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">$<?php echo number_format($total_pending, 2); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Insurance Claims Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Processing</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">$<?php echo number_format($total_processing, 2); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-sync-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Monthly Earnings Chart -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 fw-bold text-primary">Monthly Earnings (2023)</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">View Options:</div>
                                        <a class="dropdown-item" href="#">Weekly</a>
                                        <a class="dropdown-item" href="#">Monthly</a>
                                        <a class="dropdown-item" href="#">Yearly</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Export Data</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="earningsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods Chart -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">Payment Methods</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="paymentMethodsChart"></canvas>
                                </div>
                                <div class="mt-4 text-center small">
                                    <span class="me-2">
                                        <i class="fas fa-circle text-primary"></i> Insurance
                                    </span>
                                    <span class="me-2">
                                        <i class="fas fa-circle text-success"></i> Credit Card
                                    </span>
                                    <span class="me-2">
                                        <i class="fas fa-circle text-info"></i> Debit Card
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Records Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">Recent Transactions</h6>
                        <div>
                            <button class="btn btn-sm btn-outline-primary me-2" id="filterBtn">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-sort me-1"></i> Sort By
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">Date (Newest)</a></li>
                                    <li><a class="dropdown-item" href="#">Date (Oldest)</a></li>
                                    <li><a class="dropdown-item" href="#">Amount (High to Low)</a></li>
                                    <li><a class="dropdown-item" href="#">Amount (Low to High)</a></li>
                                    <li><a class="dropdown-item" href="#">Status</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="paymentsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Invoice ID</th>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Insurance</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($payment['id']); ?></td>
                                        <td><?php echo htmlspecialchars($payment['patient_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($payment['date'])); ?></td>
                                        <td><?php echo htmlspecialchars($payment['service']); ?></td>
                                        <td>$<?php echo number_format($payment['amount'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($payment['insurance']); ?></td>
                                        <td>
                                            <span class="badge payment-status-badge <?php echo $payment['status']; ?>">
                                                <?php echo ucfirst(htmlspecialchars($payment['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary view-invoice" data-bs-toggle="modal" data-bs-target="#viewInvoiceModal" data-invoice-id="<?php echo $payment['id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-info print-invoice" onclick="printInvoice(<?php echo $payment['id']; ?>)">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                                <?php if ($payment['status'] === 'pending'): ?>
                                                <button type="button" class="btn btn-sm btn-success approve-payment" onclick="approvePayment(<?php echo $payment['id']; ?>)">
                                                    <i class="fas fa-check"></i>
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
                
                <!-- Printable Invoice Templates -->
                <div style="display: none;">
                    <?php foreach ($payments as $payment): ?>
                    <div id="printable-invoice-<?php echo $payment['id']; ?>">
                        <div class="text-center mb-4">
                            <h3>HealthConnect Medical Center</h3>
                            <p class="mb-0">456 Medical Avenue, Healthville, CA 67890</p>
                            <p class="mb-0">Phone: (555) 123-4567 | Email: info@healthconnect.com</p>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-6">
                                <h5 class="border-bottom pb-2 mb-2">Invoice #INV-<?php echo htmlspecialchars($payment['id']); ?></h5>
                                <p class="mb-1"><strong>Date:</strong> <?php echo date('M d, Y', strtotime($payment['date'])); ?></p>
                                <p class="mb-1"><strong>Status:</strong> <?php echo ucfirst(htmlspecialchars($payment['status'])); ?></p>
                            </div>
                            <div class="col-6 text-end">
                                <h5 class="border-bottom pb-2 mb-2">Patient Information</h5>
                                <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($payment['patient_name']); ?></p>
                                <p class="mb-1"><strong>Insurance:</strong> <?php echo htmlspecialchars($payment['insurance']); ?></p>
                            </div>
                        </div>
                        
                        <div class="prescription-content mb-4">
                            <h5 class="border-bottom pb-2 mb-3">Service Details</h5>
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Service:</strong></div>
                                <div class="col-md-8"><?php echo htmlspecialchars($payment['service']); ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4"><strong>Amount:</strong></div>
                                <div class="col-md-8">$<?php echo number_format($payment['amount'], 2); ?></div>
                            </div>
                        </div>
                        
                        <div class="row mt-5">
                            <div class="col-6">
                                <p class="small">Thank you for choosing HealthConnect Medical Center</p>
                            </div>
                            <div class="col-6 text-end">
                                <p class="border-top pt-2 mt-4">Doctor's Signature</p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Invoice Modal -->
    <div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createInvoiceModalLabel">Create New Invoice</h5>
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
                                <label for="invoiceDate" class="form-label">Invoice Date</label>
                                <input type="date" class="form-control" id="invoiceDate" required value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="serviceType" class="form-label">Service Type</label>
                                <select class="form-select" id="serviceType" required>
                                    <option value="" selected disabled>Select Service</option>
                                    <option value="consultation">General Consultation</option>
                                    <option value="follow_up">Follow-up Appointment</option>
                                    <option value="procedure">Medical Procedure</option>
                                    <option value="test">Medical Test</option>
                                    <option value="other">Other Service</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="amount" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="insuranceProvider" class="form-label">Insurance Provider</label>
                                <select class="form-select" id="insuranceProvider">
                                    <option value="" selected disabled>Select Insurance</option>
                                    <option value="blue_cross">Blue Cross</option>
                                    <option value="aetna">Aetna</option>
                                    <option value="medicare">Medicare</option>
                                    <option value="united">United Healthcare</option>
                                    <option value="cigna">Cigna</option>
                                    <option value="none">None/Self-Pay</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="dueDate" class="form-label">Payment Due Date</label>
                                <input type="date" class="form-control" id="dueDate" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="serviceDescription" class="form-label">Service Description</label>
                            <textarea class="form-control" id="serviceDescription" rows="3" placeholder="Describe the services provided"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="additionalNotes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="additionalNotes" rows="2" placeholder="Any additional information for the invoice"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Create Invoice</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Invoice Modal -->
    <div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-labelledby="viewInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewInvoiceModalLabel">Invoice Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="invoice-header d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="mb-1">Invoice #INV-20230601</h4>
                            <p class="mb-0 text-muted">Date: June 1, 2023</p>
                        </div>
                        <div class="text-end">
                            <img src="../img/logo.png" alt="HealthConnect" height="50">
                            <p class="mb-0 mt-2">HealthConnect Medical Center</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Bill To:</h6>
                            <p class="mb-1">Jane Smith</p>
                            <p class="mb-1">123 Main Street</p>
                            <p class="mb-1">Anytown, CA 12345</p>
                            <p class="mb-0">Patient ID: PT-001</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="fw-bold">From:</h6>
                            <p class="mb-1">Dr. <?php echo htmlspecialchars($doctor_name); ?></p>
                            <p class="mb-1">HealthConnect Medical Center</p>
                            <p class="mb-1">456 Medical Avenue</p>
                            <p class="mb-0">Healthville, CA 67890</p>
                        </div>
                    </div>
                    
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Service</th>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>General Consultation</td>
                                    <td>Initial medical consultation and examination</td>
                                    <td class="text-end">$150.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Subtotal</td>
                                    <td class="text-end">$150.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Insurance Coverage (Blue Cross)</td>
                                    <td class="text-end">-$120.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Total Due</td>
                                    <td class="text-end fw-bold">$30.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="invoice-footer">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="fw-bold">Payment Information:</h6>
                                <p class="mb-1">Payment Due By: June 15, 2023</p>
                                <p class="mb-3">Payment Method: Credit Card</p>
                                
                                <h6 class="fw-bold">Notes:</h6>
                                <p class="mb-0">Please contact our billing department at billing@healthconnect.com or (555) 123-4567 if you have any questions about this invoice.</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="py-3 px-4 border rounded mt-3">
                                    <h6 class="fw-bold mb-2">Payment Status:</h6>
                                    <span class="badge bg-success fs-6 d-block p-2">PAID</span>
                                    <p class="small mt-2 mb-0">Received on: June 5, 2023</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info"><i class="fas fa-print me-1"></i> Print Invoice</button>
                    <button type="button" class="btn btn-primary"><i class="fas fa-envelope me-1"></i> Email Invoice</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.1.3 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom JS -->
    <script src="../js/main.js"></script>
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('#paymentsTable').DataTable({
                responsive: true,
                order: [[2, 'desc']] // Sort by date (newest first)
            });
        });

        // Monthly Earnings Chart
        var earningsCtx = document.getElementById('earningsChart').getContext('2d');
        var earningsChart = new Chart(earningsCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Earnings ($)',
                    data: [<?php echo implode(',', array_values($monthly_earnings)); ?>],
                    backgroundColor: 'rgba(78, 115, 223, 0.6)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Payment Methods Chart
        var methodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
        var methodsChart = new Chart(methodsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Insurance', 'Credit Card', 'Debit Card'],
                datasets: [{
                    data: [65, 25, 10],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                    hoverBorderColor: 'rgba(234, 236, 244, 1)',
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%',
            }
        });

        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
        });
        
        // Function to view invoice details
        $('.view-invoice').on('click', function() {
            const invoiceId = $(this).data('invoice-id');
            // In a real application, you would fetch the invoice details from the server
            console.log('Viewing invoice:', invoiceId);
            
            // Update the modal title with the invoice ID
            $('#viewInvoiceModalLabel').text('Invoice Details - #' + invoiceId);
            
            // You would typically load the invoice details via AJAX here
            // For now, we're just showing the modal with sample data
        });
        
        // Function to print an invoice - simple approach
        function printInvoice(invoiceId) {
            // Find the payment data
            let row = document.querySelector(`tr:has(button[onclick*="printInvoice(${invoiceId})"])`); 
            let patientName = row.cells[1].textContent.trim();
            let date = row.cells[2].textContent.trim();
            let service = row.cells[3].textContent.trim();
            let amount = row.cells[4].textContent.trim();
            let insurance = row.cells[5].textContent.trim();
            let status = row.cells[6].textContent.trim();
            
            // Create invoice HTML
            let invoiceHTML = `
                <html>
                <head>
                    <title>Invoice #${invoiceId}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .invoice-info { display: flex; justify-content: space-between; margin-bottom: 30px; }
                        .invoice-details { border: 1px solid #ddd; padding: 15px; margin-bottom: 30px; }
                        .row { display: flex; margin-bottom: 10px; }
                        .label { font-weight: bold; width: 150px; }
                        .signature { margin-top: 50px; text-align: right; }
                        .signature-line { border-top: 1px solid #000; display: inline-block; width: 200px; }
                        @media print { body { margin: 0; padding: 15px; } }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>HealthConnect Medical Center</h2>
                        <p>456 Medical Avenue, Healthville, CA 67890</p>
                        <p>Phone: (555) 123-4567 | Email: info@healthconnect.com</p>
                    </div>
                    
                    <div class="invoice-info">
                        <div>
                            <h3>Invoice #${invoiceId}</h3>
                            <p>Date: ${date}</p>
                        </div>
                        <div>
                            <h3>Patient Information</h3>
                            <p>Name: ${patientName}</p>
                            <p>Insurance: ${insurance}</p>
                        </div>
                    </div>
                    
                    <div class="invoice-details">
                        <h3>Service Details</h3>
                        <div class="row">
                            <div class="label">Service:</div>
                            <div>${service}</div>
                        </div>
                        <div class="row">
                            <div class="label">Amount:</div>
                            <div>${amount}</div>
                        </div>
                        <div class="row">
                            <div class="label">Status:</div>
                            <div>${status}</div>
                        </div>
                    </div>
                    
                    <div class="signature">
                        <p>Thank you for choosing HealthConnect Medical Center</p>
                        <p class="signature-line">Doctor's Signature</p>
                    </div>
                </body>
                </html>
            `;
            
            // Open a new window and write the invoice HTML
            let printWindow = window.open('', '_blank', 'width=800,height=600');
            printWindow.document.write(invoiceHTML);
            printWindow.document.close();
            
            // Print after the content is loaded
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
                setTimeout(() => printWindow.close(), 500);
            };
        }
        
        // Function to approve a payment
        function approvePayment(invoiceId) {
            // In a real application, you would send an AJAX request to update the payment status
            console.log('Approving payment:', invoiceId);
            
            if (confirm('Are you sure you want to approve this payment?')) {
                // Find the row with this invoice ID
                const row = $(`#paymentsTable tr:has(td:contains(${invoiceId}))`).first();
                
                if (row.length) {
                    // Update the status badge
                    const statusCell = row.find('td:nth-child(7)');
                    statusCell.html('<span class="badge payment-status-badge paid">Paid</span>');
                    
                    // Remove the approve button
                    row.find('.approve-payment').remove();
                    
                    // Show success message
                    alert('Payment approved successfully!');
                }
            }
        }
    </script>
</body>
</html>