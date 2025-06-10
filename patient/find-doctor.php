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

// Placeholder data for specialties (in a real system, you would fetch this from the database)
$specialties = [
    'Cardiology',
    'Dermatology',
    'Endocrinology',
    'Gastroenterology',
    'Neurology',
    'Obstetrics & Gynecology',
    'Oncology',
    'Ophthalmology',
    'Orthopedics',
    'Pediatrics',
    'Psychiatry',
    'Urology'
];

// Placeholder data for doctors (in a real system, you would fetch this from the database)
$doctors = [
    [
        'id' => 1,
        'name' => 'Dr. John Williams',
        'specialty' => 'Cardiology',
        'qualification' => 'MD, FACC',
        'experience' => '15 years',
        'image' => '../img/doctor-1.jpg',
        'rating' => 4.8,
        'reviews' => 124,
        'location' => 'Main Hospital',
        'availability' => 'Mon, Wed, Fri',
        'bio' => 'Dr. Williams is a board-certified cardiologist specializing in cardiovascular disease management and preventive cardiology.'
    ],
    [
        'id' => 2,
        'name' => 'Dr. Sarah Johnson',
        'specialty' => 'Dermatology',
        'qualification' => 'MD, FAAD',
        'experience' => '12 years',
        'image' => '../img/doctor-2.jpg',
        'rating' => 4.7,
        'reviews' => 98,
        'location' => 'North Clinic',
        'availability' => 'Tue, Thu',
        'bio' => 'Dr. Johnson is a dermatologist who specializes in both medical and cosmetic dermatology.'
    ],
    [
        'id' => 3,
        'name' => 'Dr. Michael Brown',
        'specialty' => 'Orthopedics',
        'qualification' => 'MD, FAAOS',
        'experience' => '18 years',
        'image' => '../img/doctor-3.jpg',
        'rating' => 4.9,
        'reviews' => 156,
        'location' => 'Main Hospital',
        'availability' => 'Mon, Tue, Thu',
        'bio' => 'Dr. Brown is an orthopedic surgeon specializing in sports medicine and joint replacement.'
    ],
    [
        'id' => 4,
        'name' => 'Dr. Emily Rodriguez',
        'specialty' => 'Endocrinology',
        'qualification' => 'MD, PhD',
        'experience' => '10 years',
        'image' => '../img/doctor-4.jpg',
        'rating' => 4.6,
        'reviews' => 87,
        'location' => 'East Clinic',
        'availability' => 'Wed, Fri',
        'bio' => 'Dr. Rodriguez specializes in diabetes management, thyroid disorders, and hormonal imbalances.'
    ],
    [
        'id' => 5,
        'name' => 'Dr. David Chen',
        'specialty' => 'Neurology',
        'qualification' => 'MD, FAAN',
        'experience' => '14 years',
        'image' => '../img/doctor-5.jpg',
        'rating' => 4.7,
        'reviews' => 112,
        'location' => 'Main Hospital',
        'availability' => 'Mon, Thu, Fri',
        'bio' => 'Dr. Chen is a neurologist with expertise in headache disorders, stroke treatment, and neurodegenerative diseases.'
    ],
    [
        'id' => 6,
        'name' => 'Dr. Lisa Thompson',
        'specialty' => 'Pediatrics',
        'qualification' => 'MD, FAAP',
        'experience' => '11 years',
        'image' => '../img/doctor-6.jpg',
        'rating' => 4.9,
        'reviews' => 143,
        'location' => 'Children\'s Clinic',
        'availability' => 'Mon, Tue, Wed',
        'bio' => 'Dr. Thompson is a pediatrician dedicated to providing comprehensive healthcare for children from birth through adolescence.'
    ]
];

// Filter doctors based on search parameters
$filtered_doctors = $doctors;
if (isset($_GET['search'])) {
    $search = strtolower($_GET['search']);
    $specialty = isset($_GET['specialty']) ? $_GET['specialty'] : '';
    
    $filtered_doctors = array_filter($doctors, function($doctor) use ($search, $specialty) {
        $name_match = empty($search) || strpos(strtolower($doctor['name']), $search) !== false;
        $specialty_match = empty($specialty) || $doctor['specialty'] === $specialty;
        return $name_match && $specialty_match;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Doctor | Patient Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .doctor-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        .rating-stars {
            color: #ffc107;
        }
        .doctor-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .doctor-card:hover .doctor-image {
            transform: scale(1.05);
        }
        .doctor-image-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 15px 15px 0 0;
        }
        .doctor-status {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255,255,255,0.9);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .doctor-specialty {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background: rgba(78, 115, 223, 0.9);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .availability-badge {
            font-size: 0.8rem;
        }
        .search-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
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
                    <h1 class="h3 mb-0 text-gray-800">Find a Doctor</h1>
                </div>

                <!-- Search Box -->
                <div class="search-box shadow-sm">
                    <form action="" method="GET" class="row g-3">
                        <div class="col-md-5">
                            <label for="search" class="form-label">Doctor Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Search by doctor name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="specialty" class="form-label">Specialty</label>
                            <select class="form-select" id="specialty" name="specialty">
                                <option value="">All Specialties</option>
                                <?php foreach ($specialties as $specialty): ?>
                                    <option value="<?php echo htmlspecialchars($specialty); ?>" <?php echo (isset($_GET['specialty']) && $_GET['specialty'] === $specialty) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($specialty); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                    </form>
                </div>

                <!-- Doctors Cards -->
                <div class="row">
                    <?php if (count($filtered_doctors) > 0): ?>
                        <?php foreach ($filtered_doctors as $doctor): ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card shadow doctor-card h-100">
                                    <div class="doctor-image-wrapper">
                                        <img src="<?php echo htmlspecialchars($doctor['image']); ?>" class="doctor-image" alt="<?php echo htmlspecialchars($doctor['name']); ?>">
                                        <div class="doctor-status text-success">
                                            <i class="fas fa-circle me-1 small"></i>Available Today
                                        </div>
                                        <div class="doctor-specialty">
                                            <?php echo htmlspecialchars($doctor['specialty']); ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($doctor['name']); ?></h5>
                                            <span class="badge bg-primary"><?php echo htmlspecialchars($doctor['specialty']); ?></span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="rating-stars">
                                                <?php 
                                                $rating = round($doctor['rating']);
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $rating) {
                                                        echo '<i class="fas fa-star"></i>';
                                                    } else {
                                                        echo '<i class="far fa-star"></i>';
                                                    }
                                                }
                                                ?>
                                            </span>
                                            <span class="ms-2 text-muted">(<?php echo $doctor['reviews']; ?> reviews)</span>
                                        </div>
                                        <p class="card-text mb-2"><i class="fas fa-graduation-cap text-muted me-2"></i><?php echo htmlspecialchars($doctor['qualification']); ?></p>
                                        <p class="card-text mb-2"><i class="fas fa-history text-muted me-2"></i><?php echo htmlspecialchars($doctor['experience']); ?> of experience</p>
                                        <p class="card-text mb-2"><i class="fas fa-map-marker-alt text-muted me-2"></i><?php echo htmlspecialchars($doctor['location']); ?></p>
                                        <p class="card-text mb-3"><i class="fas fa-calendar-alt text-muted me-2"></i>Available: <span class="availability-badge"><?php echo htmlspecialchars($doctor['availability']); ?></span></p>
                                        <p class="card-text small text-muted"><?php echo htmlspecialchars($doctor['bio']); ?></p>
                                    </div>
                                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#doctorProfileModal<?php echo $doctor['id']; ?>">View Profile</button>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookAppointmentModal<?php echo $doctor['id']; ?>">Book Appointment</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center" role="alert">
                                <i class="fas fa-info-circle me-2"></i> No doctors found matching your search criteria. Please try a different search.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Doctor Profile Modal (Example for first doctor) -->
    <div class="modal fade" id="doctorProfileModal1" tabindex="-1" aria-labelledby="doctorProfileModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="doctorProfileModalLabel1">Doctor Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <img src="../img/doctor-1.jpg" alt="Dr. John Williams" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            <h5>Dr. John Williams</h5>
                            <p class="text-primary mb-1">Cardiology</p>
                            <div class="rating-stars mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="ms-2 text-muted">(124 reviews)</span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="fw-bold">About</h6>
                            <p>Dr. Williams is a board-certified cardiologist specializing in cardiovascular disease management and preventive cardiology. With 15 years of experience, he has successfully treated thousands of patients with various heart conditions.</p>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Qualification</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check-circle text-success me-2"></i>MD, Harvard Medical School</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Residency, Massachusetts General Hospital</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>Fellowship in Cardiology, Mayo Clinic</li>
                                        <li><i class="fas fa-check-circle text-success me-2"></i>FACC (Fellow of the American College of Cardiology)</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Specializations</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-circle text-primary me-2 small"></i>Preventive Cardiology</li>
                                        <li><i class="fas fa-circle text-primary me-2 small"></i>Heart Failure Management</li>
                                        <li><i class="fas fa-circle text-primary me-2 small"></i>Coronary Artery Disease</li>
                                        <li><i class="fas fa-circle text-primary me-2 small"></i>Hypertension</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <h6 class="fw-bold">Available Days & Time</h6>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="card mb-2">
                                        <div class="card-body p-2 text-center">
                                            <h6 class="mb-1">Monday</h6>
                                            <p class="mb-0 small">9:00 AM - 4:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card mb-2">
                                        <div class="card-body p-2 text-center">
                                            <h6 class="mb-1">Wednesday</h6>
                                            <p class="mb-0 small">9:00 AM - 4:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card mb-2">
                                        <div class="card-body p-2 text-center">
                                            <h6 class="mb-1">Friday</h6>
                                            <p class="mb-0 small">9:00 AM - 4:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="fw-bold">Patient Reviews</h6>
                            <div class="mb-3">
                                <div class="d-flex mb-2">
                                    <div class="flex-shrink-0">
                                        <img src="../img/patient-1.jpg" class="rounded-circle" alt="Patient" style="width: 50px; height: 50px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">Jane Smith</h6>
                                        <div class="rating-stars mb-1">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <p class="mb-1">Dr. Williams is an excellent doctor who took the time to listen to all my concerns. His diagnosis was spot on and the treatment worked perfectly.</p>
                                        <small class="text-muted">2 months ago</small>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="../img/patient-2.jpg" class="rounded-circle" alt="Patient" style="width: 50px; height: 50px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">Robert Johnson</h6>
                                        <div class="rating-stars mb-1">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <p class="mb-1">Highly professional and knowledgeable. Dr. Williams explained everything clearly and answered all my questions patiently.</p>
                                        <small class="text-muted">3 months ago</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookAppointmentModal1">Book Appointment</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Book Appointment Modal (Example for first doctor) -->
    <div class="modal fade" id="bookAppointmentModal1" tabindex="-1" aria-labelledby="bookAppointmentModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookAppointmentModalLabel1">Book Appointment with Dr. John Williams</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="appointmentDate" class="form-label">Select Date</label>
                            <input type="date" class="form-control" id="appointmentDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentTime" class="form-label">Select Time Slot</label>
                            <select class="form-select" id="appointmentTime" required>
                                <option value="" selected disabled>Choose a time slot</option>
                                <option value="09:00 AM">09:00 AM</option>
                                <option value="10:00 AM">10:00 AM</option>
                                <option value="11:00 AM">11:00 AM</option>
                                <option value="01:00 PM">01:00 PM</option>
                                <option value="02:00 PM">02:00 PM</option>
                                <option value="03:00 PM">03:00 PM</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentType" class="form-label">Appointment Type</label>
                            <select class="form-select" id="appointmentType" required>
                                <option value="" selected disabled>Select type</option>
                                <option value="Regular Check-up">Regular Check-up</option>
                                <option value="Consultation">Consultation</option>
                                <option value="Follow-up">Follow-up</option>
                                <option value="Emergency">Emergency</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentReason" class="form-label">Reason for Visit</label>
                            <textarea class="form-control" id="appointmentReason" rows="3" placeholder="Please describe your symptoms or reason for this appointment"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="insuranceCheck">
                            <label class="form-check-label" for="insuranceCheck">
                                I'll be using insurance for this visit
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Confirm Appointment</button>
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