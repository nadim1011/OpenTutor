# OpenTutor

OpenTutor is a PHP and MySQL tutoring marketplace. Students and guardians can find tutors or post tuition opportunities; tutors can maintain a profile and apply to tuition posts. The interface is primarily in Bengali.
https://opentutor.gt.tc/

## Features

- Tutor search with subject, location, and gender filters
- Public tutor profiles and tutor verification status
- Student accounts, tuition posts, and application management
- Tutor accounts, profiles, tuition search, and applications
- Student tutor favorites and tutor tuition shortlists
- Reviews, account settings, and administration pages
- CSRF-protected forms and password hashing

## Requirements

- PHP 7.0 or newer with PDO and the PDO MySQL driver enabled
- MySQL or MariaDB with InnoDB support
- A web server such as Apache, or PHP's built-in development server

## Setup

1. Create a MySQL or MariaDB database for the project.
2. Configure the database connection. The application reads `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` environment variables; otherwise it uses the fallback values in `config/database.php`. Set your own values and do not publish database credentials.
3. Import `config/schema.sql` into the database. The PHP application also creates its tables when it connects, but importing the schema explicitly is useful when setting up or hosting the project.
4. Serve the `opentutor` directory as the web root.

For local development, open a terminal in the `opentutor` directory and run:

```sh
php -S localhost:8000
```

Then visit <http://localhost:8000>.

On Windows PowerShell, database environment variables can be set for the current terminal before starting PHP:

```powershell
$env:DB_HOST = "127.0.0.1"
$env:DB_NAME = "opentutor"
$env:DB_USER = "your_database_user"
$env:DB_PASS = "your_database_password"
php -S localhost:8000
```

Update the example database values to match your local database. The same settings can be configured in your web server's environment for deployment.

## Accounts and roles

- **Student:** browse tutors, add tutors to favorites, post tuition opportunities, review applications, and manage a shortlist.
- **Tutor:** complete a tutor profile, search tuition posts, apply, and manage applications and reviews.
- **Admin:** manage users, tutor verification, tuition posts, applications, reports, and settings.

The application bootstraps an administrator account when the database connection is initialized and that account is not already present. Review and change the bootstrap credentials in `config/database.php` before deploying to a public server.

## Project layout

```text
opentutor/
├── actions/       Request handlers
├── admin/         Administrator pages
├── api/           API endpoints
├── assets/        CSS, JavaScript, and images
├── config/        Application/database configuration and SQL schema
├── includes/      Shared authentication, layout, and helper code
├── student/       Student dashboard and tools
├── tutor/         Tutor dashboard and tools
├── uploads/       Uploaded files
├── index.php      Public homepage
├── teachers.php   Tutor search
└── tuitions.php   Tuition listings
```

## Deployment notes

- Use environment variables for database credentials and keep secrets out of source control.
- Disable PHP error display in production and configure server-side error logging.
- Ensure `uploads/` is writable by the web server only as needed, and prevent uploaded files from being executed as scripts.
- Use HTTPS for public deployments.
