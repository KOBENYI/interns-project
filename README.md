# NORAX ACADEMY

## Admin dashboard setup with XAMPP

The public academy pages live in the project root. The authenticated PHP/MySQL administration area is in [`admin`](admin/index.php).

### Requirements

- XAMPP with Apache, MySQL, and PHP enabled
- A modern browser

### Install locally

1. Copy the complete `interns-project` folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin` and create the database by importing [`admin/database/schema.sql`](admin/database/schema.sql). The SQL creates the `norax_academy` database automatically.
4. Confirm the connection values in [`admin/config/database.php`](admin/config/database.php). The defaults match a fresh XAMPP installation: user `root`, blank password, host `127.0.0.1`.
5. Open `http://localhost/interns-project/admin/setup.php` and create the first administrator. Use a password with at least 8 characters.
6. Sign in at `http://localhost/interns-project/admin/login.php`.
7. Delete or rename `admin/setup.php` after creating the first account.

### Included admin modules

The dashboard is backed by MySQL and includes live summary metrics, searchable student records, course and instructor management, enrollments, XAF payments, lessons and resource links, messages, printable reports, academy settings, CSRF-protected forms, prepared statements, session authentication, logout, and responsive layouts for mobile, tablet, and desktop.

### Contact details

- Email: noraxacademy6@gmail.com
- Phone: +237688911917 / +237680092008
# interns-project
Internship project

