# C2C E-commerce Platform

A full-featured Consumer-to-Consumer (C2C) e-commerce platform built with Laravel, enabling users to buy and sell products in a marketplace environment.

## 🚀 Live Demo

**Production URL:** [https://c2c-ecommerce-laravel-production.up.railway.app](https://c2c-ecommerce-laravel-production.up.railway.app)

## ✨ Features

- User authentication and authorization (Buyers, Sellers, Admins)
- Product listing and management
- Category-based product organization
- Product search and filtering
- Shopping cart functionality
- Order management
- User profiles and seller dashboards
- Admin panel for platform management
- Image upload and management
- Session-based shopping experience

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Web server (Apache/Nginx)

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure your database settings
4. Generate application key: `php artisan key:generate`
5. Run database migrations: `php artisan migrate`
6. Seed the database: `php artisan db:seed`
7. Configure your web server to point to the `public` directory

## Usage

- Access the application through your configured web server
- Register as a new user (Buyer/Seller)
- Sellers can list products for sale
- Buyers can browse, search, and purchase products
- Admins can manage users, products, and platform settings

## License

This project is proprietary software. All rights reserved.

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
