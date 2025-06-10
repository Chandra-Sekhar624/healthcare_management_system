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

// Placeholder data for messages (in a real system, you would fetch this from the database)
$contacts = [
    [
        'id' => 1,
        'name' => 'Dr. John Williams',
        'specialty' => 'Cardiology',
        'profile_img' => '../img/doctor-1.jpg',
        'status' => 'online',
        'last_active' => 'Now'
    ],
    [
        'id' => 2,
        'name' => 'Dr. Emily Rodriguez',
        'specialty' => 'Endocrinology',
        'profile_img' => '../img/doctor-2.jpg',
        'status' => 'offline',
        'last_active' => '5 hours ago'
    ],
    [
        'id' => 3,
        'name' => 'Dr. Michael Brown',
        'specialty' => 'Orthopedics',
        'profile_img' => '../img/doctor-3.jpg',
        'status' => 'offline',
        'last_active' => '1 day ago'
    ],
    [
        'id' => 4,
        'name' => 'Nurse Sarah Johnson',
        'specialty' => 'Primary Care',
        'profile_img' => '../img/nurse-1.jpg',
        'status' => 'online',
        'last_active' => 'Now'
    ],
    [
        'id' => 5,
        'name' => 'Front Desk',
        'specialty' => 'Appointments',
        'profile_img' => '../img/staff-1.jpg',
        'status' => 'online',
        'last_active' => 'Now'
    ]
];

// Placeholder conversation data
$conversations = [
    // Conversation with Dr. John Williams
    1 => [
        [
            'sender_id' => 1,
            'receiver_id' => $patient_id,
            'message' => 'Good morning! I\'ve reviewed your recent lab results and your blood pressure looks much better. How are you feeling with the new medication?',
            'timestamp' => '2023-06-15 09:30:00',
            'read' => true,
            'files' => []
        ],
        [
            'sender_id' => $patient_id,
            'receiver_id' => 1,
            'message' => 'Good morning Dr. Williams. I\'m feeling much better with the new medication. The side effects are minimal compared to the previous one.',
            'timestamp' => '2023-06-15 09:45:00',
            'read' => true,
            'files' => []
        ],
        [
            'sender_id' => 1,
            'receiver_id' => $patient_id,
            'message' => 'That\'s great to hear! Please continue with the current dosage, and we\'ll do another check-up in 2 weeks. If you experience any dizziness or fatigue, let me know immediately.',
            'timestamp' => '2023-06-15 10:00:00',
            'read' => true,
            'files' => []
        ],
        [
            'sender_id' => $patient_id,
            'receiver_id' => 1,
            'message' => 'Will do. Thank you for your help!',
            'timestamp' => '2023-06-15 10:05:00',
            'read' => true,
            'files' => []
        ],
        [
            'sender_id' => 1,
            'receiver_id' => $patient_id,
            'message' => 'Your lab results are now available. I\'ve attached them for your reference.',
            'timestamp' => '2023-06-20 14:30:00',
            'read' => false,
            'files' => ['Blood Test Results.pdf']
        ]
    ],
    
    // Conversation with Dr. Emily Rodriguez
    2 => [
        [
            'sender_id' => 2,
            'receiver_id' => $patient_id,
            'message' => 'Hello! I wanted to check in on how you\'re managing your blood sugar levels with the new diet plan we discussed.',
            'timestamp' => '2023-06-10 11:20:00',
            'read' => true,
            'files' => []
        ],
        [
            'sender_id' => $patient_id,
            'receiver_id' => 2,
            'message' => 'Hi Dr. Rodriguez. The new diet plan is working well. My morning readings have been between 110-125 for the past week.',
            'timestamp' => '2023-06-10 11:45:00',
            'read' => true,
            'files' => []
        ],
        [
            'sender_id' => 2,
            'receiver_id' => $patient_id,
            'message' => 'That\'s progress! Keep following the plan and monitoring your levels. I\'ve shared an updated meal plan that might help further.',
            'timestamp' => '2023-06-10 13:15:00',
            'read' => true,
            'files' => ['Diabetes Diet Plan.pdf']
        ]
    ],
    
    // Conversation with Front Desk
    5 => [
        [
            'sender_id' => $patient_id,
            'receiver_id' => 5,
            'message' => 'Hello, I need to reschedule my appointment with Dr. Williams that was set for next Tuesday.',
            'timestamp' => '2023-06-18 15:30:00',
            'read' => true,
            'files' => []
        ],
        [
            'sender_id' => 5,
            'receiver_id' => $patient_id,
            'message' => 'Hi there! I\'d be happy to help you reschedule. Dr. Williams has availability on Thursday (June 29) at 10:00 AM or Friday (June 30) at 2:00 PM. Would either of those work for you?',
            'timestamp' => '2023-06-18 15:45:00',
            'read' => true,
            'files' => []
        ],
        [
            'sender_id' => $patient_id,
            'receiver_id' => 5,
            'message' => 'Thursday at 10:00 AM works perfectly for me.',
            'timestamp' => '2023-06-18 16:00:00',
            'read' => true,
            'files' => []
        ],
        [
            'sender_id' => 5,
            'receiver_id' => $patient_id,
            'message' => 'Great! I\'ve rescheduled your appointment with Dr. Williams for Thursday, June 29, at 10:00 AM. You\'ll receive a confirmation email shortly. Let me know if you need anything else!',
            'timestamp' => '2023-06-18 16:10:00',
            'read' => true,
            'files' => []
        ]
    ]
];

// Get active conversation (if any)
$active_contact_id = isset($_GET['contact']) ? intval($_GET['contact']) : 1;
$active_contact = null;
$active_conversation = [];

// Find active contact
foreach ($contacts as $contact) {
    if ($contact['id'] == $active_contact_id) {
        $active_contact = $contact;
        break;
    }
}

// Get conversation messages
if (isset($conversations[$active_contact_id])) {
    $active_conversation = $conversations[$active_contact_id];
}

// Count unread messages for each contact
$unread_counts = [];
foreach ($contacts as $contact) {
    $contact_id = $contact['id'];
    $unread_counts[$contact_id] = 0;
    
    if (isset($conversations[$contact_id])) {
        foreach ($conversations[$contact_id] as $message) {
            if ($message['sender_id'] != $patient_id && !$message['read']) {
                $unread_counts[$contact_id]++;
            }
        }
    }
}

// Total unread messages
$total_unread = array_sum($unread_counts);

// Process sending a new message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $message_text = trim($_POST['message_text']);
    $recipient_id = intval($_POST['recipient_id']);
    
    if (!empty($message_text) && $recipient_id > 0) {
        // In a real application, you would save this to the database
        // For this demo, we'll just simulate adding it to our array
        $new_message = [
            'sender_id' => $patient_id,
            'receiver_id' => $recipient_id,
            'message' => $message_text,
            'timestamp' => date('Y-m-d H:i:s'),
            'read' => true,
            'files' => []
        ];
        
        // Add to conversation array (Note: This is just for demo purposes)
        if (!isset($conversations[$recipient_id])) {
            $conversations[$recipient_id] = [];
        }
        $conversations[$recipient_id][] = $new_message;
        $active_conversation = $conversations[$recipient_id];
        
        // Redirect to avoid form resubmission
        header("Location: messages.php?contact=" . $recipient_id);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | Patient Dashboard | HealthConnect</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .messages-container {
            height: calc(100vh - 200px);
            min-height: 500px;
        }
        .contacts-list {
            height: 100%;
            overflow-y: auto;
            border-right: 1px solid #e0e0e0;
        }
        .messages-area {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .message-header {
            border-bottom: 1px solid #e0e0e0;
            padding: 15px;
        }
        .messages-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .message-input {
            border-top: 1px solid #e0e0e0;
            padding: 15px;
            background-color: #fff;
        }
        .contact-item {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .contact-item:hover, .contact-item.active {
            background-color: #f0f7ff;
        }
        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
        .status-online {
            background-color: #28a745;
        }
        .status-offline {
            background-color: #6c757d;
        }
        .message-bubble {
            max-width: 75%;
            padding: 10px 15px;
            border-radius: 18px;
            margin-bottom: 10px;
            position: relative;
        }
        .message-received {
            background-color: #e9ecef;
            border-bottom-left-radius: 5px;
            align-self: flex-start;
        }
        .message-sent {
            background-color: #d1e7fc;
            border-bottom-right-radius: 5px;
            align-self: flex-end;
        }
        .message-time {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 5px;
        }
        .message-file {
            display: flex;
            align-items: center;
            padding: 5px 10px;
            background-color: #f1f1f1;
            border-radius: 4px;
            margin-top: 5px;
        }
        .message-file i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        .search-box {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .unread-badge {
            background-color: #dc3545;
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
                    <h1 class="h3 mb-0 text-gray-800">Messages</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                        <i class="fas fa-plus fa-sm text-white-50 me-1"></i> New Message
                    </a>
                </div>

                <!-- Messages Section -->
                <div class="card shadow mb-4">
                    <div class="card-body p-0">
                        <div class="row g-0 messages-container">
                            <!-- Contacts List -->
                            <div class="col-md-4 col-lg-3 contacts-list">
                                <div class="search-box">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search contacts...">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Contacts -->
                                <?php foreach ($contacts as $contact): ?>
                                    <a href="messages.php?contact=<?php echo $contact['id']; ?>" class="text-decoration-none text-dark">
                                        <div class="contact-item d-flex align-items-center <?php echo $contact['id'] == $active_contact_id ? 'active' : ''; ?>">
                                            <div class="position-relative me-3">
                                                <img src="<?php echo htmlspecialchars($contact['profile_img']); ?>" class="rounded-circle" width="50" height="50" alt="<?php echo htmlspecialchars($contact['name']); ?>">
                                                <span class="position-absolute bottom-0 end-0 status-indicator <?php echo $contact['status'] == 'online' ? 'status-online' : 'status-offline'; ?>"></span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0"><?php echo htmlspecialchars($contact['name']); ?></h6>
                                                <small class="text-muted"><?php echo htmlspecialchars($contact['specialty']); ?></small>
                                            </div>
                                            <?php if ($unread_counts[$contact['id']] > 0): ?>
                                                <span class="badge rounded-pill unread-badge"><?php echo $unread_counts[$contact['id']]; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Messages Area -->
                            <div class="col-md-8 col-lg-9 messages-area">
                                <?php if ($active_contact): ?>
                                    <!-- Message Header -->
                                    <div class="message-header d-flex align-items-center">
                                        <div class="position-relative me-3">
                                            <img src="<?php echo htmlspecialchars($active_contact['profile_img']); ?>" class="rounded-circle" width="50" height="50" alt="<?php echo htmlspecialchars($active_contact['name']); ?>">
                                            <span class="position-absolute bottom-0 end-0 status-indicator <?php echo $active_contact['status'] == 'online' ? 'status-online' : 'status-offline'; ?>"></span>
                                        </div>
                                        <div>
                                            <h5 class="mb-0"><?php echo htmlspecialchars($active_contact['name']); ?></h5>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($active_contact['specialty']); ?> 
                                                • <?php echo $active_contact['status'] == 'online' ? 'Online' : 'Last active ' . htmlspecialchars($active_contact['last_active']); ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- Messages Body -->
                                    <div class="messages-body">
                                        <div class="d-flex flex-column">
                                            <?php foreach ($active_conversation as $message): ?>
                                                <?php
                                                // Format the timestamp
                                                $timestamp = new DateTime($message['timestamp']);
                                                $formatted_time = $timestamp->format('M j, Y g:i A');
                                                
                                                // Check if this is a sent or received message
                                                $is_sent = $message['sender_id'] == $patient_id;
                                                $bubble_class = $is_sent ? 'message-sent' : 'message-received';
                                                ?>
                                                
                                                <div class="d-flex <?php echo $is_sent ? 'justify-content-end' : 'justify-content-start'; ?> mb-3">
                                                    <div class="message-bubble <?php echo $bubble_class; ?>">
                                                        <div class="message-text"><?php echo nl2br(htmlspecialchars($message['message'])); ?></div>
                                                        
                                                        <?php if (!empty($message['files'])): ?>
                                                            <?php foreach ($message['files'] as $file): ?>
                                                                <div class="message-file">
                                                                    <i class="fas fa-file-pdf"></i>
                                                                    <span><?php echo htmlspecialchars($file); ?></span>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                        
                                                        <div class="message-time">
                                                            <?php echo $formatted_time; ?>
                                                            <?php if ($is_sent): ?>
                                                                <i class="fas fa-check-double ms-1 <?php echo $message['read'] ? 'text-primary' : 'text-muted'; ?>"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Message Input -->
                                    <div class="message-input">
                                        <form action="messages.php?contact=<?php echo $active_contact_id; ?>" method="post">
                                            <div class="input-group">
                                                <input type="hidden" name="recipient_id" value="<?php echo $active_contact_id; ?>">
                                                <input type="text" name="message_text" class="form-control" placeholder="Type your message here..." required>
                                                <button class="btn btn-primary" type="submit" name="send_message">
                                                    <i class="fas fa-paper-plane me-1"></i> Send
                                                </button>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2">
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary me-2" title="Attach file">
                                                        <i class="fas fa-paperclip"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Add emoji">
                                                        <i class="far fa-smile"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex justify-content-center align-items-center h-100">
                                        <div class="text-center">
                                            <i class="fas fa-envelope fa-4x text-muted mb-3"></i>
                                            <h5>Select a contact to start messaging</h5>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Message Modal -->
    <div class="modal fade" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newMessageModalLabel">New Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="recipient" class="form-label">Recipient</label>
                            <select class="form-select" id="recipient" required>
                                <option value="" selected disabled>Select recipient</option>
                                <?php foreach ($contacts as $contact): ?>
                                    <option value="<?php echo $contact['id']; ?>"><?php echo htmlspecialchars($contact['name']); ?> (<?php echo htmlspecialchars($contact['specialty']); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="messageSubject" class="form-label">Subject (Optional)</label>
                            <input type="text" class="form-control" id="messageSubject" placeholder="Enter subject">
                        </div>
                        <div class="mb-3">
                            <label for="messageContent" class="form-label">Message</label>
                            <textarea class="form-control" id="messageContent" rows="5" placeholder="Type your message here..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="messageAttachment" class="form-label">Attachments (Optional)</label>
                            <input class="form-control" type="file" id="messageAttachment">
                            <div class="form-text">You can attach files up to 10MB in size.</div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="messagePriority">
                            <label class="form-check-label" for="messagePriority">
                                Mark as urgent
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="window.location.href='messages.php'">Send Message</button>
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

        // Scroll messages body to bottom on page load
        document.addEventListener('DOMContentLoaded', function() {
            const messagesBody = document.querySelector('.messages-body');
            if (messagesBody) {
                messagesBody.scrollTop = messagesBody.scrollHeight;
            }
        });
    </script>
</body>
</html>