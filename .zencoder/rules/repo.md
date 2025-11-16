---
description: Repository Information Overview
alwaysApply: true
---

# Gombe SS Hub Pro Information

## Summary
Gombe SS Hub Pro is a Laravel-based school management system designed for data collection and management of students and teachers. The application features user authentication, student/teacher management, reporting, dashboard analytics, and export capabilities. It includes role-based access control with admin and super admin roles, and provides various features like notifications, search functionality, and data visualization.

## Structure
- **app/**: Core application code including controllers, models, and business logic
- **bootstrap/**: Laravel framework bootstrap files
- **config/**: Application configuration files
- **database/**: Database migrations, seeders, and factories
- **public/**: Publicly accessible files and entry point
- **resources/**: Frontend assets, views, and language files
- **routes/**: Application route definitions
- **storage/**: Application storage for logs, cache, and user uploads
- **tests/**: Application test files
- **vendor/**: Third-party dependencies

## Language & Runtime
**Language**: PHP
**Version**: 8.2.26
**Framework**: Laravel 12.0
**Build System**: Composer
**Package Manager**: Composer (PHP), npm (JavaScript)

## Dependencies
**Main Dependencies**:
- laravel/framework (^12.0): Core Laravel framework
- laravel/tinker (^2.10.1): REPL for Laravel
- barryvdh/laravel-dompdf: PDF generation library
- maatwebsite/excel (^3.1): Excel file handling
- font-awesome: Icon system for UI components

**Development Dependencies**:
- laravel/pail (^1.2.2): Laravel debugging tool
- laravel/pint (^1.13): PHP code style fixer
- laravel/sail (^1.41): Docker development environment
- phpunit/phpunit (^11.5.3): Testing framework
- fakerphp/faker (^1.23): Fake data generation for testing

**Frontend Dependencies**:
- tailwindcss (^4.0.0): CSS framework
- vite (^6.2.4): Frontend build tool
- axios (^1.8.2): HTTP client
- alpine.js: JavaScript framework for interactive components

## Build & Installation
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Build frontend assets
npm run build

# Development server
php artisan serve

# Development with hot reloading
npm run dev
```

## Docker
**Configuration**: Laravel Sail provides Docker configuration
**Runtime**: PHP 8.2 (default)
**Run Command**:
```bash
# Start Docker environment
./vendor/bin/sail up
```

## Testing
**Framework**: PHPUnit
**Test Location**: tests/ directory
**Configuration**: phpunit.xml
**Test Suites**: Unit and Feature tests
**Run Command**:
```bash
php artisan test
# or
./vendor/bin/phpunit
```

## Key Features
- **Student Management**: Registration and tracking for O-Level and A-Level students with health fields
- **Staff Management**: Regular and government staff management with health tracking
- **Reporting**: Comprehensive reporting on student demographics and statistics
- **Export Options**: Excel, PDF, CSV export capabilities
- **Dashboard**: Analytics and visualization of school data with compact UI
- **User Management**: Role-based access control system
- **Notification System**: In-app notifications and alerts

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