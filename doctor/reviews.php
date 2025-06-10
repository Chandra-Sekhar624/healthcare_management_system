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

// Placeholder reviews data (in a real system, you would fetch this from the database)
$reviews = [
    [
        'id' => 1,
        'patient_name' => 'Jane Smith',
        'patient_id' => 1,
        'date' => '2023-05-15',
        'rating' => 5,
        'comment' => 'Dr. ' . $doctor_name . ' is an excellent physician. Very knowledgeable and takes time to explain everything. The office is clean and the staff is friendly. Highly recommend!',
        'status' => 'published',
        'response' => '',
        'response_date' => null
    ],
    [
        'id' => 2,
        'patient_name' => 'Robert Johnson',
        'patient_id' => 2,
        'date' => '2023-05-28',
        'rating' => 4,
        'comment' => 'Good experience overall. Dr. ' . $doctor_name . ' was thorough and professional. Wait time was a bit long but the care was worth it.',
        'status' => 'published',
        'response' => 'Thank you for your feedback, Robert. We\'re working on improving our scheduling to reduce wait times. I appreciate your patience and am glad you had a good experience otherwise.',
        'response_date' => '2023-05-29'
    ],
    [
        'id' => 3,
        'patient_name' => 'Emily Williams',
        'patient_id' => 3,
        'date' => '2023-06-02',
        'rating' => 5,
        'comment' => 'I\'ve been seeing Dr. ' . $doctor_name . ' for my prenatal care and couldn\'t be happier. Very attentive to my concerns and always makes me feel comfortable.',
        'status' => 'published',
        'response' => 'Thank you for your kind words, Emily! It\'s a pleasure being part of your prenatal journey.',
        'response_date' => '2023-06-02'
    ],
    [
        'id' => 4,
        'patient_name' => 'Michael Brown',
        'patient_id' => 4,
        'date' => '2023-06-10',
        'rating' => 3,
        'comment' => 'The doctor is good but the appointment scheduling system needs improvement. Had to wait 45 minutes past my appointment time.',
        'status' => 'published',
        'response' => '',
        'response_date' => null
    ],
    [
        'id' => 5,
        'patient_name' => 'Sarah Thompson',
        'patient_id' => 5,
        'date' => '2023-06-12',
        'rating' => 5,
        'comment' => 'Dr. ' . $doctor_name . ' helped me manage my asthma effectively. The treatment plan was clear and effective. Really appreciate the quality care!',
        'status' => 'published',
        'response' => '',
        'response_date' => null
    ],
    [
        'id' => 6,
        'patient_name' => 'David Wilson',
        'patient_id' => 6,
        'date' => '2023-06-14',
        'rating' => 4,
        'comment' => 'Very professional doctor with excellent bedside manner. The follow-up after my surgery was impressive.',
        'status' => 'published',
        'response' => '',
        'response_date' => null
    ]
];

// Calculate average rating
$total_rating = 0;
$total_reviews = count($reviews);
$rating_breakdown = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

foreach ($reviews as $review) {
    $total_rating += $review['rating'];
    $rating_breakdown[$review['rating']]++;
}

$average_rating = $total_reviews > 0 ? round($total_rating / $total_reviews, 1) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews | Doctor Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .star-rating .fa-star {
            color: #e5e5e5;
        }
        .star-rating .fa-star.checked {
            color: #ffc107;
        }
        .review-item {
            border-left: 4px solid #4e73df;
            background-color: #f8f9fc;
            transition: transform 0.2s;
        }
        .review-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .review-item.highlighted {
            border-left-color: #1cc88a;
        }
        .progress-bar-5 { background-color: #1cc88a; }
        .progress-bar-4 { background-color: #36b9cc; }
        .progress-bar-3 { background-color: #f6c23e; }
        .progress-bar-2 { background-color: #e74a3b; }
        .progress-bar-1 { background-color: #e74a3b; }
        .response-box {
            background-color: #e8f4ff;
            border-left: 3px solid #4e73df;
        }
    </style>
</head>
<body class="dashboard-body">
     <!-- Sidebar and Navigation -->
    <?php include 'sidebar_nav.php'; ?>
            <!-- Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Patient Reviews</h1>
                    <div>
                    <a href="#" class="btn btn-info btn-sm shadow-sm ms-2" onclick="window.print();">
                            <i class="fas fa-print fa-sm me-2"></i> Print
                        </a>
                    </div>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <!-- Reviews Statistics Cards -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col me-2">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Reviews</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $total_reviews; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-comments fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Average Rating</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            <?php echo $average_rating; ?> 
                                            <span class="star-rating small">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?php echo $i <= round($average_rating) ? 'checked' : ''; ?>"></i>
                                                <?php endfor; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-star fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">5-Star Reviews</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            <?php 
                                                $five_star_percentage = $total_reviews > 0 ? round(($rating_breakdown[5] / $total_reviews) * 100) : 0;
                                                echo $five_star_percentage . '%'; 
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-award fa-2x text-gray-300"></i>
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
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Pending Responses</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            <?php
                                                $pending_responses = 0;
                                                foreach ($reviews as $review) {
                                                    if (empty($review['response'])) {
                                                        $pending_responses++;
                                                    }
                                                }
                                                echo $pending_responses;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-reply fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Content -->
                <div class="row">
                    <!-- Reviews List -->
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 fw-bold text-primary">Recent Reviews</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Filter By:</div>
                                        <a class="dropdown-item" href="#">All Reviews</a>
                                        <a class="dropdown-item" href="#">5-Star Only</a>
                                        <a class="dropdown-item" href="#">Needs Response</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Sort by Date</a>
                                        <a class="dropdown-item" href="#">Sort by Rating</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php foreach ($reviews as $review): ?>
                                <div class="review-item mb-4 p-3 rounded <?php echo $review['rating'] >= 5 ? 'highlighted' : ''; ?>">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="mb-0"><?php echo htmlspecialchars($review['patient_name']); ?></h5>
                                            <div class="star-rating">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'checked' : ''; ?>"></i>
                                                <?php endfor; ?>
                                                <span class="ms-2 small text-muted"><?php echo date('M d, Y', strtotime($review['date'])); ?></span>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" type="button" id="reviewAction<?php echo $review['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="reviewAction<?php echo $review['id']; ?>">
                                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#respondModal<?php echo $review['id']; ?>">
                                                    <i class="fas fa-reply fa-sm me-2"></i> Respond
                                                </a></li>
                                                <li><a class="dropdown-item" href="#">
                                                    <i class="fas fa-flag fa-sm me-2"></i> Flag for Review
                                                </a></li>
                                                <li><a class="dropdown-item" href="#">
                                                    <i class="fas fa-print fa-sm me-2"></i> Print
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="mb-2"><?php echo htmlspecialchars($review['comment']); ?></p>
                                    
                                    <?php if (!empty($review['response'])): ?>
                                    <div class="response-box p-3 mt-3 rounded">
                                        <div class="d-flex align-items-center mb-2">
                                            <img src="../img/doctor-1.jpg" alt="Doctor" class="rounded-circle me-2" style="width: 24px; height: 24px;">
                                            <strong>Your Response</strong>
                                            <span class="ms-2 small text-muted"><?php echo date('M d, Y', strtotime($review['response_date'])); ?></span>
                                        </div>
                                        <p class="mb-0 small"><?php echo htmlspecialchars($review['response']); ?></p>
                                    </div>
                                    <?php else: ?>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#respondModal<?php echo $review['id']; ?>">
                                            <i class="fas fa-reply me-1"></i> Respond
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="card-footer">
                                <nav>
                                    <ul class="pagination justify-content-center mb-0">
                                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Analysis -->
                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">Rating Breakdown</h6>
                            </div>
                            <div class="card-body">
                                <div class="rating-breakdown">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <?php 
                                            $count = $rating_breakdown[$i]; 
                                            $percentage = $total_reviews > 0 ? ($count / $total_reviews) * 100 : 0;
                                        ?>
                                        <div class="rating-item mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <div>
                                                    <span class="me-2"><?php echo $i; ?></span>
                                                    <?php for ($j = 0; $j < 5; $j++): ?>
                                                        <i class="fas fa-star <?php echo $j < $i ? 'checked' : ''; ?>" style="font-size: 12px;"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <span class="small"><?php echo $count; ?> reviews</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar progress-bar-<?php echo $i; ?>" role="progressbar" 
                                                    style="width: <?php echo $percentage; ?>%;" 
                                                    aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 fw-bold text-primary">Review Trends</h6>
                            </div>
                            <div class="card-body">
                                <div class="small text-muted mb-2">Last 30 days</div>
                                <canvas id="reviewTrendsChart" width="400" height="200"></canvas>
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between mb-1 small">
                                        <span>Bedside Manner</span>
                                        <span>4.8</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 96%;" aria-valuenow="96" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mb-1 small">
                                        <span>Knowledge</span>
                                        <span>4.9</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 98%;" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mb-1 small">
                                        <span>Wait Time</span>
                                        <span>3.7</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 6px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 74%;" aria-valuenow="74" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mb-1 small">
                                        <span>Staff Friendliness</span>
                                        <span>4.6</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 92%;" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <!-- Response Modals -->
    <?php foreach ($reviews as $review): ?>
    <div class="modal fade" id="respondModal<?php echo $review['id']; ?>" tabindex="-1" aria-labelledby="respondModalLabel<?php echo $review['id']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="respondModalLabel<?php echo $review['id']; ?>">Respond to Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="review-summary mb-3 p-3 bg-light rounded">
                        <div class="star-rating mb-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'checked' : ''; ?>"></i>
                            <?php endfor; ?>
                            <span class="ms-2 small text-muted"><?php echo date('M d, Y', strtotime($review['date'])); ?></span>
                        </div>
                        <p class="mb-1"><strong><?php echo htmlspecialchars($review['patient_name']); ?></strong></p>
                        <p class="mb-0 small"><?php echo htmlspecialchars($review['comment']); ?></p>
                    </div>
                    <form>
                        <div class="mb-3">
                            <label for="responseText<?php echo $review['id']; ?>" class="form-label">Your Response</label>
                            <textarea class="form-control" id="responseText<?php echo $review['id']; ?>" rows="4" placeholder="Type your response here..."><?php echo htmlspecialchars($review['response']); ?></textarea>
                        </div>
                        <div class="form-text mb-3">
                            Your response will be visible to the patient and publicly on your profile. Keep it professional.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Submit Response</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.1.3 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom JS -->
    <script src="../js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('reviewTrendsChart');
            if (ctx) {
                ctx = ctx.getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['May 15', 'May 20', 'May 25', 'May 30', 'Jun 5', 'Jun 10', 'Jun 15'],
                        datasets: [{
                            label: 'Average Rating',
                            data: [4.5, 4.6, 4.4, 4.5, 4.7, 4.2, 4.5],
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.1)',
                            borderWidth: 3,
                            pointBackgroundColor: '#4e73df',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: false,
                                min: 3.5,
                                max: 5.0,
                                ticks: {
                                    stepSize: 0.5
                                },
                                grid: {
                                    drawBorder: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#000',
                                bodyColor: '#000',
                                borderColor: '#e0e0e0',
                                borderWidth: 1,
                                padding: 10,
                                displayColors: false,
                                callbacks: {
                                    title: function(tooltipItems) {
                                        return tooltipItems[0].label;
                                    },
                                    label: function(context) {
                                        return 'Rating: ' + context.parsed.y;
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                console.error('Cannot find canvas element with id "reviewTrendsChart"');
            }
        });

        // Toggle sidebar on small screens
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dashboard-sidebar').classList.toggle('show');
        });
    </script>
</body>
</html>