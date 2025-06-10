<?php
// Start session
session_start();

// Include database configuration
// include '../includes/config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to login page
    header('Location: ../login.php');
    exit;
}

// Get admin information
$admin_id = $_SESSION['user_id'];
$admin_name = $_SESSION['user_name'];

// Placeholder data (in a real system, you would fetch this from the database)
$monthly_appointments = [
    'Jan' => 45,
    'Feb' => 58,
    'Mar' => 62,
    'Apr' => 70,
    'May' => 75, 
    'Jun' => 80,
    'Jul' => 85,
    'Aug' => 79,
    'Sep' => 83,
    'Oct' => 90,
    'Nov' => 88,
    'Dec' => 92
];

$department_appointments = [
    'Cardiology' => 120,
    'Neurology' => 85,
    'Orthopedics' => 95,
    'Dermatology' => 65,
    'Pediatrics' => 110,
    'Ophthalmology' => 40,
    'Psychiatry' => 35
];

$revenue_data = [
    'Jan' => 12500,
    'Feb' => 14200,
    'Mar' => 15800,
    'Apr' => 16900,
    'May' => 18200, 
    'Jun' => 19500,
    'Jul' => 20400,
    'Aug' => 19800,
    'Sep' => 21200,
    'Oct' => 22500,
    'Nov' => 23100,
    'Dec' => 24800
];

$patient_demographics = [
    'age_groups' => [
        '0-18' => 95,
        '19-35' => 145,
        '36-50' => 168,
        '51-65' => 132,
        '65+' => 110
    ],
    'gender' => [
        'Male' => 312,
        'Female' => 338
    ]
];

$doctor_performance = [
    [
        'id' => 1,
        'name' => 'Dr. James Peterson',
        'specialty' => 'Orthopedics',
        'appointments' => 85,
        'rating' => 4.8,
        'satisfaction' => 92
    ],
    [
        'id' => 2,
        'name' => 'Dr. Sarah Adams',
        'specialty' => 'Neurology',
        'appointments' => 72,
        'rating' => 4.7,
        'satisfaction' => 90
    ],
    [
        'id' => 3,
        'name' => 'Dr. Robert Wilson',
        'specialty' => 'Cardiology',
        'appointments' => 90,
        'rating' => 4.9,
        'satisfaction' => 95
    ],
    [
        'id' => 4,
        'name' => 'Dr. Michael Chen',
        'specialty' => 'Dermatology',
        'appointments' => 65,
        'rating' => 4.6,
        'satisfaction' => 88
    ],
    [
        'id' => 5,
        'name' => 'Dr. Jessica Lee',
        'specialty' => 'Pediatrics',
        'appointments' => 95,
        'rating' => 4.9,
        'satisfaction' => 96
    ]
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics | Admin Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <h1 class="h3 mb-0 text-gray-800">Reports & Analytics</h1>
                    <div>
                        <a href="#" class="btn btn-sm btn-primary shadow-sm me-2" id="printReport">
                            <i class="fas fa-print fa-sm text-white-50 me-1"></i> Print
                        </a>
                        <a href="#" class="btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50 me-1"></i> Generate Report
                        </a>
                    </div>
                </div>

                <!-- Report Filters -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Report Filters</h6>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-3">
                                    <label for="reportType" class="form-label">Report Type</label>
                                    <select class="form-select" id="reportType">
                                        <option value="appointments" selected>Appointments</option>
                                        <option value="patients">Patients</option>
                                        <option value="doctors">Doctors</option>
                                        <option value="revenue">Revenue</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="dateRangeStart" class="form-label">Date Range (Start)</label>
                                    <input type="date" class="form-control" id="dateRangeStart" value="2023-01-01">
                                </div>
                                <div class="col-md-3">
                                    <label for="dateRangeEnd" class="form-label">Date Range (End)</label>
                                    <input type="date" class="form-control" id="dateRangeEnd" value="2023-12-31">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary w-100">Apply Filters</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Report Summary Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Appointments</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">817</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Annual Revenue</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">$208,900</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Patient Satisfaction
                                        </div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">92%</div>
                                            </div>
                                            <div class="col">
                                                <div class="progress progress-sm mr-2">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        style="width: 92%" aria-valuenow="92" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-smile fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Active Patients</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">650</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-injured fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointment Analytics -->
                <div class="row">
                    <!-- Monthly Appointments Chart -->
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 fw-bold text-primary">Monthly Appointments (2023)</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Export Options:</div>
                                        <a class="dropdown-item" href="#">Export as CSV</a>
                                        <a class="dropdown-item" href="#">Export as PDF</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">View Details</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="appointmentsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Appointments by Department -->
                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">Appointments by Department</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie pt-4">
                                    <canvas id="departmentPieChart"></canvas>
                                </div>
                                <div class="mt-4 text-center small">
                                    <span class="me-2">
                                        <i class="fas fa-circle text-primary"></i> Cardiology
                                    </span>
                                    <span class="me-2">
                                        <i class="fas fa-circle text-success"></i> Neurology
                                    </span>
                                    <span class="me-2">
                                        <i class="fas fa-circle text-info"></i> Others
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Report Chart -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">Revenue Analysis (2023)</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Patient Demographics -->
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">Patient Demographics</h6>
                            </div>
                            <div class="card-body">
                                <h4 class="small fw-bold">Age Distribution</h4>
                                <div class="mb-4">
                                    <canvas id="ageDistributionChart" height="200"></canvas>
                                </div>
                                
                                <h4 class="small fw-bold mt-4">Gender Distribution</h4>
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="progress mb-4">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 48%"
                                                aria-valuenow="48" aria-valuemin="0" aria-valuemax="100">Male (48%)</div>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 52%"
                                                aria-valuenow="52" aria-valuemin="0" aria-valuemax="100">Female (52%)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="d-inline-block me-3">
                                                <span class="text-primary fw-bold">312</span><br>
                                                <small>Male</small>
                                            </div>
                                            <div class="d-inline-block">
                                                <span class="text-danger fw-bold">338</span><br>
                                                <small>Female</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Doctor Performance -->
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">Top Doctor Performance</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="doctorPerformanceTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Specialty</th>
                                                <th>Appointments</th>
                                                <th>Rating</th>
                                                <th>Satisfaction</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($doctor_performance as $doctor): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($doctor['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($doctor['specialty']); ?></td>
                                                    <td><?php echo $doctor['appointments']; ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2"><?php echo $doctor['rating']; ?></div>
                                                            <div class="small text-warning">
                                                                <?php 
                                                                $full_stars = floor($doctor['rating']);
                                                                $half_star = ($doctor['rating'] - $full_stars) >= 0.5;
                                                                
                                                                for ($i = 0; $i < $full_stars; $i++) {
                                                                    echo '<i class="fas fa-star"></i>';
                                                                }
                                                                
                                                                if ($half_star) {
                                                                    echo '<i class="fas fa-star-half-alt"></i>';
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="progress" style="height: 10px;">
                                                            <div class="progress-bar bg-success" role="progressbar" 
                                                                style="width: <?php echo $doctor['satisfaction']; ?>%" 
                                                                aria-valuenow="<?php echo $doctor['satisfaction']; ?>" 
                                                                aria-valuemin="0" aria-valuemax="100">
                                                                <?php echo $doctor['satisfaction']; ?>%
                                                            </div>
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
        
        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Appointments Chart
            var appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
            var appointmentsChart = new Chart(appointmentsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Number of Appointments',
                        data: [45, 58, 62, 70, 75, 80, 85, 79, 83, 90, 88, 92],
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            
            // Departments Pie Chart
            var departmentCtx = document.getElementById('departmentPieChart').getContext('2d');
            var departmentChart = new Chart(departmentCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Cardiology', 'Neurology', 'Orthopedics', 'Dermatology', 'Pediatrics', 'Others'],
                    datasets: [{
                        data: [120, 85, 95, 65, 110, 75],
                        backgroundColor: [
                            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                        ],
                        hoverBackgroundColor: [
                            '#2e59d9', '#17a673', '#2c9faf', '#f4b619', '#e02d1b', '#6e707e'
                        ],
                        hoverBorderColor: 'rgba(234, 236, 244, 1)',
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            // Revenue Chart
            var revenueCtx = document.getElementById('revenueChart').getContext('2d');
            var revenueChart = new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue ($)',
                        data: [12500, 14200, 15800, 16900, 18200, 19500, 20400, 19800, 21200, 22500, 23100, 24800],
                        backgroundColor: 'rgba(28, 200, 138, 0.8)',
                        borderColor: 'rgba(28, 200, 138, 1)',
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
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
            
            // Age Distribution Chart
            var ageCtx = document.getElementById('ageDistributionChart').getContext('2d');
            var ageChart = new Chart(ageCtx, {
                type: 'bar',
                data: {
                    labels: ['0-18', '19-35', '36-50', '51-65', '65+'],
                    datasets: [{
                        label: 'Patients',
                        data: [95, 145, 168, 132, 110],
                        backgroundColor: [
                            'rgba(78, 115, 223, 0.8)',
                            'rgba(28, 200, 138, 0.8)',
                            'rgba(54, 185, 204, 0.8)',
                            'rgba(246, 194, 62, 0.8)',
                            'rgba(231, 74, 59, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
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
        });
        
        // Print report functionality
        document.getElementById('printReport').addEventListener('click', function() {
            window.print();
        });
    </script>
</body>
</html> 