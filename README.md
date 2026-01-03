# E-Paper Admin Dashboard

A modern admin dashboard for managing digital publications, customers, and subscriptions built with Laravel 10 and Tailwind CSS.

## ✨ Features

- **Customer Management**: Create, edit, view customer details with subscription info
- **Publication Management**: Manage publications with active/inactive status tracking
- **Copy Distribution**: Track and manage publication copies sent to customers
- **WhatsApp Integration**: Send publications to customers via WhatsApp
- **Dashboard**: Real-time statistics and quick actions
- **Modern UI**: Professional gradient design with responsive layout

## 🛠 Tech Stack

- Laravel 10.50.0 | PHP 8.2.12
- MySQL Database
- Blade Templates + Tailwind CSS
- Vite Build Tool
- WhatsApp API Integration (UltraMsg)

## 📦 Quick Start

### Prerequisites
- PHP 8.2+, Composer, MySQL, Node.js

### Setup

1. **Install dependencies**
   ```bash
   cd c:\laragon\www\e-paper
   composer install
   npm install
   ```

2. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Setup database**
   ```bash
   # Update .env with database credentials
   php artisan migrate
   ```

4. **Build & Run**
   ```bash
   npm run dev
   php artisan serve
   ```

Access at `http://localhost:8000`

## 📁 Main Modules

| Module | Purpose |
|--------|---------|
| **Customers** | Manage customer accounts and subscriptions |
| **Publications** | Create and manage publications |
| **Copies** | Track distributed publication copies |
| **Dashboard** | System statistics and quick actions |

## 🗄️ Database Tables

- **customers**: Customer info, subscription dates, payment details
- **publications**: Publication data with status
- **copies**: Links customers to publications with distribution dates

## 🔐 Authentication

Laravel Breeze authentication system with protected routes.

## 📝 Configuration

Update `.env` for WhatsApp API:
```
ULTRAMSG_INSTANCE=your_instance
ULTRAMSG_TOKEN=your_token
```

## 📄 License

Built with Laravel. For internal use.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
