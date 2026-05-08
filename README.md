# 🌟 SAHEM – Event, Organization, and Volunteer Management Platform

<div align="center">

![Sahem Platform](https://img.shields.io/badge/Status-Active-brightgreen)
![Laravel](https://img.shields.io/badge/Laravel-12.0-red)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![License](https://img.shields.io/badge/License-MIT-green)

**A comprehensive platform for managing volunteer work, organizing events, and connecting organizations with volunteers efficiently.**

[📄 Full System Report](Graduation Project Report 2.pdf) | [Features](#-core-features) | [Installation](#-installation--setup) | [Architecture](#-architecture-overview) | [Users](#-main-user-roles)

</div>

---

## 📖 Project Overview

**Sahem** is an integrated digital system designed to organize volunteer work and connect organizations with events and volunteers within a single, modern, and user-friendly platform. The project provides a public interface for visitors and volunteers, along with an administrative dashboard for supervisors and managers, featuring a scalable design that supports future service expansion.

### 🎯 Problem Statement

Organizations struggle to effectively showcase their events and manage volunteers, while volunteers search for a unified platform that presents opportunities clearly and easily. **Sahem** bridges this gap by providing a comprehensive solution that brings together organizations, events, volunteers, administration, and public interface—all within one structured and flexible system.

---

## ⚡ Quick Start

### Installation & Setup

Follow these steps to get the project running locally:

#### **Prerequisites**
- PHP 8.2+
- MySQL 5.7+
- Node.js 16+ (for frontend assets)
- Composer
- Git

#### **Installation Steps**

```bash
# 1. Clone the repository
git clone <repository-url>
cd Non-Profit-Associations-Management-System

# 2. Install PHP dependencies
composer install

# 3. Copy environment file and configure
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure database in .env
# Edit .env and set your database credentials:
# DB_DATABASE=sproject_db
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Run migrations
php artisan migrate

# 7. Run seeders (optional - for demo data)
php artisan db:seed

# 8. Install Node dependencies and build frontend
npm install
npm run dev

# 9. Generate storage link for file uploads
php artisan storage:link
```

### Running the Application

#### **Development Mode**

```bash
# Option 1: Run all services concurrently (Recommended)
composer run dev

# This command runs:
# - Laravel development server (localhost:8000)
# - Queue listener for jobs
# - Pail (log viewer)
# - Vite development server for frontend assets

# Option 2: Run services individually
php artisan serve              # Start Laravel server
php artisan queue:listen       # Start queue worker
npm run dev                    # Start Vite dev server (in another terminal)
```

#### **Production Build**

```bash
npm run build                  # Build frontend assets
php artisan config:cache      # Cache configuration
php artisan route:cache       # Cache routes
```

---

## 👥 Main User Roles

### 1. **Supervisor** (المشرف)
- Manages the entire system
- Approves/rejects volunteer registrations
- Creates and manages managers
- Views all activities and organizations
- **Dashboard Route**: `/supervisor/dashboard`

### 2. **Manager** (المدير)
Two types of managers exist:
- **Activities Manager**: Manages organizations, activities, and volunteer assignments
  - **Dashboard Route**: `/manager/dashboard`
- **Financial Manager**: Manages donations, expenses, and financial reports
  - **Dashboard Route**: `/financial/dashboard`

### 3. **Volunteer** (المتطوع)
- Registers for an account
- Browses available activities and events
- Applies for volunteer opportunities
- Views participation history
- **Dashboard Route**: `/volunteer/dashboard`

### 4. **Public User** (الزائر)
- Browses organizations and events
- Views activity details
- Can register as a volunteer
- **Routes**: `/sahem/home`, `/sahem/organizations`, `/sahem/activities`

---

## 🧩 Core Features

### 👤 For Users (Visitors & Volunteers)

✅ Create volunteer account (requires supervisor approval)  
✅ Browse published, active events  
✅ View detailed event information (description, date, location, type)  
✅ View organization profiles and their associated events  
✅ Apply for volunteer opportunities  
✅ Track volunteer participation history  
✅ View completed activities and results  

### 🛠️ For Managers

#### **Activities Manager**
✅ Manage organizations (create, read, update, delete)  
✅ Manage organization events and activities  
✅ Create and publish volunteer activities  
✅ Set volunteer requirements per activity  
✅ Review and manage volunteer applications  
✅ Approve/reject/remove volunteers from activities  
✅ Record activity results and reports  
✅ Generate PDF reports for volunteers  

#### **Financial Manager**
✅ Manage donations by activity  
✅ Record and track expenses  
✅ Generate financial reports  
✅ View donation corrections and adjustments  
✅ Export financial data  

### 👨‍💼 For Supervisors

✅ Manage all managers and assign roles  
✅ Approve/reject volunteer registrations  
✅ View all activities and organizations  
✅ Monitor system-wide volunteer participation  
✅ Manage volunteer status and accounts  

---

## 🏗️ Architecture Overview

### **System Architecture**

```
┌─────────────────────────────────────────┐
│         PUBLIC FRONTEND                  │
│  (Landing, Organizations, Activities)    │
└────────────┬────────────────────────────┘
             │
┌────────────┴─────────────────────────────┐
│     VOLUNTEER INTERFACE                   │
│  (Dashboard, Apply, History)              │
└────────────┬─────────────────────────────┘
             │
┌────────────┴──────────────────────────────────────┐
│         ADMIN DASHBOARDS (Protected Routes)       │
├──────────────────────────────────────────────────┤
│  Supervisor  │  Manager  │  Financial Manager    │
│  Dashboard   │ Dashboard │    Dashboard           │
└────────────┬──────────────────────────────────────┘
             │
┌────────────┴──────────────────────────┐
│     DATABASE LAYER (MySQL)             │
│  ┌────────────────────────────────────┐│
│  │  Core Tables                       ││
│  │  - Organizations                   ││
│  │  - Organization Events             ││
│  │  - Organization Activities         ││
│  │  - Volunteers                      ││
│  │  - Managers & Supervisors          ││
│  │  - Activity Assignments            ││
│  │  - Donations & Expenses            ││
│  └────────────────────────────────────┘│
└────────────────────────────────────────┘
```

### **Frontend Structure**

1. **Public Pages**
   - Home page (`/sahem/home`)
   - Organizations listing (`/sahem/organizations`)
   - Activities listing (`/sahem/activities`)
   - Activity details (`/sahem/activities/sahem/{id}`)
   - Organization events (`/sahem/organization`)

2. **Volunteer Pages**
   - Dashboard (`/volunteer/dashboard`)
   - Available activities (`/volunteer/activities`)
   - My participations (`/volunteer/past-activities`)
   - Profile management

3. **Admin Dashboards**
   - Manager dashboard (`/manager/dashboard`)
   - Supervisor dashboard (`/supervisor/dashboard`)
   - Financial dashboard (`/financial/dashboard`)

### **Backend Structure**

**Controllers**:
- `PublicController.php` - Public pages
- `VolunteerController.php` - Volunteer operations
- `Manager/` - Manager-related operations
- `Supervisor/` - Supervisor-related operations
- `Financial/` - Financial operations

**Models**:
- `Organization`, `OrganizationEvent`, `OrganizationActivity`
- `Volunteer`, `ActivityVolunteerAssignment`
- `Manager`, `Supervisor`
- `Donation`, `DonationCorrection`, `Donor`
- `Expense`, `ActivityResult`, `Payment`

**Routes**:
- `web.php` - Public routes
- `manager/manager_web.php` - Manager routes
- `supervisor/supervisor_web.php` - Supervisor routes
- `financial/financial_web.php` - Financial routes
- `volunteer/volunteer_web.php` - Volunteer routes

---

## 🗄️ Database Schema

### **Core Tables**

| Table Name | Purpose | Key Fields |
|-----------|---------|-----------|
| **organizations** | Organization information | id, name, type, status, created_by |
| **organization_events** | Events created by organizations | id, organization_id, title, dates, status |
| **organization_activities** | Main volunteer/donation activities | id, title, activity_type, dates, status |
| **volunteers** | Volunteer profiles | id, name, email, status, skills |
| **activity_volunteer_assignments** | Volunteer applications to activities | id, activity_id, volunteer_id, status |
| **activity_volunteer_requirements** | Requirements for each activity | id, activity_id, age_min, gender, skills |
| **activity_donation_settings** | Donation configurations | id, activity_id, target_amount, duration |
| **donations** | Donation records | id, activity_id, amount, donor_id |
| **expenses** | Expense records | id, activity_id, amount, receipt |
| **activity_results** | Activity completion reports | id, activity_id, total_volunteers, images |
| **managers** | Manager accounts | id, username, manager_type, status |
| **supervisor** | Supervisor accounts | id, username, email, status |

### **Migrations Overview**

All database migrations are properly ordered and maintained:

```
0001_01_01_000000 - Create users table (Laravel default)
0001_01_01_000001 - Create cache table
0001_01_01_000002 - Create jobs table
2025_10_30_000001 - Create supervisor table
2025_10_30_000002 - Create managers table
2025_11_06_000004 - Create organizations table
2025_11_06_000005 - Create organization_events table
2025_12_05_175856 - Create organization_activities table
2025_12_05_175907 - Create activity_donation_settings table
2025_12_05_175907 - Create activity_volunteer_requirements table
2025_12_21_210825 - Create volunteers table
2025_12_23_032803 - Add password to volunteers table
2025_12_05_210122 - Alter gender requirement in activity_volunteer_requirements
2026_03_08_041644 - Create activity_results table
2026_03_09_044304 - Create activity_volunteer_assignments table
2026_03_09_050455 - Add volunteer fields to organization_activities
2026_03_09_053806 - Add rejection reason to activity_volunteer_assignments
2026_03_11_080000 - Create donors table
2026_03_11_080001 - Create donations table
2026_03_11_080002 - Create donation_corrections table
2026_03_11_080003 - Create expenses table
2026_04_05_201242 - Add receipt_number to expenses table
2026_04_22_214255 - Add deleted_at to activity_volunteer_assignments
2026_04_22_221748 - Add checkin fields to activity_volunteer_assignments
2026_04_23_000000 - Add removed status to activity_volunteer_assignments
2026_04_25_000001 - Create payments table
2026_04_25_012951 - Make created_by nullable in donations table
```

---

## 🔄 Workflow & User Journey

### **Volunteer Registration & Approval Flow**

```
Visitor (Public)
     ↓
[Registration Form] → Pending Status
     ↓
Supervisor Review
     ↓
    ├─ Approve → Active Volunteer
    └─ Reject → Rejected Status
     ↓
Access Dashboard & Apply to Activities
```

### **Activity Management Flow**

```
Manager Creates Activity
     ↓
Set Volunteer Requirements & Donation Settings
     ↓
Publish Activity
     ↓
Volunteers Apply
     ↓
Manager Reviews Applications
     ↓
Approve/Reject Volunteers
     ↓
Activity Execution (Check-in, Attendance)
     ↓
Record Results & Completion Report
```

---

## 📊 Key Features in Detail

### **Volunteer Management**
- Application screening with detailed evaluation
- Skill-based matching
- Check-in system with QR codes
- Attendance tracking
- Removal with reason documentation

### **Financial Management**
- Donation tracking and reporting
- Expense recording with receipts
- Donation corrections and adjustments
- Financial reports by activity
- Payment processing integration

### **Activity Management**
- Creation with detailed requirements
- Publishing controls
- Volunteer assignment management
- Results and impact reporting
- PDF report generation

### **Reporting**
- Activity reports with metrics
- Financial summaries
- Volunteer participation statistics
- PDF exports for documentation

---

## 🚀 Technology Stack

| Layer | Technology |
|-------|-----------|
| **Framework** | Laravel 12.0 |
| **Language** | PHP 8.2+ |
| **Database** | MySQL 5.7+ |
| **Frontend** | Tailwind CSS, Vue.js (via Vite) |
| **Build Tool** | Vite |
| **Session** | Database-based |
| **Queue** | Database-based |
| **Cache** | Database-based |

---

## 📋 Project Requirements & Completed Features

### ✅ **Completed Requirements**

✅ Multi-role authentication system  
✅ Organization management with hierarchy  
✅ Event and activity creation and management  
✅ Volunteer registration with approval workflow  
✅ Volunteer requirement matching  
✅ Donation and financial tracking  
✅ Expense management with receipts  
✅ Activity result documentation  
✅ PDF report generation  
✅ Public-facing interface  
✅ Admin dashboards for all roles  
✅ Database-based sessions and queues  

### 🔄 **Future Roadmap**

📌 Advanced financial reports and analytics  
📌 Mobile app for volunteer management  
📌 SMS/Email notifications  
📌 Integration with payment gateways  
📌 Advanced volunteer matching algorithms  
📌 Impact visualization dashboards  
📌 Multi-language support  
📌 Export to Excel/CSV functionality  

---

## 🔐 Security Features

- Hash-based password encryption (bcrypt)
- CSRF protection on all forms
- Middleware authentication for protected routes
- Role-based access control
- Volunteer status validation
- Session-based authentication

---

## 📝 Environment Configuration

Key configuration files and environment variables:

```env
APP_NAME=SAHEM
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sproject_db
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

---

## 📚 Useful Resources

- 📖 [Laravel Documentation](https://laravel.com/docs)
- 🎨 [Tailwind CSS Docs](https://tailwindcss.com/docs)
- 💾 [MySQL Documentation](https://dev.mysql.com/doc/)
- 📄 [Full Project Report](./Graduation%20Project%20Report%202.pdf)

---

## 🤝 Contributors

This project is developed as part of a graduation project for managing non-profit organizations and volunteer coordination.

---

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

<div align="center">

**Built with ❤️ for Non-Profit Organizations**

**Last Updated**: May 2026

</div>
