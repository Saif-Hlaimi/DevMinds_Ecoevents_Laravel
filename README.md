EcoEvents

EcoEvents is an online platform dedicated to organizing and promoting events focused on ecology and sustainable development. Developed as part of a project at Université Esprit, it supports citizen and associative initiatives by facilitating collective participation in actions aimed at raising awareness and mobilizing for environmental protection.
Built with Laravel for the backend and Blade with Vite for the frontend, this application provides comprehensive management of users, events, donations, groups, and eco-friendly products, fostering an engaged community for a greener future.
Developed by: Saif Hlaimi, Walid Khrouf, Feryel Yahyeoui, Mohamed Yessine Mighri, Elaa Sboui


Features
EcoEvents offers a set of tools to promote ecology:

User Management: Registration, authentication, personalized profiles, and role management (organizer, participant, admin).
Event Management: Creation, editing, promotion, and registration for eco-responsible events (workshops, demonstrations, webinars).
Donation Management: Secure system for funding sustainable initiatives, with contribution tracking.
Group Management: Creation of thematic communities for collaboration on ecological projects.
Eco-Friendly Product Management: Integrated marketplace to promote and sell green products (eco-materials, books, etc.).
Advanced Features: Real-time notifications, geolocation-based search, payment integration (Stripe/PayPal), and a dashboard to analyze environmental impact.

The platform is responsive, accessible, and optimized for all devices, thanks to Blade and Vite.
Prerequisites

PHP >= 8.2
Composer
Node.js >= 18
npm
MySQL
Laravel 12

Installation

Clone the repository:
git clone https://github.com/Saif-Hlaimi/DevMinds_Ecoevents_Laravel.git
cd ecoevents


Install Composer dependencies:
composer install


Install npm dependencies for Vite:
npm install


Copy the environment file:
cp .env.example .env


Generate the Laravel application key:
php artisan key:generate


Run migrations and seeders:
php artisan migrate
php artisan db:seed



Configuration

Database: Configure your database in the .env file:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecoevents
DB_USERNAME=root
DB_PASSWORD=


Vite: Configure the development server URL in .env:
VITE_APP_URL=http://localhost:5173


External Services:

Mail: Set up a service like Mailtrap for notifications.
Payments: Add your Stripe API keys in .env.
Storage: Configure Laravel Filesystem for event and product images.


Key Environment Variables:

APP_NAME=EcoEvents
APP_URL=http://localhost:8000
MAIL_MAILER=smtp



For more details, refer to the Laravel documentation and Vite documentation.
Usage

Start the Laravel server:
php artisan serve


Start the Vite development server:
npm run dev

Access the application at http://localhost:8000.


The platform includes an admin dashboard for content management and a smooth frontend experience powered by Blade and Vite.
Architecture

Backend: Laravel 12.
Frontend: Blade templates for structure, with Vite for fast asset compilation (JS, CSS).
Database: Eloquent models for User, Event, Donation, Group, Product.
Security: Form validation, CSRF protection, rate limiting.

The code adheres to PSR-12 conventions and follows an MVC architecture for optimal maintainability.

Thank you for joining the mission for a more sustainable world! 🌍
