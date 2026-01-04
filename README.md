# SISKA V2 - Enterprise Learning Management System

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![Midtrans](https://img.shields.io/badge/Payment-Midtrans-00529A?style=for-the-badge&logo=v&logoColor=white)

**SISKA (Sistem Informasi Sertifikasi & Keahlian)** is a robust, secure, and modern Learning Management System (LMS) designed to streamline professional education. It serves as a unified ecosystem connecting administrators, diverse vendors, and learners through a premium interface.

## 🚀 Overview

SISKA V2 solves the complexity of course administration by automating enrollments, payments, and certification. It transforms scattered learning processes into a single, cohesive "LearnFlow"—from discovery to verified credential issuance.

### Core Solutions
*   **Administrative Automation**: Reduces manual overhead by 90% through automated invoicing and enrollment.
*   **Digital Trust**: Combats certificate fraud with system-generated, immutable digital credentials.
*   **Unified Learning Experience**: Consolidates video modules, materials, and progress tracking in a distraction-free environment.

## ✨ Key Features

### 👤 For Learners
*   **Course Marketplace**: Browse and filter courses by category, price, and vendor.
*   **Learning Dashboard**: Track progress, access materials (Video/PDF), and view history.
*   **Digital Wallet**: Manage transaction history and view payment status.
*   **Instant Certification**: Download verifiable certificates immediately upon course completion.

### 🛡️ For Administrators
*   **Content Management**: Create and manage classes, modules, and materials with rich media support.
*   **Financial Center**: Real-time revenue tracking and transaction verification.
*   **User Management**: Role-based access control (RBAC) and user oversight.
*   **Vendor Management**: Manage multiple course providers within the platform.

### 🔒 Security Enhancements
*   **Secure Headers**: Implemented HSTS, X-Frame-Options, and CSP for robust protection.
*   **Path Traversal Protection**: Secure file serving mechanisms for private assets.
*   **Role-Based Access Control**: Strict middleware policies (Role:Admin, Auth).
*   **Input Sanitization**: Global protection against XSS and SQL Injection via Eloquent.

## 💻 Tech Stack

*   **Framework**: Laravel 10
*   **Database**: MySQL / MariaDB
*   **Frontend**: Bootstrap 5, Custom CSS Variables, Glassmorphism UI
*   **Payment Gateway**: Midtrans Snap API
*   **Assets**: Bootstrap Icons, Google Fonts (Inter/Outfit)

## ⚙️ Installation

Follow these steps to set up the project locally:

1.  **Clone the repository**
    ```bash
    git clone https://github.com/yourusername/siskav2.git
    cd siskav2
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Environment Setup**
    Copy the example env file and configure your database and Midtrans credentials.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Database Setup**
    ```bash
    php artisan migrate --seed
    ```

5.  **Storage Link**
    ```bash
    php artisan storage:link
    ```

6.  **Run the Application**
    ```bash
    php artisan serve
    ```

## 📝 Accounts

Default accounts seeded for testing:

*   **Administrator**: `admin@gmail.com` / `password`
*   **User**: `user@gmail.com` / `password`

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
