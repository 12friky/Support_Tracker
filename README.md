# Support Tracker

A Laravel-based daily activity tracking system for applications support teams. Personnel can log activities, update statuses, leave remarks, view daily handovers, and query historical reports — all behind staff authentication.

---

## Login Credentials

Three staff accounts are pre-seeded. Use these to log in at `/login`.

| # | Staff ID | Password | Name | Role | Department |
|---|----------|----------|------|------|------------|
| 1 | 100001 | Admin@1234 | Francis Ngumah | Admin | Infrastructure & Platforms |
| 2 | 100002 | Support@5678 | Abena Mensah | Support Engineer | Applications Support |
| 3 | 100003 | Engineer@9012 | Kwame Asante | Support Engineer | Operations |

Note: All passwords are hashed in the database. Plain-text passwords are listed here for demo/testing purposes only.

---

## Features

- User authentication — staff login via Staff ID and Password. All routes are protected. Logout is CSRF-safe.
- Activity management — create, view, update and delete support activities.
- Status updates with remarks — each activity can be set to Done or Pending with a mandatory remark. Every update is logged with the personnel identity and timestamp.
- Personnel bio capture — every activity log records the staff member (Staff ID, Name, Department, Shift, Phone) who made the update and the exact time it was done.
- Daily handover view — shows all activity updates for any selected day including who updated it, their bio details, the remark and the time. Pending items have a Continue Work button. Supports day navigation and CSV export.
- Reports view — query activity histories by a custom start and end date with optional keyword search. Fully server-side filtered. Results can be exported to CSV.
- Dashboard — live stats for total activities, completed, pending and updates today. Includes a donut chart and a recent updates table.
- User management — view all team members with their roles and departments.
- Profile and settings — each user has a profile page and configurable notification and timezone preferences.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 11 (PHP) |
| Database | MySQL |
| Frontend | Blade Templates + Vanilla CSS |
| Auth | Custom session-based (Staff ID + Password) |
| Charts | Inline SVG |

---

## Installation

### 1. Clone the repository
```bash
git clone https://github.com/12friky/Support_Tracker.git
cd Support_Tracker
```

### 2. Install dependencies
```bash
composer install
npm install && npm run dev
```

### 3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Set up the database

Edit `.env` with your MySQL credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=support_tracker
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run migrations and seed
```bash
php artisan migrate
php artisan db:seed
```

This creates all tables and seeds the three demo staff accounts listed in the Login Credentials table above.

### 6. Start the development server
```bash
php artisan serve
```

Visit: http://localhost:8000

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ActivityController.php     - Create, update, delete activities and logs
│   │   ├── DashboardController.php    - Dashboard stats
│   │   ├── HandoverController.php     - Daily handover view and CSV export
│   │   ├── ProfileController.php      - User profile
│   │   ├── ReportController.php       - Server-side date-range reports
│   │   ├── SettingsController.php     - User settings
│   │   └── UsersController.php        - Team members list
│   └── Middleware/
│       └── StaffAuth.php              - Session-based auth guard
├── Models/
│   ├── User.php                       - staff_id, name, role, department, phone, shift
│   ├── Activity.php                   - Activities with relationships
│   ├── ActivityLog.php                - Update history (status, remark, who, when)
│   └── UserSetting.php                - Per-user preferences

database/
├── migrations/                        - All 7 migration files
└── seeders/
    ├── DatabaseSeeder.php
    └── SupportTrackerSeeder.php       - Seeds users, activities, logs and settings

resources/views/
├── auth/login.blade.php
├── dashboard/index.blade.php
├── activities/
│   ├── index.blade.php                - List with Add, Edit and Delete modals
│   ├── create.blade.php               - Add activity form
│   └── update.blade.php               - Update status and remark form
├── handover/index.blade.php           - Daily handover with bio details
├── reports/index.blade.php            - Date-range query and export
├── users/index.blade.php
├── profile/index.blade.php
├── settings/index.blade.php
└── layouts/app.blade.php              - Shared sidebar layout

routes/web.php                         - All routes (public and protected)
```

---

## Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | /login | login | Login page |
| POST | /login | login.submit | Authenticate |
| POST | /logout | logout | Logout and clear session |
| GET | /dashboard | dashboard | Dashboard |
| GET | /activities | activities.index | List all activities |
| GET | /activities/create | activities.create | Create form |
| POST | /activities | activities.store | Save new activity |
| GET | /activities/{id}/update | activities.update | Update form |
| POST | /activities/{id}/update | activities.save | Save update and log |
| DELETE | /activities/{id} | activities.destroy | Delete activity |
| GET | /handover | handover | Daily handover (today) |
| GET | /handover/show | handover.show | Daily handover (by date) |
| GET | /handover/export | handover.export | Export handover to CSV |
| GET | /reports | reports | Reports with date filter |
| GET | /users | users.index | Team members |
| GET | /profile | profile | User profile |
| GET | /settings | settings | User settings |

All routes except /login and POST /login require authentication via the staff.auth middleware.

---

## Author

Developed by Francis Ngumah
GitHub: https://github.com/12friky

---

## License

This project is open-source and free to use.
