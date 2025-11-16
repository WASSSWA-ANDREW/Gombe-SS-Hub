# Gombe SS Hub Pro - School Management System

## Project Overview
Gombe SS Hub Pro is a comprehensive Laravel-based school management system designed for Gombe Secondary School. The system facilitates the collection, management, and analysis of data related to students, staff, and school operations with advanced reporting, analytics, and export capabilities.

## Key Features

### User Management
- Multi-level user roles: Super Admin, Admin, and regular users
- Secure login system with email and password authentication
- User profile management with update capabilities
- Dark mode toggle for user interface preference
- Role-based access control (RBAC) system

### Student Management
- Comprehensive student registration for O'Level and A'Level students
- Student health tracking with Medical Status (Healthy/Medical care) and Physical Health (Fit/Disabled) fields
- Detailed student profiles with academic and personal information
- Search, filter, and sort functionality for student records
- Student location mapping on Uganda map
- Student record export capabilities

### Staff Management
- Registration system for both private and government staff
- Staff health tracking with Medical Status and Physical Health fields
- Comprehensive staff profiles with qualification and employment details
- PDF generation for staff records
- Search and filter capabilities for staff management

### Academics Module
- **Subject Management**: Complete add/edit/delete functionality for O'Level and A'Level subjects
  - O'Level: General and optional subjects with practical flag
  - A'Level: Arts, science, and subsidiary subjects with stream selection
  - Multi-class assignment support for all subject types
- **Teacher Subject Assignment**: Assign subjects to teachers with level and specialty specifications
  - Dynamic subject filtering based on academic level
  - Flexible multi-class assignment with comma-separated values
  - Edit and delete capabilities for assignments
  - Level-aware field management (specialty for A'Level only)

### Dashboard
- Compact, information-rich welcome card with school overview
- Real-time statistics showing counts of students, staff, and system users
- Recent activity tracking
- Quick access to key system functions
- Analytics and data visualization

### Reporting & Export
- Comprehensive data reports generation
- Attendance tracking and reporting
- Academic performance analysis
- Staff and student distribution reports
- Export capabilities to Excel, PDF, and CSV formats
- Detailed demographic and statistical reports

### Communication
- Notification system for important updates and alerts
- In-app notification management
- Direct communication channels to administrators
- Sharing capabilities via various social media platforms
- Chatbot for answering system-related questions

### Additional Features
- Responsive design for all device types
- Accessibility features including screen reader compatibility
- Data visualization with graphs and charts
- Advanced security measures including HTTPS and secure authentication
- Regular automated backups
- Search history and bookmarking functionality

## Recent Updates

### 1. Health Fields Implementation
- Added "Medical Status" fields (Healthy/Medical care) to all relevant forms:
  - Staff registration forms (private and government)
  - Student registration forms (O'Level and A'Level)
  - Staff PDF generation templates
- Added "Physical Health" fields (Fit/Disabled) to the same forms
- Standardized health field implementation across the system

### 2. Dashboard UI Improvements
- Reduced the height of the welcome card for better space utilization
- Implemented more compact layout with grid-based information display
- Optimized text sizes and spacing for better readability
- Combined related information to reduce vertical space requirements
- Enhanced visual hierarchy with improved spacing and sizing

### 3. Icon System Overhaul
- Replaced generic SVG icons with professional Font Awesome icons
- Implemented consistent icon styling throughout the application
- Added contextual icons for better feature recognition
- Improved accessibility with semantic icon implementation
- Reduced code complexity with standardized icon classes

### 4. Academics Module - Subject Management
- **O'Level Subjects**:
  - Add/Edit/Delete functionality for general subjects
  - Add/Edit/Delete functionality for optional subjects
  - Subject name and code validation
  - Practical requirement flag for practical-based subjects
  - Multiple class assignment support (JSON array storage)

- **A'Level Subjects**:
  - Add/Edit/Delete functionality for arts subjects
  - Add/Edit/Delete functionality for science subjects
  - Add/Edit/Delete functionality for subsidiary subjects with stream specification
  - Dynamic stream selection (Arts/Science) for subsidiary subjects
  - Multiple class assignment support

- **Implementation Details**:
  - Modal-based forms for seamless add/edit operations
  - Real-time form state management with localStorage
  - Confirmation dialogs for destructive operations
  - Comprehensive validation for all subject inputs
  - 18 new routes for complete CRUD operations across all subject types

### 5. Academics Module - Teacher Subject Assignment
- **Assignment Features**:
  - Assign subjects to teachers with level specification (O'Level/A'Level)
  - Specialty assignment for A'Level teachers (Arts/Science)
  - Multi-class assignment using comma-separated input (e.g., "S1, S2, S3")
  - Edit and delete functionality for existing assignments

- **User Interface**:
  - Comprehensive assignment modal with 2x2 grid layout
  - Dynamic subject dropdown based on level selection
  - Classes field accepting comma-separated values
  - AJAX-based edit functionality for seamless data loading
  - Inline delete forms with confirmation dialogs

- **Technical Implementation**:
  - 3 new routes for teacher assignment CRUD operations
  - Dynamic subject filtering by academic level
  - Sophisticated JavaScript handling for form state management
  - Proper method spoofing for HTTP verbs (PUT, DELETE)
  - JSON-encoded subject data for client-side processing

### 6. Database & Relationships
- **New Relationships**:
  - TeacherSubject pivot table linking Staff, Academics, and Subjects
  - Flexible many-to-many relationship between teachers and subjects
  - Level-aware subject assignment with specialty support
  - Class grouping through JSON array storage

- **Data Integrity**:
  - Foreign key constraints for referential integrity
  - Cascading validation between related entities
  - Proper data parsing for comma-separated class inputs

## Technical Information

### Language & Runtime
- **Language**: PHP
- **Version**: 8.2.26
- **Framework**: Laravel 12.0
- **Build System**: Composer
- **Package Manager**: Composer (PHP), npm (JavaScript)

### Main Dependencies
- **laravel/framework** (^12.0): Core Laravel framework
- **laravel/tinker** (^2.10.1): REPL for Laravel
- **barryvdh/laravel-dompdf**: PDF generation library
- **maatwebsite/excel** (^3.1): Excel file handling
- **font-awesome**: Icon system for UI components
- **tailwindcss** (^4.0.0): CSS framework
- **vite** (^6.2.4): Frontend build tool
- **axios** (^1.8.2): HTTP client
- **alpine.js**: JavaScript framework for interactive components

### Development Dependencies
- **laravel/pail** (^1.2.2): Laravel debugging tool
- **laravel/pint** (^1.13): PHP code style fixer
- **laravel/sail** (^1.41): Docker development environment
- **phpunit/phpunit** (^11.5.3): Testing framework
- **fakerphp/faker** (^1.23): Fake data generation for testing

### Database
- MySQL 5.7+ for data storage
- Eloquent ORM for database management

## Installation & Setup

### Prerequisites
- PHP 8.2.26 or higher
- MySQL 5.7 or higher
- Node.js and npm
- Composer

### Installation Steps

1. **Clone the repository**
   `ash
   git clone <repository-url>
   cd Gombe\ SS\ Hub\ Pro
   `

2. **Install PHP dependencies**
   `ash
   composer install
   `

3. **Install JavaScript dependencies**
   `ash
   npm install
   `

4. **Create environment configuration**
   `ash
   cp .env.example .env
   `

5. **Generate application key**
   `ash
   php artisan key:generate
   `

6. **Run database migrations**
   `ash
   php artisan migrate
   `

7. **Build frontend assets**
   `ash
   npm run build
   `

8. **Start the development server**
   `ash
   php artisan serve
   `

   For development with hot reloading, in a separate terminal run:
   `ash
   npm run dev
   `

## Docker Support

The application includes Laravel Sail for Docker development environment.

### Using Docker
`ash
# Install dependencies and start containers
./vendor/bin/sail up

# Run artisan commands
./vendor/bin/sail artisan <command>

# Access the application at http://localhost
`

**Docker Configuration**:
- Runtime: PHP 8.2 (configurable)
- Includes MySQL database container
- Redis cache support
- Mailhog for email testing

## Testing

### Testing Framework
- **Framework**: PHPUnit (^11.5.3)
- **Test Location**: 	ests/ directory
- **Configuration**: phpunit.xml
- **Test Suites**: Unit and Feature tests

### Running Tests
`ash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Unit/ExampleTest.php

# Using PHPUnit directly
./vendor/bin/phpunit

# Run tests with coverage
./vendor/bin/phpunit --coverage-html
`

## Project Structure

### Directory Organization
- **app/**: Core application code (controllers, models, services)
- **bootstrap/**: Laravel framework bootstrap files
- **config/**: Application configuration files
- **database/**: Migrations, seeders, and factories
- **public/**: Publicly accessible files and entry point
- **resources/**: Frontend assets, views, and language files
  - **css/**: Stylesheets
  - **js/**: JavaScript files
  - **views/**: Blade template files
- **routes/**: Application route definitions
- **storage/**: Application storage (logs, cache, uploads)
- **tests/**: Application test files
- **vendor/**: Third-party dependencies

## Configuration Files
- **composer.json**: PHP dependency configuration
- **package.json**: JavaScript dependency configuration
- **phpunit.xml**: PHPUnit testing configuration
- **tailwind.config.js**: Tailwind CSS configuration
- **vite.config.js**: Vite build tool configuration
- **.env.example**: Environment configuration template

## Deployment

### Requirements
- PHP 8.2.26+
- MySQL 5.7+
- Web server (Apache/Nginx)
- HTTPS enabled

### Deployment Options
- Traditional web hosting environments
- Cloud platforms (AWS, DigitalOcean, Heroku, etc.)
- Docker containers for containerized deployment
- Laravel Forge for automated deployment

## Security Features
- HTTPS encryption for data transmission
- Secure authentication mechanisms with Laravel Fortify
- Role-based access control (RBAC)
- Input validation and sanitization
- CSRF protection
- Regular security updates
- Data backup and recovery systems
- Password hashing using bcrypt

## Support & Documentation

For more information about Laravel, visit: https://laravel.com/docs

For more information about Tailwind CSS, visit: https://tailwindcss.com

For more information about Vite, visit: https://vitejs.dev

## License

This project is proprietary software for Gombe Secondary School.

## Contact

For support or inquiries, please contact the development team.
#   G o m b e - S S - H u b - P r o  
 #   G o m b e - S S - H u b  
 