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
$patients = [
    [
        'id' => 1,
        'name' => 'Jane Smith',
        'age' => 35,
        'gender' => 'Female',
        'email' => 'jane.smith@example.com',
        'phone' => '(123) 456-7890',
        'address' => '123 Main St, Anytown, USA',
        'registration_date' => '2023-01-15',
        'last_visit' => '2023-06-10',
        'blood_group' => 'A+',
        'status' => 'active'
    ],
    [
        'id' => 2,
        'name' => 'Robert Johnson',
        'age' => 42,
        'gender' => 'Male',
        'email' => 'robert.johnson@example.com',
        'phone' => '(234) 567-8901',
        'address' => '456 Oak Ave, Somewhere, USA',
        'registration_date' => '2023-02-22',
        'last_visit' => '2023-05-28',
        'blood_group' => 'O+',
        'status' => 'active'
    ],
    [
        'id' => 3,
        'name' => 'Emily Williams',
        'age' => 29,
        'gender' => 'Female',
        'email' => 'emily.williams@example.com',
        'phone' => '(345) 678-9012',
        'address' => '789 Pine Rd, Nowhere, USA',
        'registration_date' => '2023-03-10',
        'last_visit' => '2023-06-15',
        'blood_group' => 'B-',
        'status' => 'active'
    ],
    [
        'id' => 4,
        'name' => 'Thomas Walker',
        'age' => 58,
        'gender' => 'Male',
        'email' => 'thomas.walker@example.com',
        'phone' => '(456) 789-0123',
        'address' => '101 Elm Blvd, Anywhere, USA',
        'registration_date' => '2023-04-05',
        'last_visit' => '2023-06-08',
        'blood_group' => 'AB+',
        'status' => 'inactive'
    ],
    [
        'id' => 5,
        'name' => 'Julia Martinez',
        'age' => 31,
        'gender' => 'Female',
        'email' => 'julia.martinez@example.com',
        'phone' => '(567) 890-1234',
        'address' => '202 Cedar Ln, Everywhere, USA',
        'registration_date' => '2023-05-12',
        'last_visit' => '2023-06-01',
        'blood_group' => 'O-',
        'status' => 'active'
    ]
];

// Handle patient status changes (in a real system, this would update the database)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $patient_id = $_GET['id'];
    
    // Placeholder for status change actions
    $status_message = '';
    
    if ($action === 'activate') {
        $status_message = "Patient ID $patient_id has been activated.";
    } elseif ($action === 'deactivate') {
        $status_message = "Patient ID $patient_id has been deactivated.";
    } elseif ($action === 'delete') {
        $status_message = "Patient ID $patient_id has been deleted.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients | Admin Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                    <h1 class="h3 mb-0 text-gray-800">Manage Patients</h1>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                        <i class="fas fa-user-plus fa-sm text-white-50 me-2"></i>Add New Patient
                    </a>
                </div>

                <?php if (isset($status_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($status_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Patient Filters -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Filters</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="genderFilter">
                                    <option value="">All Genders</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search patients..." id="searchPatients">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-secondary w-100" id="resetFilters">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patients List -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Patients List</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="patientsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Last Visit</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($patients as $patient): ?>
                                        <tr>
                                            <td><?php echo $patient['id']; ?></td>
                                            <td><?php echo htmlspecialchars($patient['name']); ?></td>
                                            <td><?php echo $patient['age']; ?></td>
                                            <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                            <td><?php echo htmlspecialchars($patient['email']); ?></td>
                                            <td><?php echo htmlspecialchars($patient['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($patient['last_visit']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $patient['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                    <?php echo ucfirst(htmlspecialchars($patient['status'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewPatientModal<?php echo $patient['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPatientModal<?php echo $patient['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($patient['status'] === 'active'): ?>
                                                        <a href="?action=deactivate&id=<?php echo $patient['id']; ?>" class="btn btn-warning">
                                                            <i class="fas fa-ban"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="?action=activate&id=<?php echo $patient['id']; ?>" class="btn btn-success">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePatientModal<?php echo $patient['id']; ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- View Patient Modal -->
                                        <div class="modal fade" id="viewPatientModal<?php echo $patient['id']; ?>" tabindex="-1" aria-labelledby="viewPatientModalLabel<?php echo $patient['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewPatientModalLabel<?php echo $patient['id']; ?>">
                                                            Patient Details: <?php echo htmlspecialchars($patient['name']); ?>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-4 text-center mb-3">
                                                                <img src="../img/patient-<?php echo $patient['id']; ?>.jpg" class="img-fluid rounded-circle mb-3" alt="Patient Avatar" onerror="this.src='../img/patient-avatar.jpg';" style="width: 150px; height: 150px; object-fit: cover;">
                                                                <h5><?php echo htmlspecialchars($patient['name']); ?></h5>
                                                                <p class="mb-1">ID: PAT<?php echo str_pad($patient['id'], 5, '0', STR_PAD_LEFT); ?></p>
                                                                <span class="badge bg-<?php echo $patient['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                                    <?php echo ucfirst(htmlspecialchars($patient['status'])); ?>
                                                                </span>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <h6 class="text-primary mb-3">Personal Information</h6>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Age:</div>
                                                                    <div class="col-md-8"><?php echo $patient['age']; ?> years</div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Gender:</div>
                                                                    <div class="col-md-8"><?php echo htmlspecialchars($patient['gender']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Blood Group:</div>
                                                                    <div class="col-md-8"><?php echo htmlspecialchars($patient['blood_group']); ?></div>
                                                                </div>
                                                                
                                                                <h6 class="text-primary mb-3 mt-4">Contact Information</h6>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Email:</div>
                                                                    <div class="col-md-8"><?php echo htmlspecialchars($patient['email']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Phone:</div>
                                                                    <div class="col-md-8"><?php echo htmlspecialchars($patient['phone']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Address:</div>
                                                                    <div class="col-md-8"><?php echo htmlspecialchars($patient['address']); ?></div>
                                                                </div>
                                                                
                                                                <h6 class="text-primary mb-3 mt-4">Medical History</h6>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Registration Date:</div>
                                                                    <div class="col-md-8"><?php echo htmlspecialchars($patient['registration_date']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Last Visit:</div>
                                                                    <div class="col-md-8"><?php echo htmlspecialchars($patient['last_visit']); ?></div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Medical Conditions:</div>
                                                                    <div class="col-md-8">
                                                                        <?php if ($patient['id'] % 2 == 0): ?>
                                                                            Diabetes, Hypertension
                                                                        <?php elseif ($patient['id'] % 3 == 0): ?>
                                                                            Asthma
                                                                        <?php else: ?>
                                                                            No major conditions
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Allergies:</div>
                                                                    <div class="col-md-8">
                                                                        <?php if ($patient['id'] % 3 == 0): ?>
                                                                            Penicillin
                                                                        <?php elseif ($patient['id'] % 4 == 0): ?>
                                                                            Peanuts, Shellfish
                                                                        <?php else: ?>
                                                                            None
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <a href="#" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editPatientModal<?php echo $patient['id']; ?>">Edit Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Delete Patient Confirmation Modal -->
                                        <div class="modal fade" id="deletePatientModal<?php echo $patient['id']; ?>" tabindex="-1" aria-labelledby="deletePatientModalLabel<?php echo $patient['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deletePatientModalLabel<?php echo $patient['id']; ?>">Confirm Delete</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete the patient record for <?php echo htmlspecialchars($patient['name']); ?>? This action cannot be undone.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <a href="?action=delete&id=<?php echo $patient['id']; ?>" class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Patient Modal -->
    <div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPatientModalLabel">Add New Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="date_of_birth" required>
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="" selected disabled>Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label for="bloodGroup" class="form-label">Blood Group</label>
                                <select class="form-select" id="bloodGroup" name="blood_group">
                                    <option value="" selected disabled>Select blood group</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="maritalStatus" class="form-label">Marital Status</label>
                                <select class="form-select" id="maritalStatus" name="marital_status">
                                    <option value="" selected disabled>Select marital status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="emergencyContactName" class="form-label">Emergency Contact Name</label>
                                <input type="text" class="form-control" id="emergencyContactName" name="emergency_contact_name">
                            </div>
                            <div class="col-md-6">
                                <label for="emergencyContactPhone" class="form-label">Emergency Contact Phone</label>
                                <input type="tel" class="form-control" id="emergencyContactPhone" name="emergency_contact_phone">
                            </div>
                            <div class="col-12">
                                <label for="medicalConditions" class="form-label">Medical Conditions (if any)</label>
                                <textarea class="form-control" id="medicalConditions" name="medical_conditions" rows="2"></textarea>
                            </div>
                            <div class="col-12">
                                <label for="allergies" class="form-label">Allergies (if any)</label>
                                <textarea class="form-control" id="allergies" name="allergies" rows="2"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Password must be at least 8 characters long.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                            </div>
                            <div class="col-12">
                                <label for="profileImage" class="form-label">Profile Image</label>
                                <input class="form-control" type="file" id="profileImage" name="profile_image">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Patient</button>
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