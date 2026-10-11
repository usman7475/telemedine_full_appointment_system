# Telemedicine Platform

A web-based telemedicine application built with PHP and MySQL for managing patient-doctor communication, appointments, prescriptions, and admin operations.

This project allows patients to search doctors, book appointments, chat with doctors, view prescriptions, and submit feedback, while doctors can manage appointments, communicate with patients, and issue prescriptions. Administrators can monitor patients, doctors, and system activity.

## Features

- Patient registration and login
- Doctor registration and login
- Admin login and dashboard
- Doctor search and profile browsing
- Appointment booking and approval flow
- Real-time style messaging/chat system
- Prescription generation and viewing
- Patient feedback and rating system
- Notifications for doctors
- Admin management for patients, doctors, and reports

## Tech Stack

- PHP
- MySQL
- XAMPP / Apache
- Bootstrap / CSS / JavaScript
- HTML5

## Project Structure

```text
telemedicine/
├── admin/
│   ├── activity.php
│   ├── doctors.php
│   ├── home.php
│   ├── index.php
│   ├── logout.php
│   ├── patients.php
│   ├── reports.php
│   └── sidebar.php
├── doctor/
│   ├── appointment_status.php
│   ├── chat.php
│   ├── doctorprofile.php
│   ├── feedback.php
│   ├── logout.php
│   ├── notifications.php
│   ├── prescriptions.php
│   └── sidebar.php
├── Database sql file/
│   └── telemedicine.sql
├── admin_login.php
├── cancel_appointment.php
├── check.php
├── conn.php
├── doctorlogin.php
├── doctorsignup.php
├── feedback_form.php
├── footer.php
├── index.php
├── logout.php
├── mydoctors.php
├── myprescriptions.php
├── navbar.php
├── patientlogin.php
├── patientsignup.php
├── profile.php
├── style.css
├── users_chat.php
├── README.md
└── ...
```

## Requirements

Before running this project, make sure you have:

- XAMPP or WAMP installed
- PHP 7.4+ or newer
- MySQL database server
- A browser such as Chrome or Firefox

## Installation

1. Download or clone the project into your local web server directory.
   - For XAMPP: place the folder inside `C:/xampp/htdocs/`

2. Start Apache and MySQL from XAMPP Control Panel.

3. Create a database named `telemedicine` in phpMyAdmin.

4. Import the SQL file located at:
   - `Database sql file/telemedicine.sql`

5. Open `conn.php` and confirm the database credentials:

```php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'telemedicine';
```

6. Open the project in your browser:

```text
http://localhost/telemedicine
```

## Default Login Credentials

The project includes sample data in the SQL file.

### Admin
- Email: `admin@gmail.com`
- Password: `admin`

### Doctor
- Email: `usman@gmail.com`
- Password: `123`

### Patient
- Email: `aahil@gmail.com`
- Password: `123`

## User Roles

### Patient
- Sign up and login
- View doctor profiles
- Book appointments
- Read doctor messages
- View prescriptions
- Give feedback

### Doctor
- Sign up and login
- Manage profile and availability
- View patient appointments
- Send messages to patients
- Issue prescriptions
- Review feedback

### Admin
- Manage all patients and doctors
- View activity and reports
- Review platform information and records

## Important Notes

- This project is designed for local development and learning purposes.
- The app uses direct MySQL connections via `mysqli`.
- For production deployment, it is recommended to secure the application with:
  - password hashing
  - prepared statements
  - CSRF protection
  - user session management improvements
  - secure environment configuration

## Screenshots

Add screenshots here after deployment if needed.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the MIT License.

## Author

Built for a telemedicine management system project.

If you want, you can also customize this README for:
- GitHub project presentation
- README with screenshots
- Professional portfolio style
- Urdu/Hindi friendly version
