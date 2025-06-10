<?php
// Start session
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Get admin information
$admin_id = $_SESSION['user_id'];
$admin_name = $_SESSION['user_name'];

// Placeholder data (in a real system, fetch from database)
$payments = [
    [
        'id' => 'PAY-10001',
        'patient_name' => 'Jane Smith',
        'patient_id' => 1,
        'doctor_name' => 'Dr. Robert Wilson',
        'service' => 'Cardiology Consultation',
        'amount' => 150.00,
        'date' => '2023-06-15',
        'payment_method' => 'Credit Card',
        'status' => 'completed',
        'invoice' => 'INV-20230615-001'
    ],
    [
        'id' => 'PAY-10002',
        'patient_name' => 'Robert Johnson',
        'patient_id' => 2,
        'doctor_name' => 'Dr. Sarah Adams',
        'service' => 'Neurology Consultation',
        'amount' => 200.00,
        'date' => '2023-06-16',
        'payment_method' => 'Insurance',
        'status' => 'completed',
        'invoice' => 'INV-20230616-001'
    ],
    [
        'id' => 'PAY-10003',
        'patient_name' => 'Emily Williams',
        'patient_id' => 3,
        'doctor_name' => 'Dr. Michael Chen',
        'service' => 'Dermatology Procedure',
        'amount' => 350.00,
        'date' => '2023-06-17',
        'payment_method' => 'Bank Transfer',
        'status' => 'pending',
        'invoice' => 'INV-20230617-001'
    ],
    [
        'id' => 'PAY-10004',
        'patient_name' => 'Thomas Walker',
        'patient_id' => 4,
        'doctor_name' => 'Dr. James Peterson',
        'service' => 'Orthopedic Surgery',
        'amount' => 1200.00,
        'date' => '2023-06-14',
        'payment_method' => 'Insurance',
        'status' => 'completed',
        'invoice' => 'INV-20230614-001'
    ],
    [
        'id' => 'PAY-10005',
        'patient_name' => 'Julia Martinez',
        'patient_id' => 5,
        'doctor_name' => 'Dr. Sarah Adams',
        'service' => 'Neurology Test',
        'amount' => 275.00,
        'date' => '2023-06-18',
        'payment_method' => 'Credit Card',
        'status' => 'refunded',
        'invoice' => 'INV-20230618-001'
    ],
    [
        'id' => 'PAY-10006',
        'patient_name' => 'David Thompson',
        'patient_id' => 6,
        'doctor_name' => 'Dr. Jessica Lee',
        'service' => 'Pediatric Checkup',
        'amount' => 120.00,
        'date' => '2023-06-19',
        'payment_method' => 'Cash',
        'status' => 'completed',
        'invoice' => 'INV-20230619-001'
    ],
    [
        'id' => 'PAY-10007',
        'patient_name' => 'Sarah Davis',
        'patient_id' => 7,
        'doctor_name' => 'Dr. Robert Wilson',
        'service' => 'Cardiology Follow-up',
        'amount' => 100.00,
        'date' => '2023-06-20',
        'payment_method' => 'Insurance',
        'status' => 'pending',
        'invoice' => 'INV-20230620-001'
    ]
];

// Sort payments by date in descending order (newest first)
usort($payments, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// Calculate payment statistics
$total_payments = count($payments);
$total_revenue = array_sum(array_column($payments, 'amount'));
$completed_payments = count(array_filter($payments, function($payment) {
    return $payment['status'] === 'completed';
}));
$pending_payments = count(array_filter($payments, function($payment) {
    return $payment['status'] === 'pending';
}));

// Group by payment method
$payment_methods = [];
foreach ($payments as $payment) {
    if (!isset($payment_methods[$payment['payment_method']])) {
        $payment_methods[$payment['payment_method']] = 0;
    }
    $payment_methods[$payment['payment_method']] += $payment['amount'];
}

// Handle payment status changes and new payment additions (in a real system, update database)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $payment_id = $_GET['id'];
    $status_message = '';
    if ($action === 'mark_completed') {
        $status_message = "Payment $payment_id has been marked as completed.";
    } elseif ($action === 'refund') {
        $status_message = "Payment $payment_id has been refunded.";
    }
}

// Handle new payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_payment'])) {
    // Generate a new payment ID
    $new_id = 'PAY-' . (10000 + count($payments) + 1);
    
    // Create new payment record
    $new_payment = [
        'id' => $new_id,
        'patient_name' => $_POST['patient_name'],
        'patient_id' => (int)$_POST['patient_id'],
        'doctor_name' => $_POST['doctor_name'],
        'service' => $_POST['service'],
        'amount' => (float)$_POST['amount'],
        'date' => $_POST['payment_date'],
        'payment_method' => $_POST['payment_method'],
        'status' => $_POST['status'],
        'invoice' => 'INV-' . str_replace('-', '', $_POST['payment_date']) . '-' . str_pad(count($payments) + 1, 3, '0', STR_PAD_LEFT)
    ];
    
    // Add to payments array (in a real system, save to database)
    array_unshift($payments, $new_payment);
    
    // Update statistics
    $total_payments = count($payments);
    $total_revenue += $new_payment['amount'];
    if ($new_payment['status'] === 'completed') {
        $completed_payments++;
    } elseif ($new_payment['status'] === 'pending') {
        $pending_payments++;
    }
    
    // Update payment methods
    if (!isset($payment_methods[$new_payment['payment_method']])) {
        $payment_methods[$new_payment['payment_method']] = 0;
    }
    $payment_methods[$new_payment['payment_method']] += $new_payment['amount'];
    
    $status_message = "New payment $new_id has been added successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management | Admin Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
        }
        .dashboard-sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 999;
            background: #2c3e50;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        .dashboard-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        .dashboard-sidebar .sidebar-header {
            padding: 20px;
            background: #1a2a3a;
        }
        .dashboard-sidebar .sidebar-user {
            padding: 20px 0;
        }
        .dashboard-sidebar .user-avatar img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }
        .sidebar-nav .nav-item {
            margin-bottom: 5px;
        }
        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 10px 20px;
            border-radius: 5px;
            margin: 0 10px;
            transition: all 0.3s;
        }
        .sidebar-nav .nav-link:hover, 
        .sidebar-nav .nav-item.active .nav-link {
            background: #3498db;
            color: #fff;
        }
        .sidebar-nav .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 24px;
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .card-header h6 {
            font-weight: 600;
        }
        .border-left-primary {
            border-left: 4px solid #4e73df;
        }
        .border-left-success {
            border-left: 4px solid #1cc88a;
        }
        .border-left-warning {
            border-left: 4px solid #f6c23e;
        }
        .border-left-info {
            border-left: 4px solid #36b9cc;
        }
        .badge {
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 6px;
        }
        .table {
            vertical-align: middle;
        }
        .btn-group-sm .btn {
            border-radius: 6px !important;
            margin: 0 2px;
            padding: 6px 10px;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
        .modal-content {
            border: none;
            border-radius: 12px;
        }
        .modal-header {
            border-radius: 12px 12px 0 0;
            background-color: #f8f9fa;
        }
        .modal-footer {
            border-radius: 0 0 12px 12px;
        }
        .icon-circle {
            height: 40px;
            width: 40px;
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-circle i {
            font-size: 1rem;
        }
        .bg-primary {
            background-color: #4e73df !important;
        }
        .bg-success {
            background-color: #1cc88a !important;
        }
        .bg-warning {
            background-color: #f6c23e !important;
        }
        .bg-info {
            background-color: #36b9cc !important;
        }
        .bg-success-soft {
            background-color: rgba(28, 200, 138, 0.1) !important;
        }
        .bg-warning-soft {
            background-color: rgba(246, 194, 62, 0.1) !important;
        }
        .bg-danger-soft {
            background-color: rgba(231, 74, 59, 0.1) !important;
        }
        .bg-primary-soft {
            background-color: rgba(78, 115, 223, 0.1) !important;
        }
        .avatar-circle {
            height: 40px;
            width: 40px;
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #4e73df;
            background-color: rgba(78, 115, 223, 0.1);
        }
        .rounded-pill {
            border-radius: 50rem !important;
        }
        .payment-method {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
            background-color: #f8f9fa;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .payment-method i {
            margin-right: 0.5rem;
        }
        .service-info {
            display: flex;
            flex-direction: column;
        }
        .service-info .service-name {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        .service-info .service-date {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .patient-info {
            display: flex;
            align-items: center;
        }
        .patient-info .patient-details {
            display: flex;
            flex-direction: column;
        }
        .patient-info .patient-name {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        .patient-info .patient-id {
            font-size: 0.85rem;
            color: #6c757d;
        }
        #paymentsTable {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }
        #paymentsTable td {
            vertical-align: middle;
        }
        #paymentsTable tr {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }
        #paymentsTable tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
        }
        #paymentsTable th {
            font-weight: 600;
            border-bottom: 2px solid #e3e6f0;
            padding-bottom: 1rem;
        }
        .payment-amount {
            font-weight: 700;
            color: #2e3a4a;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            border-radius: 1rem;
            font-weight: 500;
        }
        .status-completed {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        .status-refunded {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        /* GST Details Row Styles */
        .gst-details-row td {
            padding: 0.75rem 1rem;
            background-color: #f8f9fc;
        }
        
        .view-details-btn.active {
            background-color: #e8f0fe;
            color: #4e73df;
        }
        @media (max-width: 768px) {
            .dashboard-sidebar {
                margin-left: -250px;
            }
            .dashboard-sidebar.show {
                margin-left: 0;
            }
            .dashboard-content {
                margin-left: 0;
            }
        }
    </style> -->
</head>
<body class="dashboard-body">
   <!-- Sidebar and Navigation -->
    <?php include 'sidebar_nav.php'; ?>
            <!-- Page Content -->
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Payment Management</h1>
                    <div>
                        <a href="#" class="btn btn-sm btn-success shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                            <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Record Payment
                        </a>
                        <a href="#" class="btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50 me-1"></i> Generate Report
                        </a>
                    </div>
                </div>

                <?php if (isset($status_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($status_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Payment Stats -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100">
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-dollar-sign text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                            Total Revenue
                                        </div>
                                        <div class="h4 mb-0 fw-bold">$<?php echo number_format($total_revenue, 2); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent py-2">
                                <small class="text-muted">
                                    <i class="fas fa-arrow-up text-success me-1"></i> 12.5% increase from last month
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100">
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-check-circle text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                            Completed Payments
                                        </div>
                                        <div class="h4 mb-0 fw-bold"><?php echo $completed_payments; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent py-2">
                                <small class="text-muted">
                                    <i class="fas fa-arrow-up text-success me-1"></i> 8% increase from last week
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100">
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-clock text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                            Pending Payments
                                        </div>
                                        <div class="h4 mb-0 fw-bold"><?php echo $pending_payments; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent py-2">
                                <small class="text-muted">
                                    <i class="fas fa-arrow-down text-danger me-1"></i> 3 require attention
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100">
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <div class="icon-circle bg-info">
                                            <i class="fas fa-file-medical text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                            Insurance Payments
                                        </div>
                                        <div class="h4 mb-0 fw-bold">$<?php echo number_format($payment_methods['Insurance'] ?? 0, 2); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent py-2">
                                <small class="text-muted">
                                    <i class="fas fa-arrow-up text-success me-1"></i> 4.2% increase from last month
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Filters -->
                <div class="card shadow mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-filter me-2"></i> Payment Filters
                        </h6>
                        <span class="badge bg-light text-primary"><?php echo $total_payments; ?> payments found</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1">Payment Status</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fas fa-tag text-primary"></i>
                                    </span>
                                    <select class="form-select border-start-0 ps-0" id="statusFilter">
                                        <option value="">All Statuses</option>
                                        <option value="completed">Completed</option>
                                        <option value="pending">Pending</option>
                                        <option value="refunded">Refunded</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1">Payment Method</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fas fa-credit-card text-primary"></i>
                                    </span>
                                    <select class="form-select border-start-0 ps-0" id="paymentMethodFilter">
                                        <option value="">All Payment Methods</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Insurance">Insurance</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1">Payment Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </span>
                                    <input type="date" class="form-control border-start-0 ps-0" id="dateFilter">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1">Search Payments</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fas fa-search text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" placeholder="Search by ID, name..." id="searchPayments">
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-outline-secondary me-2" onclick="$('#statusFilter, #paymentMethodFilter, #dateFilter, #searchPayments').val(''); table.search('').columns().search('').draw();">
                                        <i class="fas fa-redo-alt me-1"></i> Reset Filters
                                    </button>
                                    <button class="btn btn-primary" onclick="table.draw();">
                                        <i class="fas fa-filter me-1"></i> Apply Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments List -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-money-bill-wave me-2"></i> Payment Transactions
                        </h6>
                        <div class="dropdown no-arrow">
                            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow border-0 animated--fade-in">
                                <div class="dropdown-header text-uppercase small fw-bold text-primary">Export Options:</div>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-file-csv me-2 text-success"></i> Export as CSV
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-file-pdf me-2 text-danger"></i> Export as PDF
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list me-2 text-primary"></i> View All Payments
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover align-middle" id="paymentsTable" width="100%" cellspacing="0">
                                <thead class="bg-light text-dark">
                                    <tr>
                                        <th class="ps-3">ID</th>
                                        <th>Patient</th>
                                        <th>Service & Date</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th class="text-end pe-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($payments as $payment): ?>
                                        <?php
                                        $status_class = '';
                                        $status_icon = '';
                                        switch($payment['status']) {
                                            case 'completed':
                                                $status_class = 'success';
                                                $status_icon = 'fa-check-circle';
                                                break;
                                            case 'pending':
                                                $status_class = 'warning';
                                                $status_icon = 'fa-clock';
                                                break;
                                            case 'refunded':
                                                $status_class = 'danger';
                                                $status_icon = 'fa-undo';
                                                break;
                                        }
                                        $method_icon = '';
                                        switch($payment['payment_method']) {
                                            case 'Credit Card':
                                                $method_icon = 'fa-credit-card text-primary';
                                                break;
                                            case 'Insurance':
                                                $method_icon = 'fa-file-medical text-info';
                                                break;
                                            case 'Cash':
                                                $method_icon = 'fa-money-bill text-success';
                                                break;
                                            case 'Bank Transfer':
                                                $method_icon = 'fa-university text-secondary';
                                                break;
                                        }
                                        ?>
                                        <tr class="align-middle border-bottom">
                                            <td class="ps-3 fw-bold"><?php echo htmlspecialchars($payment['id']); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-2 bg-primary-soft">
                                                        <?php echo strtoupper(substr($payment['patient_name'], 0, 1)); ?>
                                                    </div>
                                                    <div>
                                                        <?php echo htmlspecialchars($payment['patient_name']); ?>
                                                        <br>
                                                        <small class="text-muted">PAT<?php echo str_pad($payment['patient_id'], 5, '0', STR_PAD_LEFT); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($payment['service']); ?></td>
                                            <td class="fw-bold text-dark">$<?php echo number_format($payment['amount'], 2); ?></td>
                                            <td><?php echo htmlspecialchars($payment['date']); ?></td>
                                            <td>
                                                <span class="d-flex align-items-center">
                                                    <i class="fas <?php echo $method_icon; ?> me-2"></i>
                                                    <?php echo htmlspecialchars($payment['payment_method']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $status_class; ?>-soft text-<?php echo $status_class; ?> px-3 py-2 rounded-pill">
                                                    <i class="fas <?php echo $status_icon; ?> me-1"></i>
                                                    <?php echo ucfirst(htmlspecialchars($payment['status'])); ?>
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="payment_details.php?id=<?php echo $payment['id']; ?>" class="btn btn-light text-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($payment['status'] === 'pending'): ?>
                                                        <a href="?action=mark_completed&id=<?php echo $payment['id']; ?>" class="btn btn-light text-success" data-bs-toggle="tooltip" title="Mark as Completed">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($payment['status'] === 'completed'): ?>
                                                        <a href="?action=refund&id=<?php echo $payment['id']; ?>" class="btn btn-light text-warning" data-bs-toggle="tooltip" title="Refund Payment">
                                                            <i class="fas fa-undo"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="#" class="btn btn-light text-primary" onclick="printInvoice('<?php echo $payment['invoice']; ?>')" data-bs-toggle="tooltip" title="Print Invoice">
                                                        <i class="fas fa-print"></i>
                                                    </a>
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

    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="addPaymentModalLabel">
                        <i class="fas fa-plus-circle text-primary me-2"></i> Record New Payment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST" action="payments.php">
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user-circle me-2"></i> Patient & Doctor Information
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="patientSelect" class="form-label">Patient</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-user-injured text-primary"></i>
                                            </span>
                                            <select class="form-select border-start-0 ps-0" id="patientSelect" name="patient_id" required>
                                                <option value="" selected disabled>Select patient</option>
                                                <option value="1" data-name="Jane Smith">Jane Smith</option>
                                                <option value="2" data-name="Robert Johnson">Robert Johnson</option>
                                                <option value="3" data-name="Emily Williams">Emily Williams</option>
                                                <option value="4" data-name="Thomas Walker">Thomas Walker</option>
                                                <option value="5" data-name="Julia Martinez">Julia Martinez</option>
                                            </select>
                                            <input type="hidden" name="patient_name" id="patientName">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="doctorSelect" class="form-label">Doctor</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-user-md text-primary"></i>
                                            </span>
                                            <select class="form-select border-start-0 ps-0" id="doctorSelect" name="doctor_id" required>
                                                <option value="" selected disabled>Select doctor</option>
                                                <option value="1" data-name="Dr. James Peterson">Dr. James Peterson (Orthopedics)</option>
                                                <option value="2" data-name="Dr. Sarah Adams">Dr. Sarah Adams (Neurology)</option>
                                                <option value="3" data-name="Dr. Robert Wilson">Dr. Robert Wilson (Cardiology)</option>
                                                <option value="4" data-name="Dr. Michael Chen">Dr. Michael Chen (Dermatology)</option>
                                                <option value="5" data-name="Dr. Jessica Lee">Dr. Jessica Lee (Pediatrics)</option>
                                            </select>
                                            <input type="hidden" name="doctor_name" id="doctorName">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-file-invoice-dollar me-2"></i> Service & Payment Details
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="serviceDescription" class="form-label">Service Description</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-stethoscope text-primary"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0 ps-0" id="serviceDescription" name="service" placeholder="Enter service description" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="paymentAmount" class="form-label">Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-dollar-sign text-primary"></i>
                                            </span>
                                            <input type="number" class="form-control border-start-0 ps-0" id="paymentAmount" name="amount" step="0.01" placeholder="0.00" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="paymentDate" class="form-label">Payment Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-calendar-alt text-primary"></i>
                                            </span>
                                            <input type="date" class="form-control border-start-0 ps-0" id="paymentDate" name="payment_date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="paymentMethod" class="form-label">Payment Method</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-credit-card text-primary"></i>
                                            </span>
                                            <select class="form-select border-start-0 ps-0" id="paymentMethod" name="payment_method" required>
                                                <option value="" selected disabled>Select payment method</option>
                                                <option value="Credit Card">Credit Card</option>
                                                <option value="Insurance">Insurance</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Bank Transfer">Bank Transfer</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="paymentStatus" class="form-label">Payment Status</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-tag text-primary"></i>
                                            </span>
                                            <select class="form-select border-start-0 ps-0" id="paymentStatus" name="status" required>
                                                <option value="completed">Completed</option>
                                                <option value="pending">Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="paymentNotes" class="form-label">Notes</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-sticky-note text-primary"></i>
                                            </span>
                                            <textarea class="form-control border-start-0 ps-0" id="paymentNotes" name="notes" rows="3" placeholder="Add any additional notes about this payment..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" name="add_payment" class="btn btn-primary rounded-pill">
                        <i class="fas fa-save me-1"></i> Record Payment
                    </button>
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
    <!-- Custom JS -->
    <script src="../js/main.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        $(document).ready(function() {
            try {
                // Format table rows before DataTables initialization
                $('#paymentsTable tbody tr').each(function() {
                    const $row = $(this);
                    const service = $row.find('td:nth-child(3)').text().trim();
                    const amount = $row.find('td:nth-child(4)').text().trim();
                    const date = $row.find('td:nth-child(5)').text().trim();
                    
                    $row.find('td:nth-child(3)').html(
                        `<div class="service-info" style="display: flex; flex-direction: column;">
                            <span class="service-name" style="font-weight: 500; margin-bottom: 0.25rem;">${service}</span>
                            <span class="service-date" style="font-size: 0.85rem; color: #6c757d;"><i class="fas fa-calendar-alt me-1"></i>${date}</span>
                        </div>`
                    );
                    
                    $row.find('td:nth-child(4)').html(`<span class="payment-amount" style="font-weight: 600; color: #2c3e50; font-size: 1.05rem;">${amount}</span>`);
                    
                    const patientCell = $row.find('td:nth-child(2)');
                    const patientHtml = patientCell.html();
                    patientCell.html(`<div class="patient-info">${patientHtml}</div>`);
                    
                    const methodCell = $row.find('td:nth-child(6)');
                    methodCell.find('span').addClass('payment-method');
                    
                    const statusCell = $row.find('td:nth-child(7)');
                    const statusBadge = statusCell.find('span');
                    const statusText = statusBadge.text().trim();
                    const statusIcon = statusBadge.find('i').prop('outerHTML');
                    let statusClass = 'status-pending';
                    
                    if (statusText.toLowerCase().includes('completed')) {
                        statusClass = 'status-completed';
                    } else if (statusText.toLowerCase().includes('refunded')) {
                        statusClass = 'status-refunded';
                    }
                    
                    statusCell.html(`<span class="status-badge ${statusClass}" style="display: inline-flex; align-items: center; padding: 0.35rem 0.75rem; border-radius: 30px; font-size: 0.85rem; font-weight: 500;">${statusIcon} <span style="margin-left: 4px;">${statusText}</span></span>`);
                    
                    $row.find('td:nth-child(5)').hide();
                });
                
                $('#paymentsTable thead th:nth-child(5)').hide();
                $('#paymentsTable thead th:nth-child(3)').text('Service & Date');
                
                // Initialize DataTables
                var table = $('#paymentsTable').DataTable({
                    "pageLength": 10,
                    "ordering": true,
                    "order": [[4, 'desc']], // Order by date column (hidden) in descending order
                    "info": true,
                    "searching": true,
                    "dom": '<"top"f>rt<"bottom"lp><"clear">',
                    "language": {
                        "search": "",
                        "searchPlaceholder": "Search payments..."
                    },
                    "columnDefs": [
                        { "visible": false, "targets": 4 }, // Hide date column
                        { "width": "22%", "targets": 2 }, // Service & Date column width
                        { "width": "18%", "targets": 1 }, // Patient column width
                        { "width": "10%", "targets": 0 }, // ID column width
                        { "width": "12%", "targets": 3 }, // Amount column width
                        { "width": "15%", "targets": 5 }, // Method column width
                        { "width": "12%", "targets": 6 }, // Status column width
                        { "width": "11%", "targets": 7 }  // Actions column width
                    ]
                });
                
                $('.dataTables_filter input').addClass('form-control');
                $('.dataTables_length select').addClass('form-select');
                
                // Filters
                $('#statusFilter').on('change', function() {
                    table.column(6).search(this.value).draw();
                });
                
                $('#paymentMethodFilter').on('change', function() {
                    table.column(5).search(this.value).draw();
                });
                
                $('#dateFilter').on('change', function() {
                    table.column(4).search(this.value).draw();
                });
                
                $('#searchPayments').on('keyup', function() {
                    table.search(this.value).draw();
                });
            } catch (error) {
                console.error('Error in DataTables initialization:', error);
            }
        });

        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
            document.querySelector('.dashboard-content').classList.toggle('shift');
        });

        // Print invoice
        function printInvoice(invoiceId) {
            var printWindow = window.open('', '_blank', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Invoice: ' + invoiceId + '</title>');
            printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">');
            printWindow.document.write('<style>body { padding: 20px; }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<div class="container">');
            printWindow.document.write('<h3 class="mb-4">HealthConnect</h3>');
            printWindow.document.write('<h5 class="text-primary">Invoice: ' + invoiceId + '</h5>');
            printWindow.document.write('<p>This is a sample invoice preview. In a real application, this would display the actual invoice content from the database.</p>');
            printWindow.document.write('<button onclick="window.print();" class="btn btn-primary">Print Invoice</button>');
            printWindow.document.write('<button onclick="window.close();" class="btn btn-secondary ms-2">Close</button>');
            printWindow.document.write('</div>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
        }

        // Card hover effect
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
                card.style.boxShadow = '0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2)';
                card.style.transition = 'all 0.3s ease';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = '0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1)';
                card.style.transition = 'all 0.3s ease';
            });
        });
        
        // Update hidden fields when patient and doctor are selected
        document.getElementById('patientSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('patientName').value = selectedOption.getAttribute('data-name');
        });
        
        document.getElementById('doctorSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('doctorName').value = selectedOption.getAttribute('data-name');
        });
        

    </script>
</body>
</html>