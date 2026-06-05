# primary_school_finder

A lightweight web application that helps parents locate and compare primary schools in their area. Built with PHP and a simple MySQL database, it provides an admin interface for managing cities, courses, faculties, schools, and admission vouchers.

---

## Overview

`primary_school_finder` offers:

* A public‑facing portal where users can search for primary schools by city, course, or faculty.
* An admin dashboard for CRUD (Create, Read, Update, Delete) operations on schools, faculties, courses, and cities.
* Admission form handling and voucher generation.
* A ready‑to‑use SQL dump (`Database/primary_db.sql`) and documentation (`Online Primary School Finding.docx`).

---

## Features

| Feature | Description |
|---------|-------------|
| **Admin Authentication** | Secure login/logout (`adminlogin.php`, `adminlogout.php`). |
| **City Management** | Add, edit, and delete cities (`add_city.php`). |
| **Course Management** | Manage courses (`add_courses.php`, `edit_course.php`, `update_courses.php`). |
| **Faculty Management** | Upload faculty profiles with images (`add_faculty.php`, `edit_faculty.php`, `update_faculty.php`). |
| **School Management** | Add schools with images, edit details, and delete entries (`add_school.php`, `edit_school.php`, `update_school.php`). |
| **Admission Form** | Collect applicant data (`admission_form.php`). |
| **Voucher Generation** | Create printable fee vouchers (`voucher.php`, `fee_voucher.php`). |
| **Responsive UI** | Simple, mobile‑friendly layout powered by HTML/CSS (Bootstrap optional). |
| **Database Seed** | Pre‑populated schema and sample data (`Database/primary_db.sql`). |

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL / MariaDB |
| **Frontend** | HTML5, CSS3, minimal JavaScript (Bootstrap 4/5 optional) |
| **Server** | Apache / Nginx (any LAMP stack) |
| **Version Control** | Git |

---

## Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/yourusername/primary_school_finder.git
   cd primary_school_finder
   ```

2. **Create a MySQL database**

   ```sql
   CREATE DATABASE primary_school_finder;
   ```

3. **Import the schema and sample data**

   ```bash
   mysql -u YOUR_DB_USER -p primary_school_finder < Database/primary_db.sql
   ```

4. **Configure the application**

   *Copy `config.php.example` (if provided) to `config.php` and edit the credentials.*

   ```php
   // config.php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'primary_school_finder');
   define('DB_USER', 'YOUR_DB_USER');
   define('DB_PASS', 'YOUR_DB_PASSWORD');
   ```

5. **Set up the web server**

   - Place the