# E-Paper Admin Dashboard

A modern admin dashboard for managing digital publications, customers, and subscriptions built with Laravel 10 and Tailwind CSS.

## ✨ Features

- **Customer Management**: Create, edit, view customer details with subscription info
- **Publication Management**: Manage publications with active/inactive status tracking
- **Copy Distribution**: Track and manage publication copies sent to customers
- **WhatsApp Integration**: Send publications to customers via WhatsApp
- **Dashboard**: Real-time statistics and quick actions
- **Modern UI**: Professional gradient design with responsive layout

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

## 📁 Main Modules

| Module | Purpose |
|--------|---------|
| **Customers** | Manage customer accounts and subscriptions |
| **Publications** | Create and manage publications |
| **Copies** | Track distributed publication copies |
| **Dashboard** | System statistics and quick actions |

## 🔐 Authentication

Laravel Breeze authentication system with protected routes.

## 📝 Configuration

Update `.env` for WhatsApp API:
```
ULTRAMSG_INSTANCE=your_instance
ULTRAMSG_TOKEN=your_token
```

## 📄 License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.

## Contributing

This is a private project for Wijeya Newspapers Limited. For internal development inquiries, please contact the development team.

## Security Vulnerabilities

If you find a security vulnerability, please report it privately to oanuttara@gmail.com instead of opening a public issue. Include:
- A clear description of the issue and steps to reproduce
- Expected vs actual behavior
- Any relevant logs or stack traces

We aim to acknowledge reports within 2 business days.
