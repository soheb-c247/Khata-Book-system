# 📘 KhataBook – Customer & Transaction Management System

A **Laravel-based KhataBook** that allows shopkeepers to manage customers, record transactions (credit/debit), track balances, and generate customer statements in **PDF, CSV, and Excel** formats.  
Built with Laravel, Blade, TailwindCSS, jQuery, Toastr, and DomPDF.

---

## 🚀 Features

### 👥 User & Customer Management
* Shopkeepers can **register/login** using phone and password (powered by **Laravel Breeze** for authentication).
* Manage customers with details: Name, Phone, Address, Opening Balance.
* Secure authentication with hashed passwords and session management.

### 💰 Transactions
* Record **Credit/Debit** transactions with notes.
* Auto-calculation of **total credits, debits, and balance**.
* Transaction history viewable per customer.

### 📊 Customer Statement
* Generate **PDF / CSV / Excel** statements using `barryvdh/laravel-dompdf`.
* Apply **date filters**: Current Month, Last 3 Months, or Custom Range.
* Handles **no transactions case** gracefully.

### 🔔 Flash Notifications
* Toastr integration for **success, error, warning, info** messages.

### 🛠 Seeder & Factories
* One fixed user with customers and transactions.
* Additional random users with customers & transactions for demo purposes.

### 🔐 Authentication & Authorization
* User authentication handled by **Laravel Breeze**.
* Registration, login, password reset flows included.
* Middleware ensures only authenticated shopkeepers access customer/transaction pages.

---

## ⚙️ Setup Instructions

### 1. Clone Repository


```bash
git clone https://github.com/soheb-c247/Khata-Book-system
cd Khata-Book-system

```
### 2. Install Dependencies

```bash
composer install
npm install && npm run dev
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` file with correct **DB credentials**.

### 4. Migrate & Seed Database

```bash
php artisan migrate:fresh --seed
```

This will insert:

* **1 fixed user (Shoaib) with 1 fixed customer & transactions**
* **8 random customers for Shoaib**
* **5 additional shopkeepers, each with 10 customers and 15 transactions**

### 5. Start Server

```bash
php artisan serve
```

Visit: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 👤 Demo Credentials

You can log in with the seeded user:

* **phone**: `8827738545`
* **Password**: `password`

---

## 🔄 Working Flow

1. **Login as Shopkeeper** (Breeze handles login & registration)
2. **Add Customers** (Name, Phone, Address, Opening Balance{optional}).
3. **Record Transactions** → Credit / Debit.
4. **View Customer Details** → Balance auto-calculated.
5. **Generate Statement**:
   * Select Date Range (Current Month / Last 3 Months / Custom).
   * Choose Output Format (PDF).
   * Download or Email (Email feature planned).
6. **Flash Notifications** show operation results.

---

## 📌 Areas of Improvement

* 📧 **Email Statement** → Currently not implemented.
* 📱 **Responsive UI Enhancements** → Improve mobile view for transactions.
* 📊 **Charts & Analytics** → Show monthly credits/debits in graphs.
* 🔐 **Role Management** → Add multiple shopkeepers with permissions.
* 🧾 **GST/Invoice Integration** → Generate invoices with GST support.
* 🌐 **Multi-language Support** → English + Hindi for wider usability.
* 📩 **Multiple Options for statement** → Excel, CSV etc .

---

## 🏗 Tech Stack

* **Backend**: Laravel 12
* **Authentication**: Laravel Breeze
* **Frontend**: Blade, TailwindCSS, jQuery, Toastr
* **Database**: MySQL
* **PDF Export**: barryvdh/laravel-dompdf

---

## 💡 Laravel Breeze Authentication

This project uses **Laravel Breeze** to provide simple and lightweight authentication scaffolding.  

**Key Features:**
* **User Registration** – Quick signup for new shopkeepers.
* **Login / Logout** – Secure session handling for authenticated users.
* **Password Reset** – Easily reset forgotten passwords via email.
* **Email Verification (optional)** – Ensure valid email addresses.
* **TailwindCSS-based UI** – Fully customizable and responsive design.
* **Route Protection** – Secure pages so that only authenticated shopkeepers can access customer and transaction data.

---

📩 *Maintained by Mohammad Soheb*

