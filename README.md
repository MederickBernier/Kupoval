# Kupoval

## About the Project
Kupoval is a full e-commerce platform developed with Laravel 11 and PostgreSQL.  
It demonstrates my ability to design and maintain a complete backend system with a focus on performance, security, and modern deployment practices.

### Why this matters
This project highlights practical backend engineering skills directly relevant to production environments:
- Database optimization with PostgreSQL
- Secure payments integration using Stripe
- Dockerized environment for consistent deployment
- Role-based dashboards (customers & administrators)

---

## Features

### Customer
- Product catalog with category browsing
- User registration and profile management
- Shopping cart & order workflow
- Secure checkout with Stripe integration

### Admin
- Authentication & role management
- CRUD for products and categories
- Order management (status updates, tracking)
- Dashboard for activity overview

---

## Technologies Used
- **Backend:** PHP 8.2, Laravel 11
- **Database:** PostgreSQL
- **Containerization:** Docker, Laravel Sail
- **Payments:** Stripe
- **Frontend (basic):** Blade templates, Bootstrap
- **Other:** PgAdmin, MailHog (for dev), Composer

---

## My Contributions
- Backend architecture and Laravel setup
- Database schema design and PostgreSQL integration
- Secure payment integration with Stripe
- Docker setup for local development
- Implementation of validation and middleware for security (SQL injection, XSS, CSRF protection)

---

## Screenshots
*(To be added: storefront and admin dashboard views)*

---

## Installation

<details>
<summary>Click to expand installation steps</summary>

### Prerequisites
- Docker & Docker Compose
- PHP 8.2
- Composer

### Setup
```bash
# Clone the repo
git clone https://github.com/MederickBernier/Kupoval.git
cd Kupoval

# Install dependencies
composer install

# Copy environment variables
cp .env.example .env

# Start environment with Sail
./vendor/bin/sail up -d

# Run migrations & seeders
./vendor/bin/sail artisan migrate --seed
```
</details>

---

## Future Development
- Add product search and filtering
- Improve database query optimization and caching for large catalogs
- Expand admin analytics dashboards
- Integration with additional payment providers

---

## Privacy
This project was originally developed as part of an integrated AEC project.  
It has been adapted and published here to showcase backend skills; sensitive data has been removed.
