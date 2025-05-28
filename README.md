# HealthConnect - Premium Online Health System Platform

HealthConnect is a state-of-the-art online health system platform designed to connect doctors and patients through a seamless digital experience. This responsive web application provides a comprehensive solution for healthcare management, appointment scheduling, and medical record keeping.

## Features

- **User Authentication**: Secure registration and login for doctors, patients, and administrators
- **Doctor Profiles**: Comprehensive profiles with specialties, experience, and availability
- **Patient Management**: Complete patient history and medical record tracking
- **Appointment Scheduling**: Easy-to-use appointment booking system with reminders
- **Medical Records**: Secure storage and access to medical records
- **Responsive Design**: Mobile-first approach that works on any device
- **Admin Dashboard**: Complete control over platform settings and user management

## Technology Stack

- **Frontend**: HTML, CSS, JavaScript, Bootstrap 5.1.3
- **Backend**: PHP
- **Database**: MySQL
- **Icons**: Font Awesome 6.0.0

## Installation

1. **Clone the repository**
   ```
   git clone https://github.com/yourusername/healthconnect.git
   cd healthconnect
   ```

2. **Set up the database**
   - Create a MySQL database
   - Import the database schema from `database.sql`
   ```
   mysql -u username -p healthconnect < database.sql
   ```

3. **Configure database connection**
   - Update database credentials in `includes/config.php`

4. **Deploy to web server**
   - Place the files in your web server directory (e.g., htdocs for XAMPP)
   - Ensure PHP is configured correctly

5. **Access the application**
   - Open your browser and navigate to the application URL

## Demo Accounts

- **Admin**: admin@example.com / password............misrosoft eags
- **Doctor**: doctor@example.com / password..........fairfox
- **Patient**: patient@example.com / password........chrom

## Project Structure

```
healthconnect/
├── admin/              # Admin dashboard files
├── css/                # CSS stylesheets
│   └── style.css       # Main stylesheet
├── img/                # Images and icons
├── includes/           # PHP includes
│   └── config.php      # Database configuration
├── js/                 # JavaScript files
│   └── main.js         # Main JavaScript file
├── index.php           # Homepage
├── login.php           # Login page
├── register.php        # Registration page
├── registration-success.php  # Registration success page
├── database.sql        # Database schema
└── README.md           # Project documentation
```

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser

## Customization

You can customize the platform to suit your needs:

- Edit the CSS in `css/style.css` to change the appearance
- Modify PHP files to add or change functionality
- Update content in HTML files to match your branding

## Security Features

- Password hashing using bcrypt
- Form validation and sanitization
- Session management
- CSRF protection
- SQL injection prevention

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contact

For support or inquiries, please contact:
- Email: support@healthconnect.com
- Website: www.healthconnect.com

---

Designed and developed with ❤️ for better healthcare. 