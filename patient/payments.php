<?php
// Start session
session_start();

// Include database configuration
// include '../includes/config.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get patient information
$patient_id = $_SESSION['user_id'];
$patient_name = $_SESSION['user_name'];

// Placeholder data for billing history
$billing_history = [
    [
        'id' => 'INV-2023-001',
        'date' => '2023-06-10',
        'description' => 'Consultation with Dr. John Williams',
        'amount' => 150.00,
        'status' => 'Paid',
        'payment_method' => 'Credit Card (ending in 4123)'
    ],
    [
        'id' => 'INV-2023-002',
        'date' => '2023-06-15',
        'description' => 'Blood Test - Comprehensive Panel',
        'amount' => 85.00,
        'status' => 'Paid',
        'payment_method' => 'Insurance'
    ],
    [
        'id' => 'INV-2023-003',
        'date' => '2023-06-25',
        'description' => 'Follow-up with Dr. Emily Rodriguez',
        'amount' => 120.00,
        'status' => 'Pending',
        'payment_method' => 'Unpaid'
    ],
    [
        'id' => 'INV-2023-004',
        'date' => '2023-07-05',
        'description' => 'Prescription Refill - Lisinopril',
        'amount' => 45.00,
        'status' => 'Pending',
        'payment_method' => 'Unpaid'
    ]
];

// Placeholder data for upcoming payments
$upcoming_payments = [
    [
        'id' => 'INV-2023-003',
        'due_date' => '2023-07-10',
        'description' => 'Follow-up with Dr. Emily Rodriguez',
        'amount' => 120.00
    ],
    [
        'id' => 'INV-2023-004',
        'due_date' => '2023-07-15',
        'description' => 'Prescription Refill - Lisinopril',
        'amount' => 45.00
    ]
];

// Placeholder data for saved payment methods
$payment_methods = [
    [
        'id' => 1,
        'type' => 'Credit Card',
        'name' => 'Visa ending in 4123',
        'exp_date' => '05/25',
        'is_default' => true
    ],
    [
        'id' => 2,
        'type' => 'Bank Account',
        'name' => 'Checking account ending in 7890',
        'exp_date' => null,
        'is_default' => false
    ]
];

// Calculate totals
$total_due = array_sum(array_column($upcoming_payments, 'amount'));
$total_paid_this_month = 0;
foreach ($billing_history as $bill) {
    if ($bill['status'] === 'Paid' && strtotime($bill['date']) >= strtotime('first day of this month')) {
        $total_paid_this_month += $bill['amount'];
    }
}

// Process payment form (in a real application, you would process payments securely)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['make_payment'])) {
    // This is just a placeholder for demonstration
    $invoice_id = $_POST['invoice_id'];
    $payment_method = $_POST['payment_method'];
    
    // In a real application, you would:
    // 1. Validate the payment information
    // 2. Process the payment through a payment gateway
    // 3. Update the database
    // 4. Send a confirmation email
    
    // For this demo, we'll just redirect with a success message
    header('Location: payments.php?payment_success=1&invoice=' . $invoice_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments | Patient Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <style>
        .payment-summary-card {
            border-left: 4px solid #4e73df;
            border-radius: 4px;
        }
        .payment-method-card {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .payment-method-card:hover {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .payment-method-card.selected {
            border-color: #4e73df;
            background-color: #f8f9ff;
        }
        .badge-paid {
            background-color: #1cc88a;
        }
        .badge-pending {
            background-color: #f6c23e;
        }
        .badge-overdue {
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
                    <img src="../img/patient-avatar.jpg" alt="Patient" class="rounded-circle">
                </div>
                <div class="user-info">
                    <h6 class="mb-0"><?php echo htmlspecialchars($patient_name); ?></h6>
                    <span class="text-muted small">Patient</span>
                </div>
            </div>
            <ul class="sidebar-nav list-unstyled">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="appointments.php">
                        <i class="fas fa-calendar-check me-2"></i> My Appointments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="find-doctor.php">
                        <i class="fas fa-user-md me-2"></i> Find Doctor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="medical-records.php">
                        <i class="fas fa-notes-medical me-2"></i> Medical Records
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="prescriptions.php">
                        <i class="fas fa-prescription me-2"></i> Prescriptions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="messages.php">
                        <i class="fas fa-envelope me-2"></i> Messages
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="payments.php">
                        <i class="fas fa-credit-card me-2"></i> Payments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">
                        <i class="fas fa-user me-2"></i> My Profile
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
        <div class="dashboard-content col-12">
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
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-muted">June 12, 2023</div>
                                    <span>A new bill has been generated for your recent visit</span>
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-primary" href="#">Show All Notifications</a>
                        </div>
                    </li>

                    <!-- Messages -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-envelope fa-fw"></i>
                            <span class="badge bg-danger badge-counter">2</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="messagesDropdown">
                            <h6 class="dropdown-header">Message Center</h6>
                            <a class="dropdown-item d-flex align-items-center" href="messages.php">
                                <div class="dropdown-list-image me-3">
                                    <img class="rounded-circle" src="../img/doctor-1.jpg" alt="Doctor">
                                    <div class="status-indicator bg-success"></div>
                                </div>
                                <div class="fw-bold">
                                    <div class="text-truncate">Your lab results are now available.</div>
                                    <div class="small text-muted">Dr. John Williams · 2h</div>
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-primary" href="messages.php">Read More Messages</a>
                        </div>
                    </li>

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($patient_name); ?></span>
                            <img class="img-profile rounded-circle" src="../img/patient-avatar.jpg">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="profile.php">
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
                    <a href="#" class="d-none d-sm-inline-block btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
                        <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Add Payment Method
                    </a>
                </div>

                <!-- Payment Success Alert -->
                <?php if (isset($_GET['payment_success']) && $_GET['payment_success'] == 1): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Payment Successful!</strong> Your payment for invoice #<?php echo htmlspecialchars($_GET['invoice']); ?> has been processed successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <!-- Payment Summary Cards -->
                <div class="row mb-4">
                    <!-- Total Due -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card payment-summary-card h-100 py-2 shadow-sm">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Due</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($total_due, 2); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paid This Month -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card payment-summary-card h-100 py-2 shadow-sm">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Paid This Month</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($total_paid_this_month, 2); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card payment-summary-card h-100 py-2 shadow-sm">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Payment Methods</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($payment_methods); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Insurance Info -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card payment-summary-card h-100 py-2 shadow-sm">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Insurance Coverage</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">80%</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Payments -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Upcoming Payments</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#">Pay All</a>
                                <a class="dropdown-item" href="#">Request Extension</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Download All</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($upcoming_payments)): ?>
                            <div class="text-center p-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="mb-0">You have no upcoming payments. All your bills are paid!</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Due Date</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($upcoming_payments as $payment): ?>
                                            <?php 
                                                $due_date = new DateTime($payment['due_date']);
                                                $current_date = new DateTime();
                                                $days_until_due = $current_date->diff($due_date)->days;
                                                $is_due_soon = $days_until_due <= 3 && $due_date >= $current_date;
                                                $is_overdue = $due_date < $current_date;
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($payment['id']); ?></td>
                                                <td>
                                                    <?php echo date('M d, Y', strtotime($payment['due_date'])); ?>
                                                    <?php if ($is_overdue): ?>
                                                        <span class="badge bg-danger ms-2">Overdue</span>
                                                    <?php elseif ($is_due_soon): ?>
                                                        <span class="badge bg-warning ms-2">Due Soon</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($payment['description']); ?></td>
                                                <td>$<?php echo number_format($payment['amount'], 2); ?></td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal" 
                                                            data-id="<?php echo htmlspecialchars($payment['id']); ?>" 
                                                            data-amount="<?php echo $payment['amount']; ?>" 
                                                            data-description="<?php echo htmlspecialchars($payment['description']); ?>">
                                                        <i class="fas fa-dollar-sign me-1"></i> Pay Now
                                                    </button>
                                                    <a href="#" class="btn btn-outline-secondary btn-sm">
                                                        <i class="far fa-file-alt me-1"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Billing History -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Billing History</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink2">
                                <a class="dropdown-item" href="#">Download All</a>
                                <a class="dropdown-item" href="#">Export to Excel</a>
                                <a class="dropdown-item" href="#">Email Records</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="billingTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment Method</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($billing_history as $bill): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($bill['id']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($bill['date'])); ?></td>
                                            <td><?php echo htmlspecialchars($bill['description']); ?></td>
                                            <td>$<?php echo number_format($bill['amount'], 2); ?></td>
                                            <td>
                                                <?php if ($bill['status'] === 'Paid'): ?>
                                                    <span class="badge badge-paid text-white">Paid</span>
                                                <?php elseif ($bill['status'] === 'Pending'): ?>
                                                    <span class="badge badge-pending text-white">Pending</span>
                                                <?php else: ?>
                                                    <span class="badge badge-overdue text-white">Overdue</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($bill['payment_method']); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-outline-primary">
                                                    <i class="far fa-file-pdf me-1"></i> Receipt
                                                </a>
                                                <?php if ($bill['status'] !== 'Paid'): ?>
                                                    <button class="btn btn-primary btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#paymentModal" 
                                                            data-id="<?php echo htmlspecialchars($bill['id']); ?>" 
                                                            data-amount="<?php echo $bill['amount']; ?>" 
                                                            data-description="<?php echo htmlspecialchars($bill['description']); ?>">
                                                        <i class="fas fa-dollar-sign me-1"></i> Pay
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Payment Methods</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($payment_methods as $method): ?>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="payment-method-card p-3 <?php echo $method['is_default'] ? 'selected' : ''; ?>">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">
                                                <?php if ($method['type'] === 'Credit Card'): ?>
                                                    <i class="fas fa-credit-card me-2 text-primary"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-university me-2 text-primary"></i>
                                                <?php endif; ?>
                                                <?php echo htmlspecialchars($method['name']); ?>
                                            </h6>
                                            <div>
                                                <?php if ($method['is_default']): ?>
                                                    <span class="badge bg-success">Default</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="small text-muted mb-2">
                                            <?php echo htmlspecialchars($method['type']); ?>
                                            <?php if (!empty($method['exp_date'])): ?>
                                                • Expires: <?php echo htmlspecialchars($method['exp_date']); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex">
                                            <?php if (!$method['is_default']): ?>
                                                <a href="#" class="btn btn-sm btn-outline-primary me-2">Set as Default</a>
                                            <?php endif; ?>
                                            <a href="#" class="btn btn-sm btn-outline-danger">Remove</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <!-- Add New Payment Method Card -->
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="payment-method-card p-3 text-center d-flex align-items-center justify-content-center" style="min-height: 150px; border-style: dashed;">
                                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
                                        <i class="fas fa-plus-circle fa-2x text-primary mb-2"></i>
                                        <h6 class="mb-0">Add Payment Method</h6>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Insurance Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Insurance Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Insurance Provider</label>
                                    <p>Blue Cross Blue Shield</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Policy Number</label>
                                    <p>BCBS-123456789</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Group Number</label>
                                    <p>GRP-987654</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Coverage Type</label>
                                    <p>PPO - Preferred Provider Organization</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Effective Date</label>
                                    <p>January 1, 2023</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Primary Policyholder</label>
                                    <p>Self</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Coverage Details</label>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Service Type</th>
                                            <th>Coverage Percentage</th>
                                            <th>Deductible</th>
                                            <th>Out-of-Pocket Maximum</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Primary Care</td>
                                            <td>90%</td>
                                            <td>$500</td>
                                            <td>$3,000</td>
                                        </tr>
                                        <tr>
                                            <td>Specialist</td>
                                            <td>80%</td>
                                            <td>$500</td>
                                            <td>$3,000</td>
                                        </tr>
                                        <tr>
                                            <td>Emergency Room</td>
                                            <td>80%</td>
                                            <td>$500</td>
                                            <td>$3,000</td>
                                        </tr>
                                        <tr>
                                            <td>Prescription Drugs</td>
                                            <td>70%</td>
                                            <td>$250</td>
                                            <td>$1,500</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="text-end">
                            <a href="#" class="btn btn-outline-primary">View Insurance Card</a>
                            <a href="#" class="btn btn-outline-secondary">Update Insurance Information</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Make Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="payments.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="invoice_id" id="invoiceId">
                        
                        <div class="mb-3">
                            <label for="paymentDescription" class="form-label">Description</label>
                            <input type="text" class="form-control" id="paymentDescription" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="paymentAmount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="paymentAmount" readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Payment Method</label>
                            <select class="form-select" id="paymentMethod" name="payment_method" required>
                                <option value="" selected disabled>Select payment method</option>
                                <?php foreach ($payment_methods as $method): ?>
                                    <option value="<?php echo $method['id']; ?>"><?php echo htmlspecialchars($method['name']); ?> <?php echo $method['is_default'] ? '(Default)' : ''; ?></option>
                                <?php endforeach; ?>
                                <option value="new">+ Add new payment method</option>
                            </select>
                        </div>
                        
                        <div id="newPaymentFields" style="display: none;">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="cardNumber" class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="cardNumber" placeholder="XXXX XXXX XXXX XXXX">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="expiryDate" class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY">
                                </div>
                                <div class="col">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cvv" placeholder="XXX">
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="saveCard">
                                <label class="form-check-label" for="saveCard">
                                    Save this card for future payments
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="make_payment">Make Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Payment Method Modal -->
    <div class="modal fade" id="addPaymentMethodModal" tabindex="-1" aria-labelledby="addPaymentMethodModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentMethodModalLabel">Add Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="paymentType" class="form-label">Payment Type</label>
                        <select class="form-select" id="paymentType">
                            <option value="credit_card" selected>Credit/Debit Card</option>
                            <option value="bank_account">Bank Account</option>
                        </select>
                    </div>
                    
                    <div id="creditCardFields">
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Name on Card</label>
                            <input type="text" class="form-control" id="fullName" placeholder="Full Name">
                        </div>
                        <div class="mb-3">
                            <label for="newCardNumber" class="form-label">Card Number</label>
                            <input type="text" class="form-control" id="newCardNumber" placeholder="XXXX XXXX XXXX XXXX">
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="newExpiryDate" class="form-label">Expiry Date</label>
                                <input type="text" class="form-control" id="newExpiryDate" placeholder="MM/YY">
                            </div>
                            <div class="col">
                                <label for="newCvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="newCvv" placeholder="XXX">
                            </div>
                        </div>
                    </div>
                    
                    <div id="bankAccountFields" style="display: none;">
                        <div class="mb-3">
                            <label for="accountName" class="form-label">Account Holder Name</label>
                            <input type="text" class="form-control" id="accountName" placeholder="Full Name">
                        </div>
                        <div class="mb-3">
                            <label for="routingNumber" class="form-label">Routing Number</label>
                            <input type="text" class="form-control" id="routingNumber" placeholder="XXXXXXXXX">
                        </div>
                        <div class="mb-3">
                            <label for="accountNumber" class="form-label">Account Number</label>
                            <input type="text" class="form-control" id="accountNumber" placeholder="XXXXXXXXXXXX">
                        </div>
                        <div class="mb-3">
                            <label for="accountType" class="form-label">Account Type</label>
                            <select class="form-select" id="accountType">
                                <option value="checking" selected>Checking</option>
                                <option value="savings">Savings</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="setAsDefault">
                        <label class="form-check-label" for="setAsDefault">
                            Set as default payment method
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Payment Method</button>
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

        // Payment Modal
        const paymentModal = document.getElementById('paymentModal');
        if (paymentModal) {
            paymentModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const invoiceId = button.getAttribute('data-id');
                const amount = button.getAttribute('data-amount');
                const description = button.getAttribute('data-description');
                
                const modalInvoiceId = paymentModal.querySelector('#invoiceId');
                const modalAmount = paymentModal.querySelector('#paymentAmount');
                const modalDescription = paymentModal.querySelector('#paymentDescription');
                
                modalInvoiceId.value = invoiceId;
                modalAmount.value = parseFloat(amount).toFixed(2);
                modalDescription.value = description;
            });
        }

        // New Payment Method toggle
        const paymentMethodSelect = document.getElementById('paymentMethod');
        const newPaymentFields = document.getElementById('newPaymentFields');
        
        if (paymentMethodSelect && newPaymentFields) {
            paymentMethodSelect.addEventListener('change', function() {
                if (this.value === 'new') {
                    newPaymentFields.style.display = 'block';
                } else {
                    newPaymentFields.style.display = 'none';
                }
            });
        }

        // Toggle payment type fields
        const paymentTypeSelect = document.getElementById('paymentType');
        const creditCardFields = document.getElementById('creditCardFields');
        const bankAccountFields = document.getElementById('bankAccountFields');
        
        if (paymentTypeSelect && creditCardFields && bankAccountFields) {
            paymentTypeSelect.addEventListener('change', function() {
                if (this.value === 'credit_card') {
                    creditCardFields.style.display = 'block';
                    bankAccountFields.style.display = 'none';
                } else {
                    creditCardFields.style.display = 'none';
                    bankAccountFields.style.display = 'block';
                }
            });
        }
    </script>
</body>
</html> 