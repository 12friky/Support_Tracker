Support Tracker

Laravel-based Support Activity Tracking System for managing daily support tasks, status updates, personnel tracking, activity handovers, and reporting.

Features
User authentication (Login and Register)
Create support tickets
Update ticket status (Open, In Progress, Resolved)
Assign tickets to users or admins
Dashboard overview
Simple and clean UI
Tech Stack
Laravel (PHP Framework)
MySQL 
Bootstrap 
Blade Templates
Installation

Clone the repository
git clone https://github.com/12friky/Support_Tracker.git
cd Support_Tracker

Install dependencies
composer install
npm install && npm run dev

Set up environment
cp .env.example .env
php artisan key:generate

Configure your database inside the .env file, then run migrations
php artisan migrate

Start the development server
php artisan serve

Author

Developed by: Francis Ngumah
GitHub: https://github.com/12friky

License

This project is open-source and free to use.

