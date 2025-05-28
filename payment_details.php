<?php
// Initialize the session
session_start();

// Include database connection
require_once "../config/db_connect.php";

// Check if payment ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: payments.php");
    exit;
}

$payment_id = $_GET['id'];

// Fetch payment details
// In a real application, this would be fetched from the database
// For this example, we'll use the same array structure as in payments.php
$payments = [
    [
        'id' => 'PAY-10001',
        'patient_id' => 1,
        'patient_name' => 'Jane Smith',
        'doctor_id' => 1,
        'doctor_name' => 'Dr. Michael Johnson',
        'service' => 'Cardiology Follow-up',
        'amount' => 150.00,
        'date' => '2023-06-15',
        'payment_method' => 'Credit Card',
        'status' => 'completed',
        'invoice' => 'INV-20230615-001'
    ],
    [
        'id' => 'PAY-10002',
        'patient_id' => 2,
        'patient_name' => 'John Davis',
        'doctor_id' => 2,
        'doctor_name' => 'Dr. Sarah Williams',
        'service' => 'Pediatric Checkup',
        'amount' => 100.00,
        'date' => '2023-06-14',
        'payment_method' => 'Insurance',
        'status' => 'pending',
        'invoice' => 'INV-20230614-001'
    ],
    [
        'id' => 'PAY-10003',
        'patient_id' => 3,
        'patient_name' => 'Robert Brown',
        'doctor_id' => 3,
        'doctor_name' => 'Dr. Emily Clark',
        'service' => 'Dental Cleaning',
        'amount' => 75.00,
        'date' => '2023-06-13',
        'payment_method' => 'Cash',
        'status' => 'completed',
        'invoice' => 'INV-20230613-001'
    ],
    [
        'id' => 'PAY-10004',
        'patient_id' => 4,
        'patient_name' => 'Mary Wilson',
        'doctor_id' => 4,
        'doctor_name' => 'Dr. James Taylor',
        'service' => 'Orthopedic Consultation',
        'amount' => 200.00,
        'date' => '2023-06-12',
        'payment_method' => 'Bank Transfer',
        'status' => 'completed',
        'invoice' => 'INV-20230612-001'
    ],
    [
        'id' => 'PAY-10005',
        'patient_id' => 5,
        'patient_name' => 'Patricia Moore',
        'doctor_id' => 5,
        'doctor_name' => 'Dr. Robert Anderson',
        'service' => 'Dermatology Consultation',
        'amount' => 125.00,
        'date' => '2023-06-11',
        'payment_method' => 'Credit Card',
        'status' => 'refunded',
        'invoice' => 'INV-20230611-001'
    ]
];

// Find the payment with the matching ID
$payment = null;
foreach ($payments as $p) {
    if ($p['id'] === $payment_id) {
        $payment = $p;
        break;
    }
}

// If payment not found, redirect back to payments page
if ($payment === null) {
    header("Location: payments.php");
    exit;
}

// Set status class for display
$status_class = '';
switch($payment['status']) {
    case 'completed':
        $status_class = 'success';
        break;
    case 'pending':
        $status_class = 'warning';
        break;
    case 'refunded':
        $status_class = 'danger';
        break;
}

// Set method icon for display
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details - HealthConnect</title>
    
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <a href="payments.php" class="text-decoration-none text-secondary me-2">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                Invoice: <?php echo htmlspecialchars($payment['invoice']); ?>
                            </h5>
                            <div>
                                <button class="btn btn-primary" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i> Print Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($payment['service']); ?></td>
                                        <td class="text-end">$<?php echo number_format($payment['amount'] * 0.9, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>GST (10%)</td>
                                        <td class="text-end">$<?php echo number_format($payment['amount'] * 0.1, 2); ?></td>
                                    </tr>
                                    <tr style="background-color: #e8f5e9;">
                                        <td><strong>Total</strong></td>
                                        <td class="text-end"><strong>$<?php echo number_format($payment['amount'], 2); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="payments.php" class="btn btn-secondary me-2">
                                Close
                            </a>
                            <button class="btn btn-primary" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> Print Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.1.3 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
