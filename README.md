# Laravel Multi-Company URL Shortener

## Project Overview

This project is a multi-company URL shortener application developed using Laravel 12 and MySQL.

The application supports multiple companies, user invitations, role-based permissions, and short URL generation.

## Technologies Used

- PHP
- Laravel 12
- MySQL
- Blade
- Bootstrap
- JavaScript
- HTML and CSS
- Git and GitHub

## Features

### Company Management

- SuperAdmin can create and manage companies.
- Each company can have multiple users.

### User Management and Invitations

- SuperAdmin can invite Admin users to selected companies.
- Admin can invite Admin and Member users into their own company.
- Users can accept invitations using a unique invitation link.

### Role-Based Access Control

The application supports the following roles:

- SuperAdmin
- Admin
- Member
- Sales
- Manager

Permissions are managed using custom roles, permissions, and role-permission relationships.

### URL Shortener

- Admin and Member can generate short URLs.
- SuperAdmin can view short URLs across all companies.
- Admin can view all short URLs belonging to their company.
- Member can view only the URLs created by themselves.
- Generated short URLs redirect publicly to their original URLs without requiring authentication.

## Local Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/tarun-k-sahoo/laravel-multi-company-url-shortener.git

cd laravel-multi-company-url-shortener
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy the example environment file:

```bash
cp .env.example .env
```

For Windows, you can also copy `.env.example` manually and rename the copy to `.env`.

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Configure MySQL Database

Create a MySQL database, for example:

`laravel_assignment`

Update the following values in your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_assignment
DB_USERNAME=root
DB_PASSWORD=
```

Update the username and password according to your local MySQL configuration.

### 6. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

If the role and permission seeder is not registered in `DatabaseSeeder`, run it separately:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

Ensure the SuperAdmin account is created using the project's configured SuperAdmin seeder or setup procedure.

### 7. Start the Development Server

```bash
php artisan serve
```

Open the application:

http://127.0.0.1:8000

## AI Tool Usage Disclosure

- **ChatGPT:** Used as an assistance tool for frontend UI development, including Blade view structure, Bootstrap styling, and frontend presentation.
- Application requirements, backend implementation, business logic, and testing were reviewed and worked on as part of my own development process.

