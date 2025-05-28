<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthConnect | Premium Online Health System</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-heartbeat text-primary me-2"></i>
                <span class="fw-bold">HealthConnect</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto text-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#doctors">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item ms-2 mb-">
                        <a class="btn btn-outline-primary" href="login.php">Login</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header id="home" class="hero-section">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="display-4 fw-bold mb-4">Premium Healthcare Platform</h1>
                        <p class="lead mb-4">Connect with healthcare professionals and manage your appointments with our state-of-the-art platform. Experience healthcare in the digital age.</p>
                        <div class="d-flex gap-3">
                            <a href="register.php?type=patient" class="btn btn-primary btn-lg">I'm a Patient</a>
                            <a href="register.php?type=doctor" class="btn btn-outline-primary btn-lg">I'm a Doctor</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="img/hero-image.svg" alt="Healthcare Professionals" class="img-fluid">
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="features py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Why Choose HealthConnect?</h2>
                    <p class="section-subtitle">Our platform offers a seamless experience for both healthcare providers and patients</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h3>Expert Doctors</h3>
                        <p>Connect with verified healthcare professionals across all medical specialties.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3>Easy Scheduling</h3>
                        <p>Book, reschedule, or cancel appointments with just a few clicks.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Responsive Design</h3>
                        <p>Access our platform from any device with a fully responsive interface.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3>Medical History</h3>
                        <p>Keep track of your complete medical history in one secure place.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3>Notifications</h3>
                        <p>Receive timely reminders and updates about your appointments.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h3>Secure & Private</h3>
                        <p>Your health data is protected with industry-standard security measures.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="img/about-image.svg" alt="About HealthConnect" class="img-fluid rounded">
                </div>
                <div class="col-lg-6">
                    <h2 class="section-title">About HealthConnect</h2>
                    <p class="mb-4">HealthConnect is a state-of-the-art online health system platform designed to transform the healthcare experience by connecting doctors and patients seamlessly in the digital space.</p>
                    <p class="mb-4">Our mission is to make healthcare more accessible, efficient, and patient-centered through innovative technology and user-friendly design.</p>
                    <div class="row g-4 mt-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h5>Intuitive Interface</h5>
                                    <p>User-friendly design for all age groups</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h5>Secure Data</h5>
                                    <p>HIPAA-compliant data protection</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h5>24/7 Access</h5>
                                    <p>Manage your healthcare anytime</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h5>Expert Support</h5>
                                    <p>Dedicated team for assistance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Our Services</h2>
                    <p class="section-subtitle">Comprehensive healthcare solutions for patients and providers</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="service-card h-100">
                        <img src="img/service-appointment1.jpg" alt="Online Appointments" class="img-fluid rounded-top">
                        <div class="service-content">
                            <h3>Online Appointments</h3>
                            <p>Schedule and manage appointments with healthcare providers from the comfort of your home.</p>
                            <a href="#" class="btn btn-outline-primary">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card h-100">
                        <img src="img/service-records.jpg" alt="Medical Records" class="img-fluid rounded-top">
                        <div class="service-content">
                            <h3>Medical Records</h3>
                            <p>Securely store and access your complete medical history, test results, and prescriptions.</p>
                            <a href="#" class="btn btn-outline-primary">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card h-100">
                        <img src="img/service-consultation.jpg" alt="Online Consultation" class="img-fluid rounded-top">
                        <div class="service-content">
                            <h3>Online Consultation</h3>
                            <p>Connect with healthcare professionals through secure video consultations.</p>
                            <a href="#" class="btn btn-outline-primary">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card h-100">
                        <img src="img/service-specialist.jpg" alt="Specialist Referrals" class="img-fluid rounded-top">
                        <div class="service-content">
                            <h3>Specialist Referrals</h3>
                            <p>Get connected with specialists based on your healthcare needs and medical history.</p>
                            <a href="#" class="btn btn-outline-primary">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card h-100">
                        <img src="img/service-reminder.jpg" alt="Medication Reminders" class="img-fluid rounded-top">
                        <div class="service-content">
                            <h3>Medication Reminders</h3>
                            <p>Set up personalized reminders for medications and treatments.</p>
                            <a href="#" class="btn btn-outline-primary">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card h-100">
                        <img src="img/service-analytics.jpg" alt="Health Analytics" class="img-fluid rounded-top">
                        <div class="service-content">
                            <h3>Health Analytics</h3>
                            <p>Track your health metrics and visualize progress over time with intuitive dashboards.</p>
                            <a href="#" class="btn btn-outline-primary">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Doctor Section -->
    <section id="doctors" class="doctors py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Our Doctors</h2>
                    <p class="section-subtitle">Meet our team of experienced healthcare professionals</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="doctor-card h-100">
                        <img src="img/doctor-1.jpg" alt="Dr. Sarah Johnson" class="img-fluid rounded-top">
                        <div class="doctor-info">
                            <h4>Dr. Sarah Johnson</h4>
                            <p class="specialty">Cardiologist</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="ms-2">4.8</span>
                            </div>
                            <a href="#" class="btn btn-primary mt-3">Book Appointment</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="doctor-card h-100">
                        <img src="img/doctor-2.jpg" alt="Dr. Michael Chen" class="img-fluid rounded-top">
                        <div class="doctor-info">
                            <h4>Dr. Michael Chen</h4>
                            <p class="specialty">Neurologist</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="ms-2">5.0</span>
                            </div>
                            <a href="#" class="btn btn-primary mt-3">Book Appointment</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="doctor-card h-100">
                        <img src="img/doctor-3.jpg" alt="Dr. Emily Rodriguez" class="img-fluid rounded-top">
                        <div class="doctor-info">
                            <h4>Dr. Emily Rodriguez</h4>
                            <p class="specialty">Pediatrician</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <span class="ms-2">4.2</span>
                            </div>
                            <a href="#" class="btn btn-primary mt-3">Book Appointment</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="doctor-card h-100">
                        <img src="img/doctor-4.jpg" alt="Dr. James Wilson" class="img-fluid rounded-top">
                        <div class="doctor-info">
                            <h4>Dr. James Wilson</h4>
                            <p class="specialty">Dermatologist</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="ms-2">4.7</span>
                            </div>
                            <a href="#" class="btn btn-primary mt-3">Book Appointment</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="doctors.php" class="btn btn-outline-primary btn-lg">View All Doctors</a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">What Our Users Say</h2>
                    <p class="section-subtitle">Read testimonials from patients and healthcare providers</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="testimonial-card h-100">
                        <div class="testimonial-content">
                            <div class="rating mb-3">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="testimonial-text">"HealthConnect has completely transformed how I manage my healthcare. The appointment scheduling is seamless, and I love having all my medical records in one place."</p>
                            <div class="testimonial-author d-flex align-items-center mt-4">
                                <img src="img/patient-1.jpg" alt="Julia Martinez" class="rounded-circle">
                                <div class="ms-3">
                                    <h5 class="mb-0">Julia Martinez</h5>
                                    <p class="mb-0">Patient</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="testimonial-card h-100">
                        <div class="testimonial-content">
                            <div class="rating mb-3">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="testimonial-text">"As a doctor, I appreciate how HealthConnect streamlines my practice. The appointment management system is intuitive, and it helps me provide better care to my patients."</p>
                            <div class="testimonial-author d-flex align-items-center mt-4">
                                <img src="img/doctor-5.jpg" alt="Dr. Robert Kim" class="rounded-circle">
                                <div class="ms-3">
                                    <h5 class="mb-0">Dr. Robert Kim</h5>
                                    <p class="mb-0">Orthopedic Surgeon</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="testimonial-card h-100">
                        <div class="testimonial-content">
                            <div class="rating mb-3">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <p class="testimonial-text">"Finding the right specialist used to be challenging, but with HealthConnect, I was able to browse doctors' profiles and book an appointment within minutes. The platform is user-friendly and efficient."</p>
                            <div class="testimonial-author d-flex align-items-center mt-4">
                                <img src="img/patient-2.jpg" alt="Thomas Walker" class="rounded-circle">
                                <div class="ms-3">
                                    <h5 class="mb-0">Thomas Walker</h5>
                                    <p class="mb-0">Patient</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats py-5 bg-primary text-white">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">5000+</div>
                        <div class="stat-label">Registered Patients</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">300+</div>
                        <div class="stat-label">Expert Doctors</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">10000+</div>
                        <div class="stat-label">Appointments</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Satisfaction Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Contact Us</h2>
                    <p class="section-subtitle">Get in touch with our support team</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="contact-info p-4 h-100">
                        <h3>Contact Information</h3>
                        <p class="mb-4">Have questions or need assistance? Our team is here to help. Reach out to us using the contact information below.</p>
                        <div class="contact-item d-flex mb-3">
                            <div class="icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info">
                                <h5>Address</h5>
                                <p>123 Healthcare Ave, Medical District, New York, NY 10001</p>
                            </div>
                        </div>
                        <div class="contact-item d-flex mb-3">
                            <div class="icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="info">
                                <h5>Phone</h5>
                                <p>+1 (555) 123-4567</p>
                            </div>
                        </div>
                        <div class="contact-item d-flex mb-3">
                            <div class="icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info">
                                <h5>Email</h5>
                                <p>support@healthconnect.com</p>
                            </div>
                        </div>
                        <div class="contact-item d-flex">
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info">
                                <h5>Support Hours</h5>
                                <p>Monday - Friday: 8:00 AM - 8:00 PM<br>Saturday: 9:00 AM - 5:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-form p-4">
                        <h3>Send us a Message</h3>
                        <form method="POST" action="contact-save.php">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" id="n1" name="n1" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="e1" id="email" name="e1" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        <input type="text" id="s1" name="s1" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea id="me1" name="me1" rows="5" class="form-control" required></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta py-5 bg-primary text-white text-center">
        <div class="container">
            <h2 class="mb-4">Ready to Experience Premium Healthcare?</h2>
            <p class="lead mb-4">Join thousands of patients and healthcare providers on our platform</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="register.php" class="btn btn-light btn-lg">Get Started</a>
                <a href="#about" class="btn btn-outline-light btn-lg">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-5 bg-dark text-white">
        <div class="container">
            <div class="row g-4  text-xs-center">
                <div class="col-lg-4">
                    <div class="footer-info">
                        <a href="index.php" class="footer-logo d-flex align-items-center mb-3">
                            <i class="fas fa-heartbeat text-primary me-2"></i>
                            <span class="fw-bold fs-4">HealthConnect</span>
                        </a>
                        <p>Connecting healthcare professionals and patients through an innovative digital platform. Experience the future of healthcare management.</p>
                        <div class="social-links mt-3">
                            <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 ">
                    <h5>Quick Links</h5>
                    <ul class="footer-links ">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#doctors">Doctors</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>For Patients</h5>
                    <ul class="footer-links">
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php?type=patient">Register</a></li>
                        <li><a href="#">Find Doctors</a></li>
                        <li><a href="#">Book Appointment</a></li>
                        <li><a href="#">Patient Dashboard</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>For Doctors</h5>
                    <ul class="footer-links">
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php?type=doctor">Register</a></li>
                        <li><a href="#">Doctor Dashboard</a></li>
                        <li><a href="#">Manage Appointments</a></li>
                        <li><a href="#">Patient Records</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>Support</h5>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p>&copy; 2023 HealthConnect. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p>Designed with <i class="fas fa-heart text-danger"></i> for better healthcare</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <a href="#" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Bootstrap 5.1.3 JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="js/main.js"></script>
</body>
</html> 