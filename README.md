# Watoto Church Wedding Booking System

A comprehensive wedding booking and management system built with CodeIgniter 4 for Watoto Church. This system allows couples to book wedding venues, manage their wedding applications, and enables administrators to manage bookings, pastors, campuses, and generate reports.

## Features

### User Features
- **User Registration & Authentication**: Secure registration with email verification via OTP
- **Wedding Application**: Multi-step application form for wedding bookings
- **Dashboard**: Personal dashboard showing application status, timeline, and progress
- **Document Management**: Upload and manage required documents
- **Payment Tracking**: View payment status and history
- **Messaging**: Communicate with administrators
- **Application Timeline**: Track wedding preparation progress

### Admin Features
- **Booking Management**: Approve, reject, or manage wedding bookings
- **Campus Management**: Manage wedding venues and their details
- **Pastor Management**: Manage pastors and their availability
- **User Management**: View and manage user accounts
- **Blocked Dates**: Manage dates when venues are unavailable
- **Reports**: Generate comprehensive reports on bookings, revenue, and performance
- **Calendar View**: Visual calendar for booking management
- **Payment Management**: Track and update payment statuses
- **Counseling Management**: Track pre-marital counseling sessions

## Requirements

- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache/Nginx web server
- Composer (for dependency management)
- Required PHP extensions:
  - intl
  - mbstring
  - json
  - mysqlnd (for MySQL)
  - libcurl (for HTTP requests)

## Installation

1. **Clone or download the project**
   ```bash
   cd /path/to/your/web/directory
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp env .env
   ```
   Edit `.env` file and configure:
   - Database connection settings
   - Base URL
   - Email settings (for OTP verification)
   - Encryption key (generate with: `php spark key:generate`)

4. **Set up the database**
   - Create a MySQL database named `watoto_wedding` (or your preferred name)
   - Import the database schema:
     ```bash
     mysql -u root -p watoto_wedding < database_schema.sql
     ```
   - Or run migrations:
     ```bash
     php spark migrate
     ```

5. **Set permissions**
   ```bash
   chmod -R 755 writable/
   ```

6. **Configure web server**
   - Point your web server document root to the `public/` directory
   - For Apache, ensure mod_rewrite is enabled
   - For Nginx, configure proper rewrite rules

## Configuration

### Database Configuration
Edit `.env` file:
```env
database.default.hostname = localhost
database.default.database = watoto_wedding
database.default.username = your_username
database.default.password = your_password
database.default.port = 3306
```

### Email Configuration
For OTP verification, configure SMTP settings in `.env`:
```env
email.SMTPHost = smtp.gmail.com
email.SMTPUser = your-email@gmail.com
email.SMTPPass = your-app-password
email.SMTPPort = 587
email.SMTPCrypto = tls
email.fromEmail = noreply@watotochurch.org
email.fromName = Watoto Church Wedding Booking
```

**Note**: For Gmail, you need to use an App Password, not your regular password.

### Base URL Configuration
Update the base URL in `.env`:
```env
app.baseURL = 'http://localhost:8888/wedding/public/'
```

## Default Credentials

After installation, you can log in with the default admin account:
- **Email**: admin@watoto.com
- **Password**: admin123

**⚠️ IMPORTANT**: Change the default admin password immediately after first login!

## Project Structure

```
wedding/
├── app/                    # Application code
│   ├── Config/            # Configuration files
│   ├── Controllers/       # Controller classes
│   ├── Models/            # Model classes
│   ├── Views/             # View templates
│   ├── Libraries/         # Custom libraries
│   └── Database/          # Migrations and seeds
├── public/                # Public web root
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   ├── images/           # Image assets
│   └── index.php         # Entry point
├── writable/             # Writable directories
│   ├── cache/            # Cache files
│   ├── logs/             # Log files
│   ├── session/          # Session files
│   └── uploads/          # Uploaded files
├── vendor/               # Composer dependencies
└── .env                  # Environment configuration (create from env)
```

## Security Features

- CSRF protection enabled
- Password hashing using bcrypt
- Email verification via OTP
- Math captcha for registration
- Input validation and sanitization
- SQL injection protection (via CodeIgniter's Query Builder)
- XSS protection
- Session security

## Development

### Running Migrations
```bash
php spark migrate
```

### Running Seeds
```bash
php spark db:seed SeedClassName
```

### Generating Encryption Key
```bash
php spark key:generate
```

### Clearing Cache
```bash
php spark cache:clear
```

## API Endpoints

The system includes API endpoints for AJAX requests:
- `GET /api/campuses/{id}/availability/{date}` - Check venue availability
- `GET /api/campuses/{id}/pastors` - Get pastors for a campus
- `GET /api/notifications` - Get user notifications
- `POST /api/notifications/{id}/read` - Mark notification as read
- `POST /api/quick-availability-check` - Quick availability check

## Troubleshooting

### Common Issues

1. **500 Internal Server Error**
   - Check file permissions on `writable/` directory
   - Check `.env` file configuration
   - Review error logs in `writable/logs/`

2. **Database Connection Error**
   - Verify database credentials in `.env`
   - Ensure database exists
   - Check MySQL service is running

3. **Email Not Sending**
   - Verify SMTP settings in `.env`
   - For Gmail, ensure App Password is used
   - Check firewall/port restrictions

4. **CSRF Token Errors**
   - Clear browser cookies
   - Check session configuration
   - Verify CSRF settings in `app/Config/Security.php`

## Contributing

When contributing to this project:
1. Follow PSR-12 coding standards
2. Write meaningful commit messages
3. Test your changes thoroughly
4. Update documentation as needed

## License

This project is proprietary software for Watoto Church.

## Support

For support, contact the development team or refer to the CodeIgniter 4 documentation:
- [CodeIgniter 4 User Guide](https://codeigniter.com/user_guide/)
- [CodeIgniter 4 Forum](https://forum.codeigniter.com/)

## Changelog

### Recent Improvements
- Enhanced security: CSRF token randomization enabled
- Improved code quality: Fixed TODOs and implemented missing functionality
- Better error handling and validation
- Document upload functionality implemented
- Progress tracking and timeline features
- Activity feed implementation

---

**Built with CodeIgniter 4** | **Watoto Church** © 2025
