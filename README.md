# Kupoval - Online Art Platform

## About the Project
Kupoval is an e-commerce platform developed for an independent artist specializing in paintings and art prints inspired by pop culture. This project aims to provide a complete solution for managing online art sales, replacing a previously manual process based on social media.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-13-336791?style=for-the-badge&logo=postgresql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)

## Features

### For Customers
- Browse and purchase artwork
- Secure shopping cart and payment system
- Account creation and order history
- Discover upcoming events and exhibitions

### For Artist/Administrator
- Complete artwork management (CRUD)
- Sales dashboard and statistics
- Order and shipping management
- Event and promotion organization
- Intuitive administrative interface

## Technologies Used

### Backend
- Laravel 11
- PostgreSQL
- Laravel Sail (Docker)

### Frontend
- Blade
- Livewire
- TailwindCSS
- Alpine.js
- CKEditor 5
- Bootstrap Icons
- SimpleLightbox
- Glide.js

### Payment and Infrastructure
- Stripe for payment processing
- Docker for containerization
- PgAdmin for database management

## Site Structure

The application is divided into three main sections:

1. **Public Section**: Art showcase and online store
2. **Customer Area**: Account management and order tracking
3. **Administration**: Complete platform management

## Installation and Configuration

This repository is primarily for demonstration purposes. For a complete installation with sensitive data, please refer to the `INSTALLATION.md` file (not included in the public repository).

### Option 1: Complete Setup (All-in-one command)
Run this command to install all dependencies and start the application:

```bash
npm install && composer install && ./vendor/bin/sail build && ./vendor/bin/sail up -d
```

### Option 2: Step-by-Step Setup
If you prefer to run commands individually:

1. Clone the repository:
   ```bash
   git clone https://github.com/username/kupoval.git
   cd kupoval
   ```

2. Install JavaScript dependencies:
   ```bash
   npm install
   ```

3. Install PHP dependencies:
   ```bash
   composer install
   ```

4. Build the Docker containers:
   ```bash
   ./vendor/bin/sail build
   ```

5. Start the application:
   ```bash
   ./vendor/bin/sail up -d
   ```

### Managing the Application
- Restart the application: `./vendor/bin/sail up -d`
- Stop the application: `./vendor/bin/sail down`
- View application logs: `./vendor/bin/sail logs`

**Note:** You'll need to set up your own environment variables in the `.env` file for the application to function correctly. See `.env.example` for required variables.

### Database Administration with pgAdmin
- pgAdmin is accessible at: http://localhost:5050
- Database connection settings can be found in the `docker/pgadmin/servers.json` file
- You'll need to customize these settings for your own environment

## Screenshots

*Screenshots will be added soon*

## Development

This project was developed as part of an integrated project at Collège Multihexa. It addresses a real need for an artist looking to improve their online presence and automate sales management.

## Privacy

All sensitive data has been removed from this public repository. Access information, API keys, and other configurations are managed separately for security reasons.

## Future Development

- Integration of automated marketing features
- Advanced sales data analysis
- Enhanced customer experience personalization

---

Project developed by Médérick Bernier
