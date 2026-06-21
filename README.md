Support Tracker

A simple Support Ticket Tracking System built with Laravel.
This project helps manage and track customer/support requests efficiently.

 Features

 User authentication (Login/Register)
 Create support tickets
 Update ticket status (Open, In Progress, Resolved)
 Assign tickets to users/admins
 Dashboard overview
 Simple and clean UI



Tech Stack

 Laravel (PHP Framework)
 MySQL / SQLite
 Bootstrap / Tailwind (if used)
 Blade Templates


 Installation

Clone the project:
git clone https://github.com/12friky/Support_Tracker.git
cd Support_Tracker


Install dependencies:

composer install
npm install && npm run dev


Set up environment:

cp .env.example .env
php artisan key:generate


Configure database in `.env`, then run:


php artisan migrate


Start server:
php artisan serve



Author
 Developed by: Francis Ngumah
 GitHub:https://github.com/12friky



License
This project is open-source.
